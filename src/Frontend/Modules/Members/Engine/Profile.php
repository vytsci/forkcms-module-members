<?php

namespace Frontend\Modules\Members\Engine;

use Frontend\Modules\Profiles\Engine\Profile as BaseProfile;
use Common\Modules\Entities\Engine\ArrayableInterface;

/**
 * Class Profile
 * @package Frontend\Modules\Members\Engine
 */
class Profile extends BaseProfile implements ArrayableInterface
{

    /**
     * @param bool $lazyLoad
     * @return array
     */
    public function toArray($lazyLoad = true)
    {
        $return = array();

        $return['email'] = $this->getEmail();
        $return['status'] = $this->getStatus();
        $return['display_name'] = $this->getDisplayName();
        $return['registered_on'] = $this->getRegisteredOn();
        $return['url'] = $this->getUrl();

        foreach ($this->getSettings() as $key => $value) {
            $return['settings'][$key] = $value;
        }

        return $return;
    }
}
