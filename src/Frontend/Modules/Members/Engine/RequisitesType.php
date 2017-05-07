<?php

namespace Frontend\Modules\Members\Engine;

use Frontend\Core\Engine\Language as FL;

use Common\Modules\Members\Entity\MemberType as BaseMemberType;

/**
 * Class RequisitesType
 * @package Frontend\Modules\Members\Engine
 */
class RequisitesType extends BaseMemberType
{

    /**
     *
     */
    public function afterLoad()
    {
        if ($this->isLoaded()) {
            $this->setLabel(FL::lbl($this->getValue(true)));
            $this->setMessage(FL::msg($this->getValue(true)));
            $this->setError(FL::err($this->getValue(true)));
            $this->setAction(FL::act($this->getValue(true)));
        }
    }
}
