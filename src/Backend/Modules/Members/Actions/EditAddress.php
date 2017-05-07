<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionEdit;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Model as BackendModel;
use Common\Modules\Members\Engine\Helper;
use Common\Modules\Members\Entity\Address;

/**
 * Class EditAddress
 * @package Backend\Modules\Members\Actions
 */
class EditAddress extends BackendBaseActionEdit
{

    /**
     * @var Address
     */
    protected $address;

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    public function execute()
    {
        $this->address = new Address(array($this->getParameter('id', 'int')));

        if (!$this->address->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }

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
        $this->frm = new BackendForm('editAddress');
        Helper::parseFieldsAddress($this->frm, BL::getWorkingLanguage(), $this->address->isPrimary(), $this->address);
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
                    'after_edit_address',
                    array('item' => $this->address->toArray())
                );

                $this->redirect(
                    BackendModel::createURLForAction('Edit')
                    .'&id='.$this->address->getMemberId()
                    .'#tabAddresses'
                );
            }
        }
    }
}
