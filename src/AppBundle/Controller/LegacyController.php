<?php


namespace AppBundle\Controller;

use Biblys\Service\Config;
use Biblys\Service\CurrentSite;
use Biblys\Service\Mailer;
use Exception;
use Framework\Controller;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;

class LegacyController extends Controller
{
    /**
     * @throws PropelException
     * @throws Exception
     */
    public function defaultAction(
        Request $request,
        Session $session,
        Mailer $mailer,
        Config $config,
        CurrentSite $currentSite,
        UrlGenerator $urlGenerator,
    ): Response
    {
        global $_SITE, $_ECHO, $_SQL, $urlgenerator;

        $_PAGE = $request->get('page', 'home');

        $_PAGE_TYPE = substr($_PAGE, 0, 4);
        if ($_PAGE_TYPE == 'adm_') {
            self::authAdmin($request);
        }
        if ($_PAGE_TYPE == 'pub_') {
            self::authPublisher($request, null);
        }
        if ($_PAGE_TYPE == 'log_') {
            self::authUser($request);
        }

        // Get correct controller for called url
        $controller_path = get_controller_path($_PAGE);
        if ($controller_path) {
            $_INCLUDE = $controller_path;
        }

        // If static page, redirect to new /page/:slug url
        else {
            $staticPageUrl = $urlGenerator->generate("static_page_show", ["slug" => $_PAGE]);
            return new RedirectResponse($staticPageUrl, 301);
        }

        // INCLUDE PAGE EN COURS
        $_ECHO = null;
        $response = require $_INCLUDE;

        if (!$response instanceof Response && isset($_ECHO)) {
            trigger_error("Using \$_ECHO in $_INCLUDE. Legacy controllers should return a Response.", E_USER_DEPRECATED);
            $response = new Response($_ECHO);
        }

        // Is this still used?
        if (isset($_JSON)) {
            trigger_error("Using \$_JSON in $_INCLUDE. Legacy controllers should return a Response", E_USER_DEPRECATED);
            $_JSON->send();
            die();
        }

        // If response is JSON, return immediately and die
        // Is this still necessary? Should be ok to return JsonResponse here.
        if ($response instanceof JsonResponse) {
            $response->send();
            die();
        }

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        if (isset($GLOBALS["_PAGE_TITLE"])) {
            trigger_deprecation(
                "biblys",
                "2.59.0",
                "Using \$_PAGE_TITLE to set page title is deprecated. Use \$request->attributes->set(\"page_title\", …) instead.");
            $request->attributes->set("page_title", $GLOBALS["_PAGE_TITLE"]);
        }

        return $this->render("AppBundle:Legacy:default.html.twig", [
            "title" => $request->attributes->get("page_title"),
            "content" => $response->getContent(),
        ]);
    }
}