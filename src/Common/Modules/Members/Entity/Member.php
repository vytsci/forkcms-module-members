<?php

namespace Common\Modules\Members\Entity;

use Common\Core\Model as CommonModel;
use Common\Modules\Entities\Engine\Entity;
use Common\Modules\Members\Engine\Model;

/**
 * Class Member
 * @package Common\Modules\Members\Entity
 */
class Member extends Entity
{

    protected $_table = Model::TBL_MEMBERS;

    protected $_query = Model::QRY_ENTITY_MEMBER;

    protected $_columns = array(
        'member_id',
        'type',
        'first_name',
        'last_name',
        'introduction',
        'phone',
        'avatar',
        'gender',
        'date_birth',
        'source',
    );

    protected $_relations = array(
        'email',
        'display_name',
        'url',
        'addresses',
        'address_primary',
        'address_billing',
        'groups',
        'requisites',
    );

    protected $memberId;

    protected $type;

    protected $firstName;

    protected $lastName;

    protected $introduction;

    protected $phone;

    protected $avatar;

    protected $gender;

    protected $dateBirth;

    protected $source;

    protected $addresses;

    protected $groups;

    protected $requisites;

    protected $email;

    protected $displayName;

    protected $url;

    public function load($parameters = array())
    {
        parent::load($parameters);

        return $this;
    }

    public function loadAddresses($language = null)
    {
        if ($language === null) {
            $language = $this->getLanguage();
        }

        if ($this->isLoaded()) {
            $this->addresses = Model::getMemberAddresses($this->getId(), $language);
        }

        return $this;
    }

    public function loadGroups($language = null)
    {
        if ($language === null) {
            $language = $this->getLanguage();
        }

        if ($this->isLoaded()) {
            $this->groups = Model::getMemberGroups($this->getId(), $language);
        }

        return $this;
    }

    public function loadRequisites()
    {
        if ($this->isLoaded()) {
            $this->requisites = new Requisites(array($this->getId()));
        }

        return $this;
    }

    public function getType()
    {
        if (is_null($this->type)) {
            $this->type = new MemberType();
        }

        return $this->type;
    }

    public function setType($type)
    {
        $this->type = new MemberType($type);

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getIntroduction()
    {
        return $this->introduction;
    }

    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

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

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDateBirth($format = 'Y-m-d H:i:s')
    {
        $dateBirth = strtotime(str_replace(array('/'), '-', $this->dateBirth));

        if ($format) {
            $dateBirth = date($format, $dateBirth);
        }

        return $dateBirth;
    }

    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;

        return $this;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Make sure you have loaded members addresses
     *
     * @return Address[]|array
     */
    public function getAddresses()
    {
        return (array)$this->addresses;
    }

    /**
     * Make sure you have loaded members addresses
     *
     * @return Address
     */
    public function getAddressPrimary()
    {
        foreach ($this->getAddresses() as $address) {
            if ($address->isPrimary()) {
                return $address;
            }
        }

        return new Address();
    }

    /**
     * Make sure you have loaded members addresses
     *
     * @return Address
     */
    public function getAddressBilling()
    {
        foreach ($this->getAddresses() as $address) {
            if ($address->isBilling()) {
                return $address;
            }
        }

        return $this->getAddressPrimary();
    }

    /**
     * @param $id
     *
     * @return bool
     * @throws \SpoonDatabaseException
     */
    public function isInGroup($id)
    {
        $groups = array();
        if ($this->groups === null) {
            $groups = CommonModel::getContainer()->get('database')->getColumn(
                Model::TBL_GROUPS,
                'id'
            );
        }

        return array_search($id, $groups) || isset($this->groups[$id]);
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return mixed
     */
    public function getRequisites()
    {
        return $this->requisites;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param $displayName
     *
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
