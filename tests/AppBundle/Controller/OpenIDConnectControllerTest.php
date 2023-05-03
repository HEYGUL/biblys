<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace AppBundle\Controller;

use Biblys\Service\Axys;
use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\OpenIDConnectProviderService;
use Biblys\Service\TokenService;
use Biblys\Test\ModelFactory;
use Biblys\Test\RequestFactory;
use DateTime;
use Exception;
use Facile\OpenIDClient\Token\TokenSetInterface;
use Firebase\JWT\JWT;
use JsonException;
use Model\SessionQuery;
use PHPUnit\Framework\TestCase;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__."/../../setUp.php";

class OpenIDConnectControllerTest extends TestCase
{
    /**
     * @throws JsonException
     */
    public function testAxys()
    {
        // given
        $axys = $this->createMock(Axys::class);
        $axys->method("getClientSecret")->willReturn("secret_key");
        $request = new Request();
        $request->query->add(["return_url" => "/my-account"]);
        $tokenService = $this->createMock(TokenService::class);
        $tokenService->method("createOIDCStateToken")
            ->with("/my-account", "secret_key")
            ->willReturn("oidc-state-token");

        $controller = new OpenIDConnectController();
        $openIDConnectProviderService = $this->createMock(OpenIDConnectProviderService::class);
        $openIDConnectProviderService
            ->method("getAuthorizationUri")
            ->willReturn("https://axys.me/authorize");

        // when
        $response = $controller->axys($request, $tokenService, $openIDConnectProviderService);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("https://axys.me/authorize", $response->getTargetUrl());
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function testCallback()
    {
        // given
        $request = Request::create("https://www.biblys.fr/openid/callback");
        $stateToken = JWT::encode(["return_url" => "/my-account"], "secret_key", "HS256");
        $request->query->set("code", "authorization_code");
        $request->query->set("state", $stateToken);

        $user = ModelFactory::createUser();
        $site = ModelFactory::createSite();
        $currentSite = new CurrentSite($site);

        $controller = new OpenIDConnectController();
        $config = new Config(["axys" => ["client_secret" => "secret_key"]]);

        $tokenSet = $this->createMock(TokenSetInterface::class);
        $tokenSet->method("claims")->willReturn(["sub" => $user->getId(), "exp" => 1682278410]);
        $openIDConnectProviderService = $this->createMock(OpenIDConnectProviderService::class);
        $openIDConnectProviderService->method("getTokenSet")->willReturn($tokenSet);

        // when
        $response = $controller->callback($request, $currentSite, $config, $openIDConnectProviderService);

        // then
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals("/my-account", $response->getTargetUrl());

        $cookies = $response->headers->getCookies();
        $this->assertCount(1, $cookies);

        $userUidCookie = $cookies[0];
        $this->assertEquals("user_uid", $userUidCookie->getName());
        $this->assertEquals(1682278410, $userUidCookie->getExpiresTime());
        $session = SessionQuery::create()
            ->filterBySite($site)
            ->filterByUser($user)
            ->findOneByToken($userUidCookie->getValue());
        $this->assertNotNull($session);
        $this->assertEquals(new DateTime("@1682278410"), $session->getExpiresAt());
    }
}
