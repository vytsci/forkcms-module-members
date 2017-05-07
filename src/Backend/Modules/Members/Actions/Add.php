<?php

namespace Backend\Modules\Members\Actions;

use Symfony\Component\Filesystem\Filesystem;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Search\Engine\Model as BackendSearchModel;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;
use Backend\Modules\Members\Engine\Helper as BackendMembersHelper;
use Common\Modules\Members\Engine\Helper;
use Common\Modules\Members\Entity\Member;
use Common\Modules\Members\Entity\Address;

/**
 * Class Add
 * @package Backend\Modules\Members\Actions
 */
class Add extends BackendBaseActionAdd
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
        $this->member = new Member();

        parent::execute();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * @throws \Backend\Core\Engine\Exception
     */
    private function loadForm()
    {
        $this->frm = new BackendForm('add');

        $this->frm->addText('email');
        $this->frm->addText('display_name');
        $this->frm->addPassword('password');

        $this->frm->addDropdown('type', BackendMembersModel::getMemberTypesForDropdown());
        $this->frm->addText('first_name');
        $this->frm->addText('last_name');
        $this->frm->addText('phone');
        $this->frm->addImage('avatar');
        $this->frm->addDropdown(
            'gender',
            array(
                'male' => \SpoonFilter::ucfirst(BL::getLabel('Male')),
                'female' => \SpoonFilter::ucfirst(BL::getLabel('Female')),
            )
        );

        BackendMembersHelper::parseFieldsDateBirth($this->frm);

        $this->frm->addText('source');

        Helper::parseFieldsAddress($this->frm, BL::getWorkingLanguage(), true);
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
                    $address = new Address();

                    if ($this->frm->getField('add_address')->isChecked()) {
                        Helper::validateAddress($this->frm, $address);
                    }

                    $avatar = $this->member->getAvatar();
                    $avatarsPath = FRONTEND_FILES_PATH.'/Members/avatars';

                    $fs = new Filesystem();

                    $fs->mkdir(Helper::getAvatarsPaths());

                    if ($this->frm->getField('avatar')->isFilled()) {
                        $avatar = uniqid().'.'.$this->frm->getField('avatar')->getExtension();
                        $this->frm->getField('avatar')->generateThumbnails($avatarsPath, $avatar);
                    }

                    $this->member = new Member();
                    $this->member
                        ->setId($profile['id'])
                        ->setType($this->frm->getField('type')->getValue())
                        ->setFirstName($this->frm->getField('first_name')->getValue())
                        ->setLastName($this->frm->getField('last_name')->getValue())
                        ->setPhone($this->frm->getField('phone')->getValue())
                        ->setAvatar($avatar)
                        ->setGender($this->frm->getField('gender')->getValue())
                        ->setDateBirth(BackendMembersHelper::getDateBirth($this->frm))
                        ->setSource($this->frm->getField('source')->getValue())
                        ->save();

                    if ($address->isAffected()) {
                        $address
                            ->setMemberId($this->member->getId())
                            ->save();
                    }
                } catch (\Exception $e) {
                    $this->get('database')->getHandler()->rollBack();
                    $this->frm->addError(BL::err('SomethingWentWrong'));

                    return;
                }

                $this->get('database')->getHandler()->commit();

                BackendModel::triggerEvent($this->getModule(), 'after_add', array('item' => $this->member->toArray()));

                if ($address->isPrimary()) {
                    /*BackendModel::triggerEvent(
                        $this->getModule(),
                        'after_set_primary_address',
                        array('item' => $address->toArray())
                    );*/
                    Helper::afterSetAddressPrimary($address->toArray());
                }

                $text = $profile['email'].'<br />'
                    .$this->member->getFirstName().'<br />'
                    .$this->member->getLastName().'<br />';

                BackendSearchModel::saveIndex(
                    $this->getModule(),
                    $this->member->getId(),
                    array('title' => $profile['display_name'], 'text' => $text)
                );

                $this->redirect(
                    BackendModel::createURLForAction('Index')
                    .'&report=added&var='.urlencode($profile['display_name'])
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
                if (BackendProfilesModel::existsByEmail($fieldEmail->getValue())) {
                    $fieldEmail->addError(BL::getError('EmailExists'));
                }
            }
        }
        $this->frm->getField('password')->isFilled(BL::err('FieldIsRequired'));
        $this->frm->getField('first_name')->isFilled(BL::err('FieldIsRequired'));
        $this->frm->getField('last_name')->isFilled(BL::err('FieldIsRequired'));

        BackendMembersHelper::validateFieldsDateBirth($this->frm, false);
    }

    /**
     * @return array|null
     * @throws \Exception
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

            $salt = BackendProfilesModel::getRandomString();

            $profile = array(
                'email' => $this->frm->getField('email')->getValue(),
                'registered_on' => BackendModel::getUTCDate(),
                'display_name' => $displayName,
                'url' => BackendProfilesModel::getUrl($displayName),
                'password' => BackendProfilesModel::getEncryptedString(
                    $this->frm->getField('password')->getValue(),
                    $salt
                ),
                'last_login' => BackendModel::getUTCDate(null, 0),
            );

            $id = BackendProfilesModel::insert($profile);

            if ($id === 0) {
                $this->frm->addError(BL::err('ProfileInvalid'));
                throw new \Exception(BL::err('ProfileInvalid'));
            }

            BackendProfilesModel::setSetting($id, 'salt', $salt);
            BackendProfilesModel::update($id, $profile);
            $profile['id'] = $id;

            return $profile;
        }

        return null;
    }

    /**
     *
     */
    protected function parse()
    {
        parent::parse();
    }
}
