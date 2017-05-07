<?php

namespace Backend\Modules\Members\Actions;

use Backend\Core\Engine\Base\ActionDelete as BackendBaseActionDelete;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use Common\Modules\Members\Entity\Member as Member;

/**
 * Class Block
 * @package Backend\Modules\Members\Actions
 */
class Block extends BackendBaseActionDelete
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
            $profile = BackendProfilesModel::get($this->id);

            if ($profile['status'] === 'blocked') {
                BackendProfilesModel::update($this->id, array('status' => 'active'));
                BackendModel::triggerEvent('Profiles', 'after_unblock', array('id' => $this->id));
                $this->redirect(
                    BackendModel::createURLForAction('Index').'&report=profile-unblocked&var='.urlencode(
                        $profile['email']
                    ).'&highlight=row-'.$this->id
                );
            } else {
                BackendProfilesModel::deleteSession($this->id);
                BackendProfilesModel::update($this->id, array('status' => 'blocked'));
                BackendModel::triggerEvent('Profiles', 'after_block', array('id' => $this->id));
                $this->redirect(
                    BackendModel::createURLForAction('Index').'&report=profile-blocked&var='.urlencode(
                        $profile['email']
                    ).'&highlight=row-'.$this->id
                );
            }
        } else {
            $this->redirect(BackendModel::createURLForAction('Index').'&error=non-existing');
        }
    }
}
