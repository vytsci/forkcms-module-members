<?php

namespace Frontend\Modules\Members\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Form as FrontendForm;
use Frontend\Core\Engine\Language as FL;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Members\Engine\Authentication as FrontendMembersAuthentication;
use Frontend\Modules\Members\Engine\Model as FrontendMembersModel;
use Frontend\Modules\Members\Engine\Helper as FrontendMembersHelper;

use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Members\Engine\Helper as CommonMembersHelper;
use Frontend\Modules\Profiles\Engine\Profile;
use Common\Modules\Members\Entity\Member;

/**
 * Class Requisites
 * @package Frontend\Modules\Members\Actions
 */
class Requisites extends FrontendBaseBlock
{

    /**
     * @var Profile
     */
    private $profile;

    /**
     * @var Member
     */
    private $member;

    /**
     * @var \Common\Modules\Members\Entity\Requisites
     */
    private $requisites;

    /**
     * @var \Common\Modules\Members\Entity\Address
     */
    private $address;

    /**
     * @var bool
     */
    private $hasPending = false;

    /**
     * The form instance
     *
     * @var FrontendForm
     */
    protected $frm;

    /**
     * @throws \Common\Exception\RedirectException
     */
    public function execute()
    {
        if (!FrontendMembersAuthentication::isLoggedIn()) {
            $this->redirect(
                FrontendNavigation::getURLForBlock('Profiles', 'Login')
                .CommonMembersHelper::getLoginQueryString(),
                307
            );
        }

        $this->profile = FrontendMembersAuthentication::getProfile();
        $this->member = new Member($this->profile->getId());
        $this->requisites = new \Common\Modules\Members\Entity\Requisites($this->member->getId());
        $this->address = CommonMembersModel::getMemberAddressBilling($this->member->getId(), FRONTEND_LANGUAGE);
        $this->hasPending = CommonMembersModel::hasPendingRequisites($this->member->getId());

        parent::execute();

        $this->loadTemplate();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
    }

    /**
     * @throws \Frontend\Core\Engine\Exception
     */
    private function loadForm()
    {
        $this->frm = new FrontendForm('requisites');

        $this->frm->addDropdown(
            'type',
            FrontendMembersModel::getMemberTypesForDropdown(),
            $this->requisites->getType()->getValue()
        );
        $this->frm->addText(
            'business_entity_type',
            $this->requisites->getBusinessEntityType(),
            8
        );
        $this->frm->addText('company', $this->requisites->getCompany());
        $this->frm->addText('company_code', $this->requisites->getCompanyCode());
        $this->frm->addText('vat_identifier', $this->requisites->getVatIdentifier());
        $this->frm->addText('bank', $this->requisites->getBank());
        $this->frm->addText('bank_account', $this->requisites->getBankAccount());
        $this->frm->addText('bank_swift', $this->requisites->getBankSwift());

        $this->frm->addCheckbox('terms');

        CommonMembersHelper::parseFieldsAddress(
            $this->frm,
            FRONTEND_LANGUAGE,
            true,
            $this->address,
            $this->hasPending
        );
        FrontendMembersHelper::loadAssetsAddress($this->header);
    }

