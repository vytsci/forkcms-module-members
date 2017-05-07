<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;

/**
 * Class Settings
 * @package Backend\Modules\Members\Actions
 */
class Settings extends BackendBaseActionEdit
{

    /**
     * @var array
     */
    private $types = array();

    /**
     * @var array
     */
    private $groups = array();

    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->getData();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    private function getData()
    {
        $this->types = CommonMembersModel::getMemberTypes();
        $this->groups = BackendMembersModel::getGroupsForDropDown();
    }

    /**
     * Loads the settings form
     */
    private function loadForm()
    {
        $this->frm = new BackendForm('settings');

        $this->frm->addCheckbox(
            'show_type_choice',
            $this->get('fork.settings')->get('Members', 'show_type_choice', 0)
        );

        $this->frm->addCheckbox(
            'show_type_choice_as_page',
            $this->get('fork.settings')->get('Members', 'show_type_choice_as_page', 0)
        );

        $this->frm->addDropdown(
            'default_type',
            BackendMembersModel::getMemberTypesForDropdown(),
            $this->get('fork.settings')->get('Members', 'default_type')
        )->setDefaultElement('-');

        $this->frm->addCheckbox(
            'enable_index_page',
            $this->get('fork.settings')->get('Members', 'enable_index_page', 0)
        );

        $this->frm->addCheckbox(
            'enable_email_registration',
            $this->get('fork.settings')->get('Members', 'enable_email_registration', 0)
        );

        $this->frm->addCheckbox(
            'enable_auto_approve_requisites',
            $this->get('fork.settings')->get('Members', 'enable_auto_approve_requisites', 0)
        );

        $this->frm->addEditor(
            'pending_text',
            $this->get('fork.settings')->get('Members', BL::getWorkingLanguage().'_pending_text', null)
        );
        $this->frm->addEditor(
            'welcome_text',
            $this->get('fork.settings')->get('Members', BL::getWorkingLanguage().'_welcome_text', null)
        );

        $this->frm->addText(
            'sources',
            $this->get('fork.settings')->get('Members', BL::getWorkingLanguage().'_sources', null)
        );

        $this->frm->addText(
            'url_terms',
            $this->get('fork.settings')->get('Members', BL::getWorkingLanguage().'_url_terms', null)
        );

        $this->frm->addEditor(
            'terms_requisites',
            $this->get('fork.settings')->get('Members', BL::getWorkingLanguage().'_terms_requisites', null)
        );

        foreach ($this->types as $type) {
            $this->frm->addDropdown(
                'default_group_'.$type,
                array('-1' => '-') + $this->groups,
                $this->get('fork.settings')->get('Members', 'default_group_'.$type, -1)
            );
        }
    }

    /**
     * Validates the settings form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            if ($this->frm->isCorrect()) {
                $this->get('fork.settings')->set(
                    'Members',
                    'show_type_choice',
                    (int)$this->frm->getField('show_type_choice')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    'show_type_choice_as_page',
                    (int)$this->frm->getField('show_type_choice_as_page')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    'default_type',
                    $this->frm->getField('default_type')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    'enable_index_page',
                    (int)$this->frm->getField('enable_index_page')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    'enable_email_registration',
                    (int)$this->frm->getField('enable_email_registration')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    'enable_auto_approve_requisites',
                    (int)$this->frm->getField('enable_auto_approve_requisites')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    BL::getWorkingLanguage().'_pending_text',
                    $this->frm->getField('pending_text')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Members',
                    BL::getWorkingLanguage().'_welcome_text',
                    $this->frm->getField('welcome_text')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    BL::getWorkingLanguage().'_sources',
                    ($this->frm->getField('sources')->isFilled()) ? $this->frm->getField('sources')->getValue() : null
                );

                $this->get('fork.settings')->set(
                    'Members',
                    BL::getWorkingLanguage().'_url_terms',
                    $this->frm->getField('url_terms')->getValue()
                );

                $this->get('fork.settings')->set(
                    'Members',
                    BL::getWorkingLanguage().'_terms_requisites',
                    $this->frm->getField('terms_requisites')->getValue()
                );

                foreach ($this->types as $type) {
                    $value = (int)$this->frm->getField('default_group_'.$type)->getValue();
                    $this->get('fork.settings')->set('Members', 'default_group_'.$type, $value < 1 ? null : $value);
                }

                $this->redirect(BackendModel::createURLForAction('Settings').'&report=saved');
            }
        }
    }

    protected function parse()
    {
        parent::parse();

        $typesGroupsFields = array();

        foreach ($this->types as $type) {
            $typesGroupsFields[$type] = array(
                'name' => \SpoonFilter::toCamelCase('default_group_'.$type),
                'field' => $this->frm->getField('default_group_'.$type)->parse(),
                'errors' => $this->frm->getField('default_group_'.$type)->getErrors(),
                'label' => BL::lbl(\SpoonFilter::toCamelCase('default_group_'.$type)),
            );
        }

        $this->tpl->assign('typesGroupsFields', $typesGroupsFields);
    }
}
