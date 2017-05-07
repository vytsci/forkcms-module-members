<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Common\Modules\Members\Entity\Member as Member;

/**
 * Class Delete
 * @package Backend\Modules\Members\Actions
 */
class Delete extends BackendBaseActionDelete
{

    /**
     * @var
     */
    private $member;

    /**
     * Execute the action.
     */
    public function execute()
    {
        $this->id = $this->getParameter('id', 'int');
        $this->member = new Member(array($this->id));

        if ($this->member->isLoaded()) {
            parent::execute();
            $profile = BackendProfilesModel::get($this->member->getId());

            if ($profile['status'] === 'deleted') {
                BackendProfilesModel::update($this->id, array('status' => 'active'));
                BackendModel::triggerEvent('Profile', 'after_reactivate', array('id' => $profile['id']));
                $this->redirect(
                    BackendModel::createURLForAction('Index').'&report=profile-undeleted&var='.urlencode(
                        $profile['email']
                    ).'&highlight=row-'.$profile['id']
                );
            } else {
                BackendProfilesModel::delete($profile['id']);
                BackendModel::triggerEvent('Profile', 'after_delete_profile', array('id' => $profile['id']));
                $this->redirect(
                    BackendModel::createURLForAction('Index').'&report=profile-deleted&var='.urlencode(
                        $profile['email']
                    ).'&highlight=row-'.$profile['id']
                );
            }
        } else {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }
    }
}
