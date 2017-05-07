<?php

namespace Backend\Modules\Members\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Common\Modules\Members\Engine\Helper;
use Common\Modules\Members\Entity\Address;

/**
 * Class SetAddressPrimary
 * @package Backend\Modules\Blog\Ajax
 */
class SetAddressPrimary extends BackendBaseAJAXAction
{

    /**
     *
     */
    public function execute()
    {
        parent::execute();

        $id = trim(\SpoonFilter::getPostValue('id', null, 0, 'int'));
        $primary = trim(\SpoonFilter::getPostValue('primary', null, 0, 'int'));
        $address = new Address($id);

        if ($address->isLoaded()) {
            $address->setPrimary($primary);
            $address->save();

            if ($address->isPrimary()) {
                /*BackendModel::triggerEvent(
                    $this->getModule(),
                    'after_set_address_primary',
                    array('item' => $address->toArray())
                );*/
                Helper::afterSetAddressPrimary($address->toArray());
            }

            $this->output(self::OK, $address->toArray(), BL::msg('AddressPrimaryWasSet'));

            return;
        }

        $this->output(self::ERROR, null, BL::err('InvalidParameters'));
    }
}
