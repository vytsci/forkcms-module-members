<?php

namespace Frontend\Modules\Members\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Form as FrontendForm;
use Frontend\Core\Engine\Language as FL;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Members\Engine\Authentication as FrontendMembersAuthentication;
use Frontend\Modules\Members\Engine\Helper as FrontendMembersHelper;

use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Frontend\Modules\Profiles\Engine\Model as FrontendProfilesModel;
use Frontend\Modules\Members\Engine\Model as FrontendMembersModel;
use Frontend\Modules\Members\Engine\MemberType;

use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Members\Engine\Helper as CommonMembersHelper;
use Common\Modules\Members\Entity\Member;
use Common\Modules\Members\Entity\Pending;

class Registration extends FrontendBaseBlock
{

    /**
     * @var Member
     */
    private $member;

    /**
     * @var Pending
     */
    private $pending;

    /**
     * @var MemberType
     */
    private $type;

    /**
     * @var array
     */
    private $types = array();

    /**
     * @var bool
     */
    private $isEmailRegistration = false;

    /**
     * @var bool
     */
    private $showTypeChoice = false;

    /**
     * @var
     */
    private $defaultType;

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
        if (FrontendMembersAuthentication::isLoggedIn()) {
            $this->redirect(FrontendNavigation::getURLForBlock('Members', 'Account'), 307);
        }

        $this->member = new Member();
        $this->type = new MemberType();
        $this->isEmailRegistration = (bool)$this->get('fork.settings')->get('Members', 'enable_email_registration', 0);
        $this->showTypeChoice = (bool)$this->get('fork.settings')->get('Members', 'show_type_choice', 0);
        $this->defaultType = $this->get('fork.settings')->get('Members', 'default_type');

        parent::execute();

        $this->loadTemplate();

        $this->loadPending();
        $this->loadTypes();
        $this->loadType();

        if ($this->type->isLoaded()) {
            $this->header->setPageTitle($this->type->getLabel());
            $this->fillBreadcrumb();
        }

        $this->loadForm();
        $this->validateForm();

