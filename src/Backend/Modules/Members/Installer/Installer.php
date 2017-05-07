<?php

namespace Backend\Modules\Members\Installer;

use Backend\Core\Installer\ModuleInstaller;
use Backend\Core\Engine\Model as BackendModel;

/**
 * Class Installer
 * @package Backend\Modules\Profiles\Installer
 */
class Installer extends ModuleInstaller
{

    /**
     *
     */
    public function install()
    {
        $this->importSQL(dirname(__FILE__).'/Data/install.sql');

        $this->addModule('Members');

        $this->importLocale(dirname(__FILE__).'/Data/locale.xml');

        $this->setModuleRights(1, 'Members');
        $this->setActionRights(1, 'Members', 'Add');
        $this->setActionRights(1, 'Members', 'Edit');
        $this->setActionRights(1, 'Members', 'Block');
        $this->setActionRights(1, 'Members', 'Delete');
        $this->setActionRights(1, 'Members', 'Index');
        $this->setActionRights(1, 'Members', 'AddGroup');
        $this->setActionRights(1, 'Members', 'EditGroup');
        $this->setActionRights(1, 'Members', 'DeleteGroup');
        $this->setActionRights(1, 'Members', 'Groups');

        $this->insertExtra('Members', 'block', 'Members', null, null, 'N', 1000);
        $this->insertExtra('Members', 'block', 'MembersDashboard', 'Dashboard', null, 'N', 1001);
        $this->insertExtra('Members', 'block', 'MembersAccount', 'Account', null, 'N', 1002);
        $this->insertExtra('Members', 'block', 'MembersAddress', 'Address', null, 'N', 1003);
        $this->insertExtra('Members', 'block', 'MembersRegistration', 'Registration', null, 'N', 1004);
        $this->insertExtra('Members', 'block', 'MembersRequisites', 'Requisites', null, 'N', 1005);

        $this->insertExtra('Members', 'widget', 'MembersAddAddress', 'AddAddress', null, 'N', 1006);
        $this->insertExtra('Members', 'widget', 'MembersLinks', 'Links', null, 'N', 1007);
        $this->insertExtra('Members', 'widget', 'MembersPromotion', 'Promotion', null, 'N', 1008);

        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationMembersId = $this->setNavigation($navigationModulesId, 'Members');
        $this->setNavigation(
            $navigationMembersId,
            'Overview',
            'members/index',
            array(
                'members/add',
                'members/edit',
                'members/add_address',
                'members/edit_address',
            )
        );
        $this->setNavigation(
            $navigationMembersId,
            'OverviewPending',
            'members/pending',
            array(
                'members/edit_requisites',
            )
        );
        $this->setNavigation(
            $navigationMembersId,
            'OverviewRequisites',
            'members/requisites'
        );
        $this->setNavigation(
            $navigationMembersId,
            'Groups',
            'members/groups',
            array(
                'members/add_group',
                'members/edit_group',
            )
        );

        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, 'Members', 'members/settings');
    }
}
