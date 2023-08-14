<?php


namespace Biblys\Service;


use DateTime;
use Exception;
use Model\AxysAccount;
use Model\Cart;
use Model\CartQuery;
use Model\Option;
use Model\OptionQuery;
use Model\Publisher;
use Model\Right;
use Model\SessionQuery;
use Model\Site;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CurrentUser
{
    private ?AxysAccount $axysAccount;
    private ?string $token;
    private ?CurrentSite $currentSite;

    public function __construct(?AxysAccount $axysAccount, ?string $token)
    {
        $this->axysAccount = $axysAccount;
        $this->token = $token;
    }

    /**
     * @param Request $request
     * @return CurrentUser
     * @throws PropelException
     */
    public static function buildFromRequest(Request $request): CurrentUser
    {
        $cookieToken = $request->cookies->get("user_uid");
        $headerToken = $request->headers->get("AuthToken");

        $token = $cookieToken ?: $headerToken;

        if ($token === null) {
            $visitorUid = $request->cookies->get("visitor_uid");
            return new CurrentUser(null, $visitorUid);
        }

        $session = SessionQuery::create()->filterByToken($token)->findOne();
        if (!$session) {
            return new CurrentUser(null, $token);
        }

        if (($session->getExpiresAt() < new DateTime())) {
            return new CurrentUser(null, $token);
        }

        $axysAccount = $session->getAxysAccount();
        if (!$axysAccount) {
            return new CurrentUser(null, $token);
        }

        return new CurrentUser($axysAccount, $token);
    }

    /**
     * @throws PropelException
     */
    public static function buildFromRequestAndConfig(Request $request, Config $config): CurrentUser
    {
        $currentUser = self::buildFromRequest($request);

        $currentSite = CurrentSite::buildFromConfig($config);
        $currentUser->injectCurrentSite($currentSite);

        return $currentUser;
    }

    public function isAuthentified(): bool
    {
        if ($this->axysAccount) {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function isAdmin(): bool
    {
        $site = $this->getCurrentSite()->getSite();
        return $this->isAdminForSite($site);
    }

    public function isAdminForSite(Site $site): bool
    {
        if ($this->axysAccount) {
            return $this->axysAccount->isAdminForSite($site);
        }

        return false;
    }

    /**
     * @return AxysAccount
     */
    public function getAxysAccount(): AxysAccount
    {
        if ($this->axysAccount === null) {
            throw new UnauthorizedHttpException("","Identification requise.");
        }

        return $this->axysAccount;
    }

    /**
     * @throws PropelException
     */
    public function hasRightForPublisher(Publisher $publisher): bool
    {
        if ($this->axysAccount) {
            return $this->axysAccount->hasRightForPublisher($publisher);
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function hasPublisherRight(): bool
    {
        if ($this->axysAccount) {
            return $this->axysAccount->hasPublisherRight();
        }

        return false;
    }

    /**
     * @throws PropelException
     */
    public function getOption(string $key): ?string
    {
        if (!$this->axysAccount) {
            return null;
        }

        $option = OptionQuery::create()
            ->filterByAxysAccount($this->axysAccount)
            ->filterByKey($key)
            ->findOne();

        return $option?->getValue();

    }

    /**
     * @throws PropelException
     */
    public function setOption(string $key, string $value): void
    {
        $option = OptionQuery::create()
            ->filterByAxysAccount($this->axysAccount)
            ->filterByKey($key)
            ->findOne();

        if (!$option) {
            $option = new Option();
            $option->setAxysAccount($this->axysAccount);
            $option->setKey($key);
        }

        $option->setValue($value);
        $option->save();
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @throws PropelException
     * @throws Exception
     */
    public function getCart(): ?Cart
    {
        if (!$this->isAuthentified()) {
            return CartQuery::create()->findOneByUid($this->token);
        }

        return CartQuery::create()
            ->filterBySite($this->getCurrentSite()->getSite())
            ->filterByAxysAccount($this->axysAccount)
            ->findOne();
    }

    private function injectCurrentSite(CurrentSite $currentSite): void
    {
        $this->currentSite = $currentSite;
    }

    /**
     * @throws Exception
     */
    public function getCurrentSite(): CurrentSite
    {
        if ($this->currentSite === null) {
            throw new Exception(
                "CurrentSite dependency was not injected in the CurrentUserService. Use the buildFromRequestAndConfig static method to build CurrentUser"
            );
        }

        return $this->currentSite;
    }

    /**
     * @throws PropelException
     */
    public function getCurrentRight(): ?Right
    {
        return $this->axysAccount?->getCurrentRight();

    }
}
