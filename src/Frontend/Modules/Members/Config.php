<?php

namespace Frontend\Modules\Members;

use Frontend\Core\Engine\Base\Config as FrontendBaseConfig;

/**
 * Class Config
 * @package Frontend\Modules\Members
 */
class Config extends FrontendBaseConfig
{

    /**
     * The default action
     *
     * @var    string
     */
    protected $defaultAction = 'Index';

    /**
     * The disabled actions
     *
     * @var    array
     */
    protected $disabledActions = array();
}
