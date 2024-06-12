<?php

namespace Framework;

use Biblys\Isbn\Isbn as Isbn;
use Framework\Exception\AuthException;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Visitor;

class Controller
{
    /**
     * @var Visitor
     */
    protected $user;

    public function __construct()
    {
        global $_V, $urlgenerator;

        $this->user = $_V;
        $this->url = $urlgenerator;
    }

    /**
     * Check current user's rank.
     *
     * @param string $rank minimal rank required
     * @param int    $id   publisher id required
     *
     * @return bool true if user's rank match requirement
     */
    public function auth($rank = 'user', $id = null)
    {
        if ($rank == 'root' && !$this->user->isRoot()) {
            throw new AuthException('Accès réservé aux super-administrateurs.');
        }

        if ($rank == 'admin' && !$this->user->isAdmin()) {
            throw new AuthException('Accès réservé aux administrateurs.');
        }

        if ($rank == 'publisher' && !$this->user->isPublisherWithId($id) && !$this->user->isAdmin()) {
            throw new AuthException('Accès réservé aux éditeurs.');
        }

        if ($rank == 'user' && !$this->user->isLogged()) {
            throw new AuthException('Identification requise.');
        }

        return true;
    }

    /**
     * Returns an entity manager.
     *
     * @param string $entity the entity we want a manager for
     *
     * @return EntityManager child class
     */
    public function entityManager($entity)
    {
        $class = $entity.'Manager';

        return new $class();
    }

