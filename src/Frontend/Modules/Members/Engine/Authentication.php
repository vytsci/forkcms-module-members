<?php

namespace Frontend\Modules\Members\Engine;

use Frontend\Modules\Profiles\Engine\Authentication as FrontendProfilesAuthentication;

use Common\Modules\Members\Entity\Member;

/**
 * Class Authentication
 * @package Frontend\Modules\Members\Engine
 */
class Authentication extends FrontendProfilesAuthentication
{
    /**
     * @var Member
     */
    private static $member;

    /**
     * @return Member|null
     */
    public static function getMember()
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        if (!isset(self::$member) || self::$member->getId() != self::getProfile()->getId()) {
            self::$member = new Member(array(self::getProfile()->getId()));
            self::$member
                ->loadAddresses()
                ->loadRequisites()
                ->loadGroups();
        }

        return self::$member;
    }
}
