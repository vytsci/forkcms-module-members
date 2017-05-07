<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\DataGridArray as BackendDataGridArray;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Filter\Engine\Filter;

class Requisites extends BackendBaseActionIndex
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var BackendDataGridArray
     */
    private $dgRequisites;

    /**
     *
     */
    public function execute()
    {
        parent::execute();
        $this->loadFilter();
        $this->loadDataGrid();
        $this->parse();
        $this->display();
    }

    /**
     * Loads filter form
     */
    private function loadFilter()
    {
        $this->filter = new Filter();
    }

    /**
     * @throws \Exception
     * @throws \SpoonDatagridException
     */
    private function loadDataGrid()
    {
        $requisites = BackendMembersModel::getMembersForDataGrid(
            $this->filter->getQuery(CommonMembersModel::QRY_DG_REQUISITES)
        );

        $this->dgRequisites = new BackendDataGridArray($requisites);

        $this->dgRequisites->setColumnHidden('member_id');

        $this->dgRequisites->setMassActionCheckboxes('check', '[id]');
        $ddmMassAction = new \SpoonFormDropdown(
            'action',
            array(
                'approve' => BL::lbl('Approve'),
                'reject' => BL::lbl('Reject'),
            ),
            'approve',
            false,
            'form-control',
            'form-control danger'
        );
        $ddmMassAction->setOptionAttributes('approve', array('data-target' => '#confirmApprove'));
        $ddmMassAction->setOptionAttributes('reject', array('data-target' => '#confirmReject'));
        $this->dgRequisites->setMassAction($ddmMassAction);

        if (BackendAuthentication::isAllowedAction('EditRequisites')) {
            $this->dgRequisites->addColumn(
                'edit',
                null,
                BL::lbl('Edit'),
                BackendModel::createURLForAction('EditRequisites').'&amp;id=[member_id]',
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

        $this->filter->parse($this->tpl);

        $this->tpl->assign(
            'dgRequisites',
            ($this->dgRequisites->getNumResults() != 0) ? $this->dgRequisites->getContent() : false
        );
    }
}
