<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Entities\Engine\Entity;
use Common\Modules\Members\Engine\Model;

/**
 * Class Requisites
 * @package Common\Modules\Members\Entity
 */
class Requisites extends Entity
{

    protected $_table = Model::TBL_REQUISITES;

    protected $_query = Model::QRY_ENTITY_REQUISITES;

    protected $_columns = array(
        'member_id',
        'type',
        'business_entity_type',
        'company',
        'company_code',
        'vat_identifier',
        'bank',
        'bank_account',
        'bank_swift',
        'status',
        'created_on',
    );

    protected $_relations = array();

    protected $memberId;

    protected $type;

    protected $businessEntityType;

    protected $company;

    protected $companyCode;

    protected $vatIdentifier;

    protected $bank;

    protected $bankAccount;

    protected $bankSwift;

    protected $status;

    protected $createdOn;

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->memberId;
    }

    /**
     * @param $memberId
     * @return $this
     */
    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * @return RequisitesType
     */
    public function getType()
    {
        if (is_null($this->type)) {
            $this->type = new RequisitesType();
        }

        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = new RequisitesType($type);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBusinessEntityType()
    {
        return $this->businessEntityType;
    }

    /**
     * @param $businessEntityType
     * @return $this
     */
    public function setBusinessEntityType($businessEntityType)
    {
        $this->businessEntityType = strtoupper($businessEntityType);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param $company
     * @return $this
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanyCode()
    {
        return $this->companyCode;
    }

    /**
     * @param $companyCode
     * @return $this
     */
    public function setCompanyCode($companyCode)
    {
        $this->companyCode = $companyCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVatIdentifier()
    {
        return $this->vatIdentifier;
    }

    /**
     * @param $vatIdentifier
     * @return $this
     */
    public function setVatIdentifier($vatIdentifier)
    {
        $this->vatIdentifier = $vatIdentifier;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @param $bank
     * @return $this
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param $bankAccount
     * @return $this
     */
    public function setBankAccount($bankAccount)
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankSwift()
    {
        return $this->bankSwift;
    }

    /**
     * @param $bankSwift
     * @return $this
     */
    public function setBankSwift($bankSwift)
    {
        $this->bankSwift = $bankSwift;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        if (is_null($this->status)) {
            $this->status = new RequisitesStatus();
        }

        return $this->status;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = new RequisitesStatus($status);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param $createdOn
     * @return $this
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }
}