    /**
     *
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();
            $this->validateFields();
            CommonMembersHelper::validateAddress($this->frm, $this->address);

            if ($this->frm->isCorrect()) {
                $this->get('database')->getHandler()->beginTransaction();

                try {
                    $requisites = new \Common\Modules\Members\Entity\Requisites();

                    $requisites
                        ->setMemberId($this->member->getId())
                        ->setType($this->frm->getField('type')->getValue())
                        ->setBusinessEntityType($this->frm->getField('business_entity_type')->getValue(true))
                        ->setCompany($this->frm->getField('company')->getValue(true))
                        ->setCompanyCode($this->frm->getField('company_code')->getValue(true))
                        ->setVatIdentifier($this->frm->getField('vat_identifier')->getValue(true))
                        ->setBank($this->frm->getField('bank')->getValue(true))
                        ->setBankAccount($this->frm->getField('bank_account')->getValue(true))
                        ->setBankSwift($this->frm->getField('bank_swift')->getValue(true));

                    if ($this->get('fork.settings')->get('Members', 'enable_auto_approve_requisites', false)) {
                        $requisites->setStatus('approved');
                    }

                    $requisites->save();

                    $this->address
                        ->setMemberId($this->member->getId())
                        ->setBilling()
                        ->save();
                } catch (\Exception $e) {
                    $this->get('database')->getHandler()->rollBack();
                    $this->frm->addError(FL::err('SomethingWentWrong'));

                    return;
                }

                $this->get('database')->getHandler()->commit();

                FrontendModel::triggerEvent(
                    $this->getModule(),
                    'after_saved_requisites',
                    array('item' => $this->member->toArray())
                );

                $this->redirect(FrontendNavigation::getURLForBlock('Members', 'Requisites'));
            }
        }

        if ($this->requisites->isLoaded() && !$this->frm->isSubmitted()) {
            $this->frm->getField('type')->setAttribute('readonly');
            $this->frm->getField('business_entity_type')->setAttribute('readonly');
            $this->frm->getField('company')->setAttribute('readonly');
            $this->frm->getField('company_code')->setAttribute('readonly');
            $this->frm->getField('vat_identifier')->setAttribute('readonly');
            $this->frm->getField('bank')->setAttribute('readonly');
            $this->frm->getField('bank_account')->setAttribute('readonly');
            $this->frm->getField('bank_swift')->setAttribute('readonly');

            $this->frm->getField('address_country')->setAttribute('readonly');
            $this->frm->getField('address_state')->setAttribute('readonly');
            $this->frm->getField('address_city')->setAttribute('readonly');
            $this->frm->getField('address_postal_code')->setAttribute('readonly');
            $this->frm->getField('address_address')->setAttribute('readonly');
            $this->frm->getField('address_phone')->setAttribute('readonly');
        }
    }

    /**
     * @throws \SpoonFormException
     */
    private function validateFields()
    {
        $this->frm->getField('terms')->isChecked(FL::err('TermsIsRequired'));
        $this->frm->getField('business_entity_type')->isFilled(FL::err('FieldIsRequired'));
        $this->frm->getField('company')->isFilled(FL::err('FieldIsRequired'));
        $this->frm->getField('company_code')->isFilled(FL::err('FieldIsRequired'));

        if ($this->frm->getField('address_country')->getValue() == '') {
            $this->frm->getField('address_country')->addError(FL::err('FieldIsRequired'));
        }
        if ($this->frm->getField('address_state')->getValue() == '') {
            $this->frm->getField('address_state')->addError(FL::err('FieldIsRequired'));
        }
        if ($this->frm->getField('address_city')->getValue() == '') {
            $this->frm->getField('address_city')->addError(FL::err('FieldIsRequired'));
        }

        $this->frm->getField('address_postal_code')->isFilled(FL::err('FieldIsRequired'));
        $this->frm->getField('address_address')->isFilled(FL::err('FieldIsRequired'));
        $this->frm->getField('address_phone')->isFilled(FL::err('FieldIsRequired'));

        $this->frm->getField('bank')->isFilled(FL::err('FieldIsRequired'));
        $this->frm->getField('bank_account')->isFilled(FL::err('FieldIsRequired'));
    }

    /**
     * @throws \SpoonTemplateException
     */
    protected function parse()
    {
        $this->tpl->assign('hideContentTitle', true);

        $this->frm->parse($this->tpl);

        $this->tpl->assign('showEdit', $this->requisites->isLoaded() && !$this->frm->isSubmitted());
        $this->tpl->assign('formErrors', $this->frm->getErrors() ? $this->frm->getErrors() : false);
        $this->tpl->assign(
            'termsRequisites',
            $this->get('fork.settings')->get('Members', FRONTEND_LANGUAGE.'_terms_requisites', '')
        );

        $this->tpl->assign('member', $this->member->toArray());
        $this->tpl->assign('requisites', $this->requisites->toArray());
    }
}
