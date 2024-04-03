<?php

namespace AppBundle\Controller;

use Biblys\Service\CurrentSite;
use Biblys\Service\TemplateService;
use Framework\Controller;
use Model\ArticleQuery;
use Model\BookCollectionQuery;
use Model\SpecialOfferQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SpecialOfferController extends Controller
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws PropelException
     * @throws LoaderError
     */
    public function indexAction(
        Request $request,
        CurrentSite $currentSite,
        TemplateService $templateService
    ): Response
    {
        self::authAdmin($request);

        $offers = SpecialOfferQuery::create()
            ->filterBySite($currentSite->getSite())
            ->find();

        return $templateService->renderResponse('AppBundle:SpecialOffer:index.html.twig', [
            'offers' => $offers->getArrayCopy(),
        ]);
    }

    /**
     * @throws LoaderError
     * @throws PropelException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function editAction(
        Request $request,
        CurrentSite $currentSite,
        TemplateService $templateService,
        int $id,
    ): Response
    {
        self::authAdmin($request);

        $offer = SpecialOfferQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneById($id);

        if (!$offer) {
            throw new NotFoundHttpException("Special offer not found");
        }

        $collections = BookCollectionQuery::create()
            ->filterByPublisherId($currentSite->getOption("publisher_filter"))
            ->orderByName()
            ->find();

        $articles = ArticleQuery::create()
            ->select(["id", "titleAlphabetic"])
            ->filterByPublisherId($currentSite->getOption("publisher_filter"))
            ->orderByTitleAlphabetic()
            ->find();

        return $templateService->renderResponse(
            "AppBundle:SpecialOffer:edit.html.twig", [
                "offer" => $offer,
                "collections" => $collections->getArrayCopy(),
                "articles" => $articles->getArrayCopy(),
        ]);
    }

    /**
     * @throws PropelException
     */
    public function updateAction(
        Request $request,
        CurrentSite $currentSite,
        Session $session,
        UrlGenerator $urlGenerator,
        int $id,
    ): RedirectResponse
    {
        self::authAdmin($request);

        $offer = SpecialOfferQuery::create()
            ->filterBySite($currentSite->getSite())
            ->findOneById($id);

        if (!$offer) {
            throw new NotFoundHttpException("Special offer not found");
        }

        $offer->setName($request->request->get("name"));
        $offer->setDescription($request->request->get("description"));
        $offer->setStartDate($request->request->get("start_date"));
        $offer->setEndDate($request->request->get("end_date"));
        $offer->setTargetQuantity($request->request->get("target_quantity"));
        $offer->setTargetCollectionId($request->request->get("target_collection_id"));
        $offer->setFreeArticleId($request->request->get("free_article_id"));
        $offer->save();

        $session->getFlashBag()->add(
            "success",
            "Offre spéciale « {$offer->getName()} » mise à jour avec succès"
        );
        $indexUrl = $urlGenerator->generate("special_offer_edit", ["id" => $offer->getId()]);
        return new RedirectResponse($indexUrl);
    }
}