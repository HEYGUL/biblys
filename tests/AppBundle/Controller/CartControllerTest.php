<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

use AppBundle\Controller\CartController;
use Biblys\Test\Factory;

require_once __DIR__."/../../setUp.php";

class CartControllerTest extends PHPUnit\Framework\TestCase
{
    public function testAddArticle()
    {
        global $_V, $site;

        // given
        $cm = new CartManager();
        $site->setOpt("virtual_stock", 1);
        $controller = new CartController();
        $cart = $_V->getCart("create");
        $cm->vacuum($cart);
        $article = Factory::createArticle();

        // when
        $response = $controller->addArticleAction(
            $article->get("id")
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertTrue(
            $cart->containsArticle($article),
            "it should have added article to cart"
        );
        $this->assertEquals(
            1,
            $cart->get("count"),
            "it should have updated cart article count"
        );
    }

    public function testAddStockCopy()
    {
        global $_V, $site;

        // given
        $cm = new CartManager();
        $site->setOpt("virtual_stock", 0);
        $controller = new CartController();
        $cart = $_V->getCart("create");
        $cm->vacuum($cart);
        $stock = Factory::createStock();

        // when
        $response = $controller->addStockAction(
            $stock->get("id")
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertTrue(
            $cart->containsStock($stock),
            "it should have added article to cart"
        );
        $this->assertEquals(
            1,
            $cart->get("count"),
            "it should have updated cart article count"
        );
    }

    public function testAddCrowdfundingReward()
    {
        global $_V, $site;

        // given
        $cm = new CartManager();
        $controller = new CartController();
        $cart = $_V->getCart("create");
        $cm->vacuum($cart);
        $reward = Factory::createCrowfundingReward();

        // when
        $response = $controller->addCrowdfundingRewardAction(
            $reward->get("id")
        );

        // then
        $this->assertEquals(
            200,
            $response->getStatusCode(),
            "it should respond with http 200"
        );
        $this->assertTrue(
            $cart->containsReward($reward),
            "it should have added article to cart"
        );
        $this->assertEquals(
            1,
            $cart->get("count"),
            "it should have updated cart article count"
        );
    }
}
