<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\DataGridArray as BackendDataGridArray;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Filter\Engine\Helper as CommonFilterHelper;
use Common\Modules\Filter\Engine\Filter;

/**
 * Class Index
 * @package Backend\Modules\Members\Actions
 */
class Index extends BackendBaseActionIndex
{

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var BackendDataGridArray
     */
    private $dgMembers;

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
                array('p.email', 'm.first_name', 'm.last_name'),
                CommonFilterHelper::OPERATOR_PATTERN
            )
            ->addDropdownCriteria('status', array('p.status'), BackendProfilesModel::getStatusForDropDown())
            ->addDropdownCriteria('group', array('mhg.group_id'), BackendMembersModel::getGroupsForDropDown());
    }

    /**
     * @throws \Exception
     * @throws \SpoonDatagridException
     */
    private function loadDataGrid()
    {
        $members = BackendMembersModel::getMembersForDataGrid(
            $this->filter->getQuery(CommonMembersModel::QRY_DG_MEMBERS)
        );

        $this->dgMembers = new BackendDataGridArray($members);

        if (BackendAuthentication::isAllowedAction('Edit')) {
            $this->dgMembers->setColumnURL('email', BackendModel::createURLForAction('Edit').'&amp;id=[id]');
            $this->dgMembers->addColumn(
                'edit',
                null,
                BL::getLabel('Edit'),
                BackendModel::createURLForAction('Edit', null, null, null).'&amp;id=[id]',
                BL::getLabel('Edit')
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
            'dgMembers',
            ($this->dgMembers->getNumResults() != 0) ? $this->dgMembers->getContent() : false
        );
    }
}
