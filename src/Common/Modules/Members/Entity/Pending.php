<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Entities\Engine\Entity;
use Common\Modules\Members\Engine\Model;

/**
 * Class Pending
 * @package Common\Modules\Members\Entity
 */
class Pending extends Entity
{

    protected $_table = Model::TBL_PENDING;

    protected $_query = Model::QRY_ENTITY_PENDING;

    protected $_columns = array(
        'email',
        'type',
        'token',
        'created_on',
    );

    protected $email;

    protected $type;

    protected $token;

    protected $createdOn;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }
}
