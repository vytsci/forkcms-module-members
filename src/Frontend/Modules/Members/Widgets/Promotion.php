<?php

namespace Frontend\Modules\Members\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\Members\Engine\Authentication as FrontendMembersAuthentication;

class Promotion extends FrontendBaseWidget
{

    public function execute()
    {
        parent::execute();

        $this->loadTemplate();

        $this->parse();
    }

    protected function parse()
    {
        $isLoggedIn = FrontendMembersAuthentication::isLoggedIn();
        $this->tpl->assign('widgetMembersPromotionsIsLoggedIn', $isLoggedIn);
    }
}
