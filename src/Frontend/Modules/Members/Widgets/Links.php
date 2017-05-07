<?php

namespace Frontend\Modules\Members\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\Members\Engine\Authentication as FrontendMembersAuthentication;

class Links extends FrontendBaseWidget
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
        $this->tpl->assign('widgetMembersLinksIsLoggedIn', $isLoggedIn);
        $this->tpl->assign(
            'widgetMembersLinksProfile',
            $isLoggedIn ? FrontendMembersAuthentication::getProfile()->toArray() : array()
        );
    }
}
