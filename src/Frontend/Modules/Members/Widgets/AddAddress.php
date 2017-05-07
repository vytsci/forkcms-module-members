<?php

namespace Frontend\Modules\Members\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Engine\Form as FrontendForm;
use Frontend\Core\Engine\Navigation as FrontendNavigation;

use Common\Modules\Members\Engine\Helper as CommonMembersHelper;

class AddAddress extends FrontendBaseWidget
{

    /**
     * @var FrontendForm
     */
    protected $frm;

    public function execute()
    {
        parent::execute();

        $this->loadTemplate();

        $this->loadForm();

        $this->parse();
    }

    private function loadForm()
    {
        $this->frm = new FrontendForm('address', FrontendNavigation::getURLForBlock('Members', 'Address'));

        CommonMembersHelper::parseFieldsAddress($this->frm, FRONTEND_LANGUAGE, true);
    }

    protected function parse()
    {
        $this->frm->parse($this->tpl);
    }
}
