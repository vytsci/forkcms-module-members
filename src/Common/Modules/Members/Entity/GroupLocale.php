<?php

namespace Common\Modules\Members\Entity;

use Common\Modules\Localization\Engine\EntityLocale;
use Common\Modules\Members\Engine\Model;

/**
 * Class GroupLocale
 * @package Common\Modules\Members\Entity
 */
class GroupLocale extends EntityLocale
{

    protected $_table = Model::TBL_GROUPS_LOCALE;

    protected $_query = Model::QRY_ENTITY_GROUP_LOCALE;

    protected $_primary = array('id', 'language');

    protected $_columns = array(
        'meta_id',
        'title',
        'introduction',
        'text',
    );

    protected $title;

    protected $introduction;

    protected $text;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetaId()
    {
        return $this->metaId;
    }

    /**
     * @param $metaId
     * @return $this
     */
    public function setMetaId($metaId)
    {
        $this->metaId = $metaId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @param $introduction
     * @return $this
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }
}
