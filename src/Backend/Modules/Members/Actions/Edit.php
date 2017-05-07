<?php

namespace Backend\Modules\Members\Actions;

use Symfony\Component\Filesystem\Filesystem;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionEdit;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;
use Backend\Modules\Members\Engine\Helper as BackendMembersHelper;
use Common\Modules\Members\Engine\Helper;
use Common\Modules\Members\Entity\Member;

/**
 * Class Edit
 * @package Backend\Modules\Members\Actions
 */
class Edit extends BackendBaseActionEdit
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var Member
     */
    private $member;

    /**
     * @var array
     */
    private $profile;

    /**
     *
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');
        $this->member = new Member(array($this->id));

        if (!$this->member->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }

        parent::execute();

        $this->loadData();
        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     *
     */
    private function loadData()
    {
        $this->profile = BackendProfilesModel::get($this->id);
    }

    /**
     * @throws \Backend\Core\Engine\Exception
     */
    private function loadForm()
    {
        $this->frm = new BackendForm('edit');

        $this->frm->addText('email', $this->profile['email']);
        $this->frm->addText('display_name', $this->profile['display_name']);
        $this->frm->addPassword('password');

        $this->frm->addDropdown(
            'type',
            BackendMembersModel::getMemberTypesForDropdown(),
            $this->member->getType()->getValue()
        );
        $this->frm->addText('first_name', $this->member->getFirstName());
        $this->frm->addText('last_name', $this->member->getLastName());
        $this->frm->addText('phone', $this->member->getPhone());
        $this->frm->addImage('avatar');
        $this->frm->addCheckbox('avatar_delete');
        $this->frm->addDropdown(
            'gender',
            array(
                'male' => \SpoonFilter::ucfirst(BL::getLabel('Male')),
                'female' => \SpoonFilter::ucfirst(BL::getLabel('Female')),
            ),
            $this->member->getGender()
        );

        BackendMembersHelper::parseFieldsDateBirth($this->frm, $this->member);

        $this->frm->addText('source', $this->member->getSource());

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
            $this->validateFields();

            if ($this->frm->isCorrect()) {
                $this->get('database')->getHandler()->beginTransaction();

                try {
                    $this->validateProfile();

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

                    $fs->mkdir(Helper::getAvatarsPaths());

                    if ($this->frm->getField('avatar')->isFilled()) {
                        $avatar = uniqid().'.'.$this->frm->getField('avatar')->getExtension();
                        $this->frm->getField('avatar')->generateThumbnails($avatarsPath, $avatar);
                    }

                    $this->member
                        ->setType($this->frm->getField('type')->getValue())
                        ->setFirstName($this->frm->getField('first_name')->getValue())
                        ->setLastName($this->frm->getField('last_name')->getValue())
                        ->setPhone($this->frm->getField('phone')->getValue())
                        ->setAvatar($avatar)
                        ->setGender($this->frm->getField('gender')->getValue())
                        ->setDateBirth(BackendMembersHelper::getDateBirth($this->frm))
                        ->setSource($this->frm->getField('source')->getValue())
                        ->save();
                } catch (\Exception $e) {
                    $this->get('database')->getHandler()->rollBack();
                    $this->frm->addError(BL::err('SomethingWentWrong'));

                    return;
                }

                $this->get('database')->getHandler()->commit();

                BackendModel::triggerEvent($this->getModule(), 'after_edit', array('item' => $this->member->toArray()));

                $text = $this->profile['email'].'<br />'
                    .$this->member->getFirstName().'<br />'
                    .$this->member->getLastName().'<br />';

                BackendSearchModel::saveIndex(
                    $this->getModule(),
                    $this->member->getId(),
                    array('title' => $this->profile['display_name'], 'text' => $text)
                );

                $this->redirect(
                    BackendModel::createURLForAction('Index')
                    .'&report=added&var='.urlencode($this->profile['display_name'])
                    .'&highlight=row-'.$this->member->getId()
                );
            }
        }
    }

    /**
     * @throws \SpoonFormException
     */
    private function validateFields()
    {
        $fieldEmail = $this->frm->getField('email');
        if ($fieldEmail->isFilled(BL::getError('FieldIsRequired'))) {
            if ($fieldEmail->isEmail(BL::getError('EmailIsInvalid'))) {
                if (BackendProfilesModel::existsByEmail($fieldEmail->getValue(), $this->profile['id'])) {
                    $fieldEmail->addError(BL::getError('EmailExists'));
                }
            }
        }
        $this->frm->getField('first_name')->isFilled(BL::err('FieldIsRequired'));
        $this->frm->getField('last_name')->isFilled(BL::err('FieldIsRequired'));

        BackendMembersHelper::validateFieldsDateBirth($this->frm, false);
    }

    /**
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

            $this->profile['email'] = $this->frm->getField('email')->getValue();
            $this->profile['display_name'] = $displayName;
            $this->profile['url'] = BackendProfilesModel::getUrl($displayName, $this->id);

            if ($this->frm->getField('password')->isFilled()) {
                $salt = BackendProfilesModel::getRandomString();
                $this->profile['password'] = BackendProfilesModel::getEncryptedString(
                    $this->frm->getField('password')->getValue(),
                    $salt
                );
                BackendProfilesModel::setSetting($this->profile['id'], 'salt', $salt);
            }

            BackendProfilesModel::update($this->profile['id'], $this->profile);
        }
    }

    /**
     *
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('profile', $this->profile);
        $this->tpl->assign('member', $this->member->toArray());
    }
}
