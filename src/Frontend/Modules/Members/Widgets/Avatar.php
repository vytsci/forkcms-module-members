<?php

namespace Frontend\Modules\Members\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\Members\Engine\Model as FrontendMembersModel;

class Avatar extends FrontendBaseWidget
{

    /**
     *
     */
    public function execute()
    {
        parent::execute();

        $this->loadTemplate();

        $this->parse();
    }

    /**
     * @throws \SpoonTemplateException
     */
    protected function parse()
    {
        $this->tpl->assign('widgetMembersAvatar', FrontendMembersModel::getAvatarInfo($this->data['id']));
    }
}
