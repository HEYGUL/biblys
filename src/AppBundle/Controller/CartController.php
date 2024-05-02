<?php


namespace AppBundle\Controller;

use Article;
use ArticleManager;
use Biblys\Service\CurrentUser;
use Cart;
use CartManager;
use CFReward;
use CFRewardManager;
use Entity\Exception\CartException;
use Exception;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Stock;
use StockManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CartController extends Controller
{
    /**
     * @throws Exception
     */
    public function addArticleAction(
        Request $request,
        CurrentUser $currentUser,
        int $articleId
    ): JsonResponse|RedirectResponse
    {
        $am = new ArticleManager();
        /** @var Article $article */
        $article = $am->getById($articleId);
        if (!$article) {
            throw new BadRequestHttpException(
                "Cannot find article with id $articleId"
            );
        }

        try {
            $cm = new CartManager();
            $cart = $currentUser->getOrCreateCart();
            /** @var Cart $cartEntity */
            $cartEntity = $cm->getById($cart->getId());
            $cm->addArticle($cartEntity, $article);
            $cm->updateFromStock($cartEntity);
        } catch(CartException $exception) {
            throw new ConflictHttpException($exception->getMessage());
        }

        if (in_array("application/json", $request->getAcceptableContentTypes())) {
            return new JsonResponse();
        }

        return new RedirectResponse("/pages/cart");
    }

    /**
     * @throws Exception
     */
    public function addStockAction(CurrentUser $currentUser, int $stockId): Response
    {
        $sm = new StockManager();
        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new BadRequestHttpException(
                "Cannot find stock with id $stockId"
            );
        }

        $cm = new CartManager();
        $cart = $currentUser->getOrCreateCart();
        /** @var Cart $cartEntity */
        $cartEntity = $cm->getById($cart->getId());
        $cm->addStock($cartEntity, $stock);
        $cm->updateFromStock($cartEntity);

        return new JsonResponse();
    }

    /**
     * @throws PropelException
     */
    public function addCrowdfundingRewardAction(CurrentUser $currentUser, int $rewardId): Response
    {
        $cfrm = new CFRewardManager();
        /** @var CFReward $reward */
        $reward = $cfrm->getById($rewardId);
        if (!$reward) {
            throw new BadRequestHttpException(
                "Cannot find reward with id $rewardId"
            );
        }

        $cm = new CartManager();
        $cart = $currentUser->getOrCreateCart();
        /** @var Cart $cartEntity */
        $cartEntity = $cm->getById($cart->getId());
        $cm->addCFReward($cartEntity, $reward);
        $cm->updateFromStock($cartEntity);

        return new JsonResponse();
    }

    /**
     * @throws Exception
     */
    public function removeStockAction(Request $request, CurrentUser $currentUser, int $stockId): Response
    {
        $sm = new StockManager();
        /** @var Stock $stock */
        $stock = $sm->getById($stockId);
        if (!$stock) {
            throw new BadRequestHttpException(
                "Cannot find stock with id $stockId"
            );
        }

        $cm = new CartManager();
        $cart = $currentUser->getOrCreateCart();
        /** @var Cart $cartEntity */
        $cartEntity = $cm->getById($cart->getId());
        $cm->removeStock($stock);
        $cm->updateFromStock($cartEntity);

        if ($request->headers->get("Accept") === "application/json") {
            return new JsonResponse();
        }

        return new RedirectResponse("/pages/cart?removed=1");
    }

    /**
     * GET /cart/summary
     * @throws PropelException
     */
    public function summaryAction(CurrentUser $currentUser): JsonResponse
    {
        $cm = new CartManager();
        $cart = $currentUser->getCart();
        if (!$cart) {
            $cartSummary = Cart::getOneLineEmpty();
            return new JsonResponse($cartSummary);
        }

        /** @var Cart $cartEntity */
        $cartEntity = $cm->getById($cart->getId());
        $cartSummary = $cartEntity->getOneLine();
        return new JsonResponse(["summary" => $cartSummary]);
    }
}