        $this->parse();
    }

    /**
     *
     */
    private function loadPending()
    {
        $id = $this->URL->getParameter('pending');
        $token = $this->URL->getParameter('token');

        $this->pending = new Pending(array($id, $token));

        if ($this->pending->isLoaded()) {
            $this->isEmailRegistration = false;

            $this->type = new MemberType($this->pending->getType());
            $this->email = $this->pending->getEmail();
        }
    }

    /**
     *
     */
    private function loadTypes()
    {
        $this->types = FrontendMembersModel::getMemberTypesForRegistration();
    }

    /**
     *
     */
    private function loadType()
    {
        $url = $this->URL->getParameter(0);

        foreach ($this->types as $type) {
            if ($type['action'] == $url) {
                $this->type->load($type['value']);

                return;
            }
        }
    }

    /**
     * @return mixed
     * @throws \SpoonFormException
     */
    private function getEmail()
    {
        if ($this->pending->isLoaded() && !$this->isEmailRegistration) {
            return $this->pending->getEmail();
        }

        if ($this->frm->existsField('email') && $this->frm->getField('email')->isFilled()) {
            return $this->frm->getField('email')->getValue();
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    private function fillBreadcrumb()
    {
        $this->breadcrumb->addElement($this->type->getLabel());
    }

    /**
     * @throws \Frontend\Core\Engine\Exception
     */
    private function loadForm()
    {
        $this->frm = new FrontendForm('registration');

        if (!$this->type->isLoaded() && !empty($this->types)) {
            $rbtTypesValues = array();
            foreach ($this->types as $type) {
                $rbtTypesValues[] = array(
                    'label' => $type['label'],
                    'value' => $type['value'],
                );
            }

            $this->frm->addRadiobutton(
                'type',
                $rbtTypesValues,
                $this->get('fork.settings')->get('Members', 'default_type')
            );
        }

        $this->frm->addText('email', $this->getEmail());

        if ($this->pending->isLoaded()) {
            $this->frm->getField('email')->setAttribute('readonly', true);
        }

        if ($this->isEmailRegistration === false) {
            $this->frm->addText('display_name');
            $this->frm->addPassword('password');
            $this->frm->addPassword('password_confirm');

            $this->frm->addText('first_name');
            $this->frm->addText('last_name');
            $this->frm->addDropdown(
                'gender',
                array(
                    'male' => \SpoonFilter::ucfirst(FL::lbl('Male')),
                    'female' => \SpoonFilter::ucfirst(FL::lbl('Female')),
                )
            );

            FrontendMembersHelper::parseFieldsDateBirth($this->frm, $this->member);

            $sources = FrontendMembersHelper::getSourcesForDropdown();
            if (!empty($sources)) {
                $this->frm->addDropdown('source', $sources);
            }
        }

        $this->frm->addCheckbox('terms', $this->pending->isLoaded());
    }

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();
            $this->validateFields();

            if ($this->isEmailRegistration) {
                $this->validatePending();

                if ($this->frm->isCorrect()) {
                    $this->redirect(FrontendNavigation::getURLForBlock('Members', 'Pending'));
                }

                return;
            }

            if ($this->frm->isCorrect()) {
                $this->get('database')->getHandler()->beginTransaction();

                try {
                    $profile = $this->validateProfile();

                    $this->member
                        ->setId($profile['id'])
                        ->setFirstName($this->frm->getField('first_name')->getValue())
                        ->setLastName($this->frm->getField('last_name')->getValue())
                        ->setDateBirth(FrontendMembersHelper::getDateBirth($this->frm));

                    if ($this->frm->existsField('source')) {
                        $this->member->setSource($this->frm->getField('source')->getValue());
                    }

                    if ($this->frm->getField('gender')->isFilled()) {
                        $this->member->setGender($this->frm->getField('gender')->getValue());
                    }

                    $this->setType();

                    $this->member->save();

                    $this->setTypeGroup();
                } catch (\Exception $e) {
                    $this->get('database')->getHandler()->rollBack();
                    $this->frm->addError(FL::err('SomethingWentWrong'));

                    return;
                }

                $this->get('database')->getHandler()->commit();

                FrontendModel::triggerEvent(
                    $this->getModule(),
                    'after_completed_registration',
                    array('item' => $this->member->toArray())
                );

                $text = $profile['email'].'<br />'
                    .$this->member->getFirstName().'<br />'
                    .$this->member->getLastName().'<br />';

                BackendSearchModel::saveIndex(
                    $this->getModule(),
                    $this->member->getId(),
                    array('title' => $profile['display_name'], 'text' => $text)
                );

                $this->redirect(FrontendNavigation::getURLForBlock('Members', 'Welcome'));
            }
        }
    }

    /**
     * @throws \SpoonFormException
     */
    private function validateFields()
    {
        $fieldEmail = $this->frm->getField('email');
        if ($fieldEmail->isFilled(FL::getError('FieldIsRequired'))) {
            if ($fieldEmail->isEmail(FL::getError('EmailIsInvalid'))) {
                if (FrontendProfilesModel::existsByEmail($fieldEmail->getValue())) {
                    $fieldEmail->addError(FL::getError('EmailExists'));
                }
            }
        }

        if ($this->isEmailRegistration === false) {
            $this->frm->getField('first_name')->isFilled(FL::err('FieldIsRequired'));
            $this->frm->getField('last_name')->isFilled(FL::err('FieldIsRequired'));

            $txtPassword = $this->frm->getField('password');
            $txtPasswordConfirm = $this->frm->getField('password_confirm');

            if ($txtPassword->isFilled(FL::err('FieldIsRequired'))) {
                if (strcmp($txtPassword->getValue(), $txtPasswordConfirm->getValue()) !== 0) {
                    $txtPassword->addError(FL::err('PasswordsDoesNotMatch'));
                }
            }

            if ($this->type->isJuridical()) {
                $this->frm->getField('company')->isFilled(FL::err('FieldIsRequired'));
                $this->frm->getField('company_code')->isFilled(FL::err('FieldIsRequired'));
            }

            FrontendMembersHelper::validateFieldsDateBirth($this->frm, false);
        }

        $this->frm->getField('terms')->isChecked(FL::err('TermsIsRequired'));
    }

    /**
     * @throws \SpoonFormException
     */
    private function validatePending()
    {
        if ($this->frm->isCorrect()) {
            $this->pending = new Pending();
            $this->pending
                ->setEmail($this->getEmail())
                ->setType($this->type->getValue())
                ->setToken(md5(uniqid()))
                ->save();

            FrontendModel::triggerEvent(
                'Members',
                'after_email_registration',
                array('item' => $this->pending->toArray())
            );

            CommonMembersHelper::sendEmailRegistration(
                $this->pending,
                SITE_URL
                .FrontendNavigation::getURLForBlock('Members', 'Registration')
                .'?pending='.$this->pending->getId().'&token='.$this->pending->getToken(),
                FL::getMessage('EmailRegistrationSubject')
            );
        }
    }

    /**
     * @return array|null
     * @throws \SpoonFormException
     */
    private function validateProfile()
    {
        if ($this->frm->isCorrect()) {
            $isEmailRegistration = (bool)$this->get('fork.settings')->get('Members', 'enable_email_registration', 0);
            $displayName = $this->frm->getField('display_name')->getValue();

            if (empty($displayName)) {
                $firstName = $this->frm->getField('first_name')->getValue();
                $lastName = $this->frm->getField('last_name')->getValue();

                $displayName = $firstName.' '.$lastName;
            }

            $settings = array(
                'salt' => FrontendProfilesModel::getRandomString(),
            );

            $profile = array(
                'email' => $this->getEmail(),
                'registered_on' => FrontendModel::getUTCDate(),
                'display_name' => $displayName,
                'url' => FrontendProfilesModel::getUrl($displayName),
                'password' => FrontendProfilesModel::getEncryptedString(
                    $this->frm->getField('password')->getValue(),
                    $settings['salt']
                ),
                'status' => $isEmailRegistration?'active':'inactive',
                'last_login' => FrontendModel::getUTCDate(null, 0),
            );

            $id = FrontendProfilesModel::insert($profile);

            if ($id === 0) {
                $this->frm->addError(FL::err('ProfileInvalid'));
            }

            FrontendModel::triggerEvent('Profiles', 'after_register', array('id' => $id));

            $profile['id'] = $id;

            if (!$isEmailRegistration) {
                $settings['activation_key'] = FrontendProfilesModel::getEncryptedString(
                    $id.microtime(),
                    $settings['salt']
                );

                CommonMembersHelper::sendActivation(
                    $profile,
                    SITE_URL
                    .FrontendNavigation::getURLForBlock('Profiles', 'Activate')
                    .'/'.$settings['activation_key'],
                    FL::getMessage('RegisterSubject')
                );
            }

            FrontendProfilesModel::setSettings($id, $settings);
            FrontendMembersAuthentication::login($id);

            return $profile;
        }

        return null;
    }

    /**
     * @throws \SpoonFormException
     */
    private function setType()
    {
        $type = $this->defaultType;

        if ($this->type->isLoaded()) {
            $type = $this->type->getValue();
        }

        if ($this->frm->existsField('type')) {
            $type = $this->frm->getField('type')->getValue();
        }

        if (isset($type)) {
            $this->member->setType($type);
        }
    }

    /**
     *
     */
    private function setTypeGroup()
    {
        $type = $this->member->getType()->getValue();

        if (isset($type)) {
            $typeGroup = $this->get('fork.settings')->get('Members', 'default_group_'.$type, -1);

            if ($typeGroup > 0) {
                CommonMembersModel::setGroupsForMember($this->member->getId(), array($typeGroup));
            }
        }
    }

    /**
     * @return bool
     */
    private function showTypeChoicePage()
    {
        if ($this->type->isLoaded()) {
            return false;
        }

        if (empty($this->types)) {
            return false;
        };

        if (!$this->get('fork.settings')->get('Members', 'show_type_choice_as_page', 0)) {
            return false;
        }

        return true;
    }

    /**
     * @throws \SpoonTemplateException
     */
    protected function parse()
    {
        $this->frm->parse($this->tpl);

        if ($this->type->isLoaded()) {
            $this->tpl->assign('type', $this->type);
        }

        $this->tpl->assign('hideContentTitle', true);
        $this->tpl->assign('showTypeChoicePage', $this->showTypeChoicePage());
        $this->tpl->assign('showTypeChoice', $this->showTypeChoice);
        $this->tpl->assign('types', $this->types);
        $this->tpl->assign('isEmailRegistration', $this->isEmailRegistration);
        $this->tpl->assign('isTypeLoaded', $this->type->isLoaded());
        $this->tpl->assign('isTypeNatural', $this->type->isNatural());
        $this->tpl->assign('isTypeJuridical', $this->type->isJuridical());
        $this->tpl->assign(
            'urlTerms',
            $this->get('fork.settings')->get('Members', FRONTEND_LANGUAGE.'_url_terms', '')
        );
    }
}
