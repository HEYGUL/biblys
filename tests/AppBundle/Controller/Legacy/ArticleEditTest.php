<?php

namespace AppBundle\Controller\Legacy;

use AppBundle\Controller\LegacyController;
use Biblys\Article\Type;
use Biblys\Legacy\LegacyCodeHelper;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\CurrentUser;
use Biblys\Service\Mailer;
use Biblys\Service\MetaTagsService;
use Biblys\Service\TemplateService;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use Exception;
use Mockery;
use Model\Right;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

require_once __DIR__ . "/../../../setUp.php";


class ArticleEditTest extends TestCase
{
    /**
     * @throws PropelException
     */
    public function testLemoninkFieldIsDisplayed()
    {
        // given
        $controller = require __DIR__."/../../../../controllers/common/php/article_edit.php";

        $article = ModelFactory::createArticle(typeId: Type::EBOOK);
        $request = new Request();
        $request->query->set("id", $article->getId());

        $user = ModelFactory::createUser();
        $currentUser = Mockery::mock(CurrentUser::class);
        $currentUser->shouldReceive("isAdmin")->andReturn(true);
        $currentUser->shouldReceive("getCurrentRight")->andReturn(null);
        $currentUser->shouldReceive("getUser")->andReturn($user);

        $site = ModelFactory::createSite();
        $currentSite = Mockery::mock(CurrentSite::class);
        $currentSite->shouldReceive("getSite")->andReturn($site);
        $currentSite->shouldReceive("getOption")->andReturn("null");
        $currentUser->shouldReceive("authPublisher")->andReturn(true);

        $urlgenerator = Mockery::mock(UrlGenerator::class);
        $urlgenerator->shouldReceive("generate")->andReturn("url");
        $config = Mockery::mock(Config::class);
        $config->shouldReceive("get")->with("lemonink.api_key")->andReturn("abcd1234");

        // when
        $response = $controller($request, $currentUser, $currentSite, $urlgenerator, $config);

        // then
        $this->assertEquals(
            "200",
            $response->getStatusCode(),
            "responds with status code 200"
        );
        $this->assertStringContainsString(
            "Identifiant LemonInk :",
            $response->getContent(),
            "displays the LemonInk field"
        );
    }
}
