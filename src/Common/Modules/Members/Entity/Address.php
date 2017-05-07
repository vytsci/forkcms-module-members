<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Entities\Engine\Entity;
use Common\Modules\Geo\Entity\Country;
use Common\Modules\Geo\Entity\State;
use Common\Modules\Geo\Entity\City;
use Common\Modules\Members\Engine\Model;
use Common\Modules\Members\Engine\Helper;

/**
 * Class Address
 * @package Common\Modules\Members\Entity
 */
class Address extends Entity
{

    protected $_table = Model::TBL_ADDRESSES;

    protected $_query = Model::QRY_ENTITY_ADDRESS;

    protected $_columns = array(
        'member_id',
        'primary',
        'billing',
        'geo_city_id',
        'postal_code',
        'address',
        'phone',
    );

    protected $_relations = array(
        'country',
        'state',
        'city',
    );

    protected $memberId;

    protected $primary;

    protected $billing;

    protected $geoCityId;

    protected $postalCode;

    protected $address;

    protected $phone;

    /**
     * @var Country
     */
    protected $country;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var City
     */
    protected $city;

    public function getMemberId()
    {
        return $this->memberId;
    }

    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }

    public function isPrimary()
    {
        return (bool)$this->primary;
    }

    public function setPrimary($primary = true)
    {
        $this->primary = (bool)$primary;

        return $this;
    }

    public function isBilling()
    {
        return (bool)$this->billing;
    }

    public function setBilling($billing = true)
    {
        $this->billing = (bool)$billing;

        return $this;
    }

    public function getGeoCityId()
    {
        return $this->geoCityId;
    }

    public function setGeoCityId($geoCityId)
    {
        $this->geoCityId = $geoCityId;

        return $this;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCountry($language = null)
    {
        if ($language === null) {
            $language = $this->getLanguage();
        }

        if ($this->country === null) {
            $this->country = $this->getState($language)->getCountry();
        }

        return $this->country;
    }

    public function getState($language = null)
    {
        if ($language === null) {
            $language = $this->getLanguage();
        }

        if ($this->state === null) {
            $this->state = $this->getCity($language)->getState();
        }

        return $this->state;
    }

    public function getCity($language = null)
    {
        if ($language === null) {
            $language = $this->getLanguage();
        }

        if ($this->city === null) {
            $this->city = new City(array($this->getGeoCityId()), array($language));
        }

        return $this->city;
    }

    public function afterSave()
    {
        $array = $this->toArray();

        if ($this->isPrimary()) {
            Helper::afterSetAddressPrimary($array);
        }

        if ($this->isBilling()) {
            Helper::afterSetAddressBilling($array);
        }
    }
}
