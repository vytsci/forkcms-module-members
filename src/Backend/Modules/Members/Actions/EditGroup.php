<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

use Backend\Modules\Localization\Engine\Form as BackendLocalizationForm;
use Backend\Modules\Localization\Engine\Locale as BackendLocalizationLocale;

use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;

use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Members\Entity\Group;
use Common\Modules\Members\Entity\GroupLocale;

/**
 * Class EditGroup
 * @package Backend\Modules\Members\Actions
 */
class EditGroup extends BackendBaseActionEdit
{

    /**
     * @var Group
     */
    protected $group;

    /**
     * @var BackendLocalizationLocale
     */
    protected $locale;

    /**
     * The form instance
     *
     * @var BackendLocalizationForm
     */
    protected $frm;

    /**
     * Execute the action
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');

        $this->group = new Group(array($this->id), BL::getActiveLanguages());

        if (!$this->group->isLoaded()) {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }

        $this->locale = new BackendLocalizationLocale();

        parent::execute();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Load the form
     */
    private function loadForm()
    {
        $this->frm = new BackendLocalizationForm($this->locale, 'editGroup');

        $this->frm->addText('identifier', $this->group->getIdentifier());
        $this->frm->addCheckbox('registration', $this->group->isRegistration());
        $this->frm->addCheckbox('default', $this->group->isDefault());

        while ($language = $this->locale->loopLanguage()) {
            $this->group->lockLocaleLanguage($language->getCode());
            $this->frm->addText('title', $this->group->getLocale()->getTitle());
            $this->frm->addEditor('text', $this->group->getLocale()->getText());
            $this->frm->addEditor('introduction', $this->group->getLocale()->getIntroduction());
            $language->setMeta($this->frm, $this->group->getLocale()->getMetaId());
            $language->getMeta()->setUrlCallback(
                'Backend\\Modules\\'.$this->URL->getModule().'\\Engine\\Model',
                'getURLForGroup',
                array($language->getCode())
            );
            $this->locale->nextLanguage();
        }
    }

    /**
     * Parse the page
     */
    protected function parse()
    {
        parent::parse();

        $this->frm->parse($this->tpl);
        $this->locale->parse($this->tpl);
    }

    /**
     * Validate the form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            $this->frm->cleanupFields();

            $this->frm->getField('identifier')->isFilled(BL::err('IdentifierIsRequired'));

            while ($language = $this->locale->loopLanguage()) {
                $this->frm->getField('title', $language)->isFilled(BL::err('TitleIsRequired'));
                $language->getMeta()->validate();
                $this->locale->nextLanguage();
            }

            if ($this->frm->isCorrect()) {
                BackendProfilesModel::updateGroup(
                    $this->id,
                    array('name' => $this->frm->getField('identifier')->getValue())
                );

                if ($this->frm->getField('default')->isChecked()) {
                    CommonMembersModel::unsetGroupsDefault();
                }

                $this->group
                    ->setDefault((int)$this->frm->getField('default')->isChecked())
                    ->setRegistration((int)$this->frm->getField('registration')->isChecked())
                    ->save();

                while ($language = $this->locale->loopLanguage()) {
                    $groupLocale = $this->group->getLocale($language->getCode());
                    $groupLocale
                        ->setId($this->group->getId())
                        ->setLanguage($language->getCode())
                        ->setMetaId($language->getMeta()->save(true))
                        ->setTitle($this->frm->getField('title', $language)->getValue())
                        ->setText($this->frm->getField('text', $language)->getValue())
                        ->setIntroduction($this->frm->getField('introduction', $language)->getValue())
                        ->save();
                    $this->group->setLocale($groupLocale, $language->getCode());
                    $this->locale->nextLanguage();
                }

                BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_edit_group',
                    array('item' => $this->group->toArray())
                );

                $this->redirect(
                    BackendModel::createURLForAction('Groups').'&report=added&var='.
                    urlencode($this->group->getLocale(BL::getWorkingLanguage())->getTitle()).
                    '&highlight='.$this->group->getId()
                );
            }
        }
    }
}
