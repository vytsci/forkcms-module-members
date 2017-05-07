<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Model as BackendModel;
use Common\Modules\Members\Engine\Helper;
use Common\Modules\Members\Entity\Member;
use Common\Modules\Members\Entity\Address;

/**
 * Class AddAddress
 * @package Backend\Modules\Members\Actions
 */
class AddAddress extends BackendBaseActionAdd
{

    /**
     * @var Member
     */
    private $member;

    /**
     * @var Address
     */
    private $address;

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    public function execute()
    {
        $this->member = new Member(array($this->getParameter('member_id', 'int')));

        if (!$this->member->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }

        $this->address = new Address();

        parent::execute();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     *
     */
    private function loadForm()
    {
        $this->frm = new BackendForm('addAddress');
        Helper::parseFieldsAddress($this->frm, BL::getWorkingLanguage());
    }

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();

            Helper::validateAddress($this->frm, $this->address);

            if ($this->frm->isCorrect() && $this->address->isAffected()) {
                $this->address->setMemberId($this->member->getId());
                $this->address->save();

                if ($this->address->isPrimary()) {
                    /*BackendModel::triggerEvent(
                        $this->getModule(),
                        'after_set_address_primary',
                        array('item' => $address->toArray())
                    );*/
                    Helper::afterSetAddressPrimary($this->address->toArray());
                }

                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_add_address',
                    array('item' => $this->address->toArray())
                );

                $this->redirect(
                    BackendModel::createURLForAction('Edit')
                    .'&id='.$this->member->getId()
                    .'#tabAddresses'
                );
            }
        }
    }
}
