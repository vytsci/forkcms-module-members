<?php

namespace Frontend\Modules\Members\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;

use Frontend\Modules\Members\Engine\Model as FrontendMembersModel;

use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Members\Entity\Member;

/**
 * Class Detail
 * @package Frontend\Modules\Members\Actions
 */
class Detail extends FrontendBaseBlock
{

    /**
     * @var Member
     */
    private $member;

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
     *
     */
    public function loadRecord()
    {
        $url = $this->URL->getParameter(0);

        $this->member = FrontendMembersModel::getMemberByUrl($url);
    }

    /**
     * @return bool
     */
    public function hasRecord()
    {
        $this->loadRecord();

        if ($this->member->isLoaded()) {
            return true;
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    private function overrideHeader()
    {
        $this->header->addMetaKeywords($this->member->getDisplayName(), true);
        $this->header->addMetaDescription($this->member->getDisplayName(), true);
        $this->header->setPageTitle($this->member->getDisplayName());
    }

    /**
     * @throws \Exception
     */
    private function fillBreadcrumb()
    {
        $this->breadcrumb->addElement($this->member->getDisplayName());
    }

    /**
     * @throws \SpoonTemplateException
     */
    private function parse()
    {
        $this->tpl->assign('hideContentTitle', true);
        $this->tpl->assign(
            'hasApprovedRequisites',
            CommonMembersModel::hasApprovedRequisites($this->member->getId())
        );

        $this->tpl->assign('member', $this->member->toArray());
    }
}
