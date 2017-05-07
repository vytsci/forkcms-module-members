<?php

namespace Backend\Modules\Members\Engine;

use Api\V1\Engine\Api as BaseAPI;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Profiles\Engine\Api as BackendProfilesApi;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;

/**
 * Class Api
 * @package Backend\Modules\Members\Engine
 */
class Api
{

    /**
     * @return array|bool
     */
    public static function getMember($language)
    {
        if (!BackendProfilesApi::isAuthorized()) {
            return BaseAPI::output(
                BaseAPI::NOT_AUTHORIZED,
                array('message' => 'Not authorized.')
            );
        }

        $email = BackendModel::getContainer()->get('request')->get('email');
        $profile = BackendProfilesModel::getByEmail($email);

        if (!defined('FRONTEND_LANGUAGE')) {
            define('FRONTEND_LANGUAGE', $language);
        }

        $member = CommonMembersModel::getMember($profile['id']);
        $member
            ->loadAddresses($language)
            ->loadGroups($language)
            ->loadRequisites();

        return $member->toArray();
    }
}
