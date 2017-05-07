<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Localization\Engine\Entity;
use Common\Modules\Members\Engine\Model;

/**
 * Class Group
 * @package Common\Modules\Members\Entity
 */
class Group extends Entity
{

    protected $_table = Model::TBL_GROUPS;

    protected $_query = Model::QRY_ENTITY_GROUP;

    protected $_locale = '\\Common\\Modules\\Members\\Entity\\GroupLocale';

    protected $_columns = array(
        'default',
        'registration',
    );

    private $identifier;

    protected $default;

    protected $registration;

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function isDefault()
    {
        return (bool)$this->default;
    }

    public function setDefault($default)
    {
        $this->default = $default ? 1 : 0;

        return $this;
    }

    public function isRegistration()
    {
        return (bool)$this->registration;
    }

    public function setRegistration($registration)
    {
        $this->registration = $registration ? 1 : 0;

        return $this;
    }
}
