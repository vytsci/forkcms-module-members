<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\Action as BackendBaseAction;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Members\Engine\Model as BackendMembersModel;

/**
 * Class MassRequisitesAction
 * @package Backend\Modules\Members\Actions
 */
class MassRequisitesAction extends BackendBaseAction
{

    /**
     * @throws \Common\Exception\RedirectException
     * @throws \Exception
     */
    public function execute()
    {
        parent::execute();

        $action = \SpoonFilter::getGetValue('action', array('approve', 'reject'), 'approve');

        if (!isset($_GET['id'])) {
            $this->redirect(BackendModel::createURLForAction('Requisites').'&error=no-requisites-selected');
        }

        $ids = (array)$_GET['id'];

        $report = 'requisites-';

        switch ($action) {
            case 'approve':
                $report .= 'approved';
                BackendMembersModel::changeStatusRequisites($ids, 'approved');
                BackendMembersModel::setAppointmentsBasedOnRequisites($ids);
                break;
            case 'reject':
                $report .= 'rejected';
                BackendMembersModel::changeStatusRequisites($ids, 'rejected');
                break;
        }

        $this->redirect(BackendModel::createURLForAction('Requisites').'&report='.$report);
    }
}
