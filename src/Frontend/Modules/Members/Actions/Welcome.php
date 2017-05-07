<?php

namespace Frontend\Modules\Members\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Language as FL;

/**
 * Class Welcome
 * @package Frontend\Modules\Members\Actions
 */
class Welcome extends FrontendBaseBlock
{
    /**
     *
     */
    public function execute()
    {
        $this->overrideHeader();
        $this->fillBreadcrumb();

        parent::execute();

        $this->loadTemplate();
        $this->parse();
    }

    /**
     * @throws \Exception
     */
    private function overrideHeader()
    {
        $this->header->addMetaData(array('name' => 'robots', 'content' => 'noindex, follow'), true);
        $this->header->setPageTitle(FL::lbl('MembersWelcome'));
    }

    /**
     * @throws \Exception
     */
    private function fillBreadcrumb()
    {
        $this->breadcrumb->addElement(FL::lbl('MembersWelcome'));
    }

    /**
     *
     */
    protected function parse()
    {
        $this->tpl->assign('hideContentTitle', true);

        $this->tpl->assign(
            'text',
            $this->get('fork.settings')->get($this->getModule(), FRONTEND_LANGUAGE.'_welcome_text', '')
        );
    }
}
