<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Entities\Engine\EnumValue;

/**
 * Class MemberType
 * @package Common\Modules\Members\Entity
 */
class MemberType extends EnumValue
{

    /**
     * @var string
     */
    protected $defaultValue = 'natural';

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
