<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\DataGridArray as BackendDataGridArray;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Filter\Engine\Helper as CommonFilterHelper;
use Common\Modules\Filter\Engine\Filter;

class Pending extends BackendBaseActionIndex
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var BackendDataGridArray
     */
    private $dgPending;

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

        $this->filter
            ->addTextCriteria(
                'search',
                array('mp.email'),
                CommonFilterHelper::OPERATOR_PATTERN
            );
    }

    /**
     * @throws \Exception
     * @throws \SpoonDatagridException
     */
    private function loadDataGrid()
    {
        $pending = BackendMembersModel::getMembersForDataGrid(
            $this->filter->getQuery(CommonMembersModel::QRY_DG_PENDING)
        );

        $this->dgPending = new BackendDataGridArray($pending);
    }

    /**
     * @throws \SpoonTemplateException
     */
    protected function parse()
    {
        parent::parse();

        $this->filter->parse($this->tpl);

        $this->tpl->assign(
            'dgPending',
            ($this->dgPending->getNumResults() != 0) ? $this->dgPending->getContent() : false
        );
    }
}
