<?php

namespace Backend\Modules\Members\Engine;

use Common\Modules\Members\Entity\Group as CommonGroup;
use Backend\Modules\Localization\Engine\EntityLocale;

/**
 * Class Group
 * @package Backend\Modules\Members
 */
class Group extends CommonGroup
{

    /**
     * @var array
     */
    protected $_relations = array(
        'locale',
    );

    /**
     * @var EntityLocale
     */
    private $locale;

    /**
     * @param array $parameters
     * @return $this
     */
    public function load($parameters = array())
    {
        parent::load($parameters);

        $this->locale = new EntityLocale(
            'Common\\Modules\\Members\\GroupLocale',
            Model::QRY_ENTITY_GROUP_LOCALE,
            array($this->getId())
        );

        return $this;
    }

    /**
     * @return EntityLocale
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
