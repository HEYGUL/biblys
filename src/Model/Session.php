<?php

namespace Model;

use Biblys\Service\CurrentSite;
use DateTime;
use Model\Base\Session as BaseSession;
use Propel\Runtime\Exception\PropelException;
use RandomLib\Factory;
use RandomLib\Generator;

/**
 * Skeleton subclass for representing a row from the 'session' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
class Session extends BaseSession
{
    /**
     * @param AxysUser $user
     * @return Session
     * @throws PropelException
     */
    public static function buildForUser(AxysUser $user): Session
    {
        $session = new Session();
        $session->setAxysUser($user);
        $session->setToken(Session::generateToken());
        $session->setExpiresAt(new DateTime('tomorrow'));
        return $session;
    }

    /**
     * @throws PropelException
     */
    public static function buildForUserAndCurrentSite(
        AxysUser $user,
        CurrentSite $currentSite,
        DateTime $expiresAt
    ): Session
    {
        $session = new Session();
        $session->setAxysUser($user);
        $session->setSite($currentSite->getSite());
        $session->setToken(Session::generateToken());
        $session->setExpiresAt($expiresAt);
        return $session;
    }

    public static function generateToken(): string
    {
        $factory = new Factory();
        $generator = $factory->getMediumStrengthGenerator();
        return $generator->generateString(32, Generator::CHAR_ALNUM);
    }
}
