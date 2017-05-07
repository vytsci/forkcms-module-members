<?php

namespace Frontend\Modules\Members\Actions;

use Symfony\Component\Filesystem\Filesystem;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Form as FrontendForm;
use Frontend\Core\Engine\Language as FL;
use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Members\Engine\Authentication as FrontendMembersAuthentication;
use Frontend\Modules\Members\Engine\Helper as FrontendMembersHelper;

use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Frontend\Modules\Profiles\Engine\Model as FrontendProfilesModel;

use Common\Modules\Members\Engine\Helper as CommonMembersHelper;
use Frontend\Modules\Profiles\Engine\Profile;
use Common\Modules\Members\Entity\Member;

/**
 * Class Account
 * @package Frontend\Modules\Members\Actions
 */
class Account extends FrontendBaseBlock
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
        $this->member->loadAddresses(FRONTEND_LANGUAGE);

        parent::execute();

        $this->loadAssets();
        $this->loadTemplate();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
    }

    /**
     *
     */
    private function loadAssets()
    {
        $this->addJS('/bower_components/ckeditor/ckeditor.js', true, false);
    }

    /**
     * @throws \Frontend\Core\Engine\Exception
     */
    private function loadForm()
    {
        $this->frm = new FrontendForm('account');

        $this->frm->addText('email', $this->profile->getEmail());
        $this->frm->addText('display_name', $this->profile->getDisplayName());

        $this->frm->addText('first_name', $this->member->getFirstName());
        $this->frm->addText('last_name', $this->member->getLastName());
        $this->frm->addTextarea('introduction', $this->member->getIntroduction(), 'inputEditor', null, true);
        $this->frm->addText('phone', $this->member->getPhone());
        $this->frm->addImage('avatar');
        $this->frm->addCheckbox('avatar_delete');
        $this->frm->addDropdown(
            'gender',
            array(
                'male' => \SpoonFilter::ucfirst(FL::lbl('Male')),
                'female' => \SpoonFilter::ucfirst(FL::lbl('Female')),
            ),
            $this->member->getGender()
        );

        FrontendMembersHelper::parseFieldsDateBirth($this->frm, $this->member);

        $this->frm->addDropdown('source', FrontendMembersHelper::getSourcesForDropdown());
    }

    /**
     *
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();
            $this->validateFields();

            if ($this->frm->isCorrect()) {
                $this->get('database')->getHandler()->beginTransaction();

                try {
                    $profile = $this->validateProfile();

                    $avatar = $this->member->getAvatar();
                    $avatarsPath = FRONTEND_FILES_PATH.'/Members/avatars';

                    $fs = new Filesystem();

                    $needDeleteAvatar =
                        $this->frm->getField('avatar_delete')->isChecked()
                        || $this->frm->getField('avatar')->isFilled();

                    if (!empty($avatar) && $needDeleteAvatar) {
                        $avatarThumbnailsPaths = FrontendModel::getThumbnailFolders($avatarsPath);
                        $fs->remove($avatarsPath.'/source/'.$avatar);
                        foreach ($avatarThumbnailsPaths as $avatarThumbnailsPath) {
                            $avatarThumbnailPath = $avatarThumbnailsPath['path'].'/'.$avatar;
                            $fs->remove($avatarThumbnailPath);
                        }
                        $avatar = '';
                    }

                    $fs->mkdir(CommonMembersHelper::getAvatarsPaths());

                    if ($this->frm->getField('avatar')->isFilled()) {
                        $avatar = uniqid().'.'.$this->frm->getField('avatar')->getExtension();
                        $this->frm->getField('avatar')->generateThumbnails($avatarsPath, $avatar);
                    }

                    $this->member
                        ->setId($this->profile->getId())
                        ->setFirstName($this->frm->getField('first_name')->getValue())
                        ->setLastName($this->frm->getField('last_name')->getValue())
                        ->setIntroduction($this->frm->getField('introduction')->getValue())
                        ->setPhone($this->frm->getField('phone')->getValue())
                        ->setAvatar($avatar)
                        ->setGender($this->frm->getField('gender')->getValue())
                        ->setDateBirth(FrontendMembersHelper::getDateBirth($this->frm))
                        ->setSource($this->frm->getField('source')->getValue())
                        ->save();
                } catch (\Exception $e) {
                    $this->get('database')->getHandler()->rollBack();
                    $this->frm->addError(FL::err('SomethingWentWrong'));

                    return;
                }

                $this->get('database')->getHandler()->commit();

                FrontendModel::triggerEvent(
                    $this->getModule(),
                    'after_saved_account',
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

                $this->redirect(FrontendNavigation::getURLForBlock('Members', 'Account'));
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
                if (FrontendProfilesModel::existsByEmail($fieldEmail->getValue(), $this->profile->getId())) {
                    $fieldEmail->addError(FL::getError('EmailExists'));
                }
            }
        }

        $this->frm->getField('first_name')->isFilled(FL::err('FieldIsRequired'));
        $this->frm->getField('last_name')->isFilled(FL::err('FieldIsRequired'));

        FrontendMembersHelper::validateFieldsDateBirth($this->frm);
    }

    /**
     * @return array|null
     * @throws \SpoonFormException
     */
    private function validateProfile()
    {
        if ($this->frm->isCorrect()) {
            $displayName = $this->frm->getField('display_name')->getValue();

            if (empty($displayName)) {
                $displayName =
                    $this->frm->getField('first_name')->getValue()
                    .' '
                    .$this->frm->getField('last_name')->getValue();
            }

            $this->profile->setEmail($this->frm->getField('email')->getValue());
            $this->profile->setDisplayName($displayName);
            $this->profile->setUrl(FrontendProfilesModel::getUrl($displayName, $this->profile->getId()));

            $profile = array(
                'email' => $this->profile->getEmail(),
                'display_name' => $this->profile->getDisplayName(),
                'url' => $this->profile->getUrl(),
            );

            FrontendProfilesModel::update($this->profile->getId(), $profile);

            FrontendModel::triggerEvent('Profiles', 'after_saved_settings', array('id' => $this->profile->getId()));

            return $profile;
        }

        return null;
    }

    /**
     * @throws \SpoonTemplateException
     */
    protected function parse()
    {
        $this->frm->parse($this->tpl);

        $this->tpl->assign('member', $this->member->toArray());
    }
}
