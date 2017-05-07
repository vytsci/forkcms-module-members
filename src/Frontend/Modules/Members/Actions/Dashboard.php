<?php

namespace Frontend\Modules\Members\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Profiles\Engine\Authentication as FrontendProfilesAuthentication;

class Dashboard extends FrontendBaseBlock
{

    public function execute()
    {
        if (!FrontendProfilesAuthentication::isLoggedIn()) {
            $this->redirect(
                FrontendNavigation::getURLForBlock(
                    'Profiles',
                    'Login'
                ).'?queryString='.FrontendNavigation::getURLForBlock('Profiles'),
                307
            );
        }

        parent::execute();

        $this->loadTemplate();

        $this->parse();
    }

    protected function parse()
    {
    }
}
