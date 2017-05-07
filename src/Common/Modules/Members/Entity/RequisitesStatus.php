<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Entities\Engine\EnumValue;

/**
 * Class RequisitesStatus
 * @package Common\Modules\Members\Entity
 */
class RequisitesStatus extends EnumValue
{

    /**
     * @var string
     */
    protected $defaultValue = 'pending';

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->getValue() == 'pending';
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->getValue() == 'approved';
    }

    /**
     * @return bool
     */
    public function isRejected()
    {
        return $this->getValue() == 'rejected';
    }

    /**
     * @param bool $lazyLoad
     * @return array
     */
    public function toArray($lazyLoad = true)
    {
        return parent::toArray() + array(
            'is_pending' => $this->isPending(),
            'is_approved' => $this->isApproved(),
            'is_rejected' => $this->isRejected(),
        );
    }
}
