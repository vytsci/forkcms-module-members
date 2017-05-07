<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\DataGridDB as BackendDataGridDB;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;

/**
 * Class Groups
 * @package Backend\Modules\Members\Actions
 */
class Groups extends BackendBaseActionIndex
{

    /**
     *
     */
    public function execute()
    {
        parent::execute();

        $this->loadData();

        $this->parse();
        $this->display();
    }

    /**
     *
     */
    private function loadData()
    {
        $this->dataGrid = new BackendDataGridDB(
            CommonMembersModel::QRY_DG_GROUPS,
            array(BL::getWorkingLanguage())
        );

        if (BackendAuthentication::isAllowedAction('EditGroup')) {
            $this->dataGrid->setColumnURL(
                'title',
                BackendModel::createURLForAction('EditGroup').
                '&amp;id=[id]'
            );

            $this->dataGrid->addColumn(
                'edit',
                null,
                BL::lbl('Edit'),
                BackendModel::createURLForAction('EditGroup').'&amp;id=[id]',
                BL::lbl('Edit')
            );
        }
    }

    /**
     * @throws \SpoonTemplateException
     */
    protected function parse()
    {
        parent::parse();

        $this->tpl->assign('dataGrid', (string)$this->dataGrid->getContent());
    }
}