    /**
     * Returns a Response with a rendered template.
     *
     * @param string $template template file path
     * @param array  $vars     template variables
     *
     * @return Response a Response object containing the rendered template
     */
    public function render($template, array $vars = [])
    {
        global $site, $urlgenerator, $request, $config, $axys;

        //** Twig custom functions **//

        $functions = [];

        // return relative url for a route
        $functions[] = new \Twig\TwigFunction('path', function ($route, $vars = []) {
            global $urlgenerator;

            return $urlgenerator->generate($route, $vars);
        });

        // return absolute url for a route
        $functions[] = new \Twig\TwigFunction('url', function ($route, $vars = []) {
            global $urlgenerator, $site;

            return 'http://'.$site->get('domain').$urlgenerator->generate($route, $vars);
        });

        // returns share buttons for url
        $functions[] = new \Twig\TwigFunction('share_buttons', function ($url, $message = '', $options = []) {
            return share_buttons($url, $message, $options);
        });

        //** Twig custom filters **//

        $filters = [];

        // authors
        $filters[] = new \Twig\TwigFilter('authors', function ($authors) {
            $authors = explode(',', $authors);
            $count = count($authors);

            if ($count > 2) {
                return 'COLLECTIF';
            }

            if ($count == 2) {
                return $authors[0].' & '.$authors[1];
            }

            return $authors[0];
        });

        $filters[] = new \Twig\TwigFilter('currency', function ($amount, $cents = false) {
            return currency($amount, $cents);
        });

        // date
        $filters[] = new \Twig\TwigFilter('date', function ($date, $format = 'd/m/Y') {
            return _date($date, $format);
        });

        // price
        $filters[] = new \Twig\TwigFilter('price', function ($price, $currency = null, $decimals = 2) {
            if ($currency == 'EUR') {
                return number_format(round($price / 100, 6), $decimals, ',', '&#8239;').'&nbsp;&euro;';
            }

            return $price / 100;
        });

        // pluralize
        $filters[] = new \Twig\TwigFilter('pluralize', function ($text, $number) {
            if ($number == 1 || $number == 0) {
                return $text;
            }

            return $text.'s';
        });

        // truncate
        $filters[] = new \Twig\TwigFilter('truncate', function ($text, $length, $ellipsis = '…') {
            return truncate(strip_tags($text), $length, $ellipsis);
        });

        // isbn
        $filters[] = new \Twig\TwigFilter('isbn', function ($ean) {
            return Isbn::convertToIsbn13($ean);
        });

        // Forms
        $defaultFormTheme = 'AppBundle:Main:_form_bootstrap_layout.html.twig';
        $vendorDir = realpath(__DIR__.'/../vendor');
        $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
        $vendorTwigBridgeDir = dirname($appVariableReflection->getFileName());
        $viewsDir = realpath(__DIR__.'/../views');

        // Custom template loader
        $loader = new TemplateLoader($site);

        // Load Twig
        if ($site->get('environment') == 'dev') {
            $twig = new \Twig\Environment($loader, ['strict_variables' => true]);
        } else {
            $twig = new \Twig\Environment($loader, ['strict_variables' => true, 'debug' => true]);
            $twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        // CRSF
        $session = new Session();
        $csrfGenerator = new UriSafeTokenGenerator();
        $csrfStorage = new SessionTokenStorage($session);
        $csrfManager = new CsrfTokenManager($csrfGenerator, $csrfStorage);

        // Forms
        $formEngine = new TwigRendererEngine([$defaultFormTheme], $twig);
        $formRenderer = new FormRenderer($formEngine, new CsrfTokenManager());
        $twig->addExtension(new FormExtension());
        $runtimeLoader = new FactoryRuntimeLoader(
            [
                FormRenderer::class => function () use ($formRenderer) {
                    return $formRenderer;
                },
            ]
        );
        $twig->addRuntimeLoader($runtimeLoader);

        // Global variables
        $app = [
            'request' => $request,
            'user' => $this->user,
            'axys' => $axys,
            'session' => $session,
            'site' => $site,
        ];
        $twig->addGlobal('app', $app);

        // Import functions
        foreach ($functions as $function) {
            $twig->addFunction($function);
        }

        // Import filters
        foreach ($filters as $filter) {
            $twig->addFilter($filter);
        }

        // Load template file
        $template = $twig->load($template);

        // Render template
        try {
            $rendered = $template->render($vars);
        } catch (\Twig\Error_Syntax $e) {
            $error = nl2br(htmlspecialchars($e->getMessage()));
            trigger_error($error);
        } catch (\Twig\Error_Runtime $e) {
            $error = nl2br(htmlspecialchars($e->getMessage()));
            trigger_error($error);
        }

        return new Response($rendered);
    }

    public function redirect(string $url, int $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Set the page title as a Request attribute.
     *
     * @param string $title page title
     */
    public function setPageTitle($title)
    {
        global $request;
        $request->attributes->set('page_title', $title);
    }

    public function setOpengraphTags($tags = [])
    {
        global $request;

        if (!isset($tags['type'])) {
            $tags['type'] = 'website';
        }

        if (!isset($tags['site_name'])) {
            global $site;
            $tags['site_name'] = $site->get('title');
        }

        if (!isset($tags['locale'])) {
            $tags['locale'] = 'fr_FR';
        }

        if (!isset($tags['url'])) {
            $tags['url'] = $request->getUri();
        }

        $request->attributes->set('opengraph_tags', $tags);
    }

    public function setTwitterCardsTags($tags)
    {
        global $request;

        if (!isset($tags['card'])) {
            $tags['card'] = 'summary';
        }

        $request->attributes->set('twitter_cards_tags', $tags);
    }

    /**
     * Generates an url from a route using the Routing component.
     *
     * @return string the generated url
     */
    public function url()
    {
        global $urlgenerator;

        return function () {
            $urlgenerator->generate();
        };
    }

    /**
     * Really generates an url from a route using the Routing component.
     *
     * @return string the generated url
     */
    public function generateUrl(string $route, array $params = [])
    {
        global $urlgenerator;

        return $urlgenerator->generate($route, $params);
    }

    public function getConfig()
    {
        global $config;

        return $config;
    }

    public function getFormFactory()
    {
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();

        return $formFactory;
    }
}
