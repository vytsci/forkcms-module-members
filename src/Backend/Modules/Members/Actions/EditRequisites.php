<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;

/**
 * Class EditRequisites
 * @package Backend\Modules\Members\Actions
 */
class EditRequisites extends BackendBaseActionEdit
{

    /**
     * @var \Common\Modules\Members\Entity\Requisites
     */
    protected $requisites;

    /**
     * @var \Common\Modules\Members\Entity\Member
     */
    protected $member;

    /**
     * @var \Common\Modules\Members\Entity\Address
     */
    protected $address;

    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');
        $this->member = new \Common\Modules\Members\Entity\Member(array($this->id));
        $this->requisites = new \Common\Modules\Members\Entity\Requisites(array($this->member->getId()));
        $this->address = CommonMembersModel::getMemberAddressBilling($this->member->getId(), BL::getWorkingLanguage());

        if (!$this->requisites->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Requisites').'&error=non-existing');
        }

        parent::execute();

        $this->parse();
        $this->display();
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('member', $this->member->isLoaded() ? $this->member->toArray() : array());
        $this->tpl->assign('requisites', $this->requisites->isLoaded() ? $this->requisites->toArray() : array());
        $this->tpl->assign('address', $this->address->isLoaded() ? $this->address->toArray() : array());
        $this->tpl->assign('history', BackendMembersModel::getMemberRequisitesHistory($this->id));
    }
}
