<?php

namespace Backend\Modules\Members;

use Backend\Core\Engine\Base\Config as BackendBaseConfig;

/**
 * Class Config
 * @package Backend\Modules\Members
 */
class Config extends BackendBaseConfig
{

    /**
     * The default action.
     *
     * @var    string
     */
    protected $defaultAction = 'Index';

    /**
     * The disabled actions.
     *
     * @var    array
     */
    protected $disabledActions = array();
}
