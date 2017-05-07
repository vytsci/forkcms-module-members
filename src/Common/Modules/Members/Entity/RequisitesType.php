<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Entities\Engine\EnumValue;

/**
 * Class RequisitesType
 * @package Common\Modules\Members\Entity
 */
class RequisitesType extends EnumValue
{

    /**
     * @var string
     */
    protected $defaultValue = 'juridical';

    /**
     * @return bool
     */
    public function isNatural()
    {
        return $this->getValue() == 'natural';
    }

    /**
     * @return bool
     */
    public function isJuridical()
    {
        return $this->getValue() == 'juridical';
    }

    /**
     * @param bool $lazyLoad
     * @return array
     */
    public function toArray($lazyLoad = true)
    {
        return parent::toArray() + array(
            'is_natural' => $this->isNatural(),
            'is_juridical' => $this->isJuridical(),
        );
    }
}
