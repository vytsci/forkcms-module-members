<?php

namespace Backend\Modules\Members\Engine;

use Backend\Core\Engine\Language as BL;

use Common\Modules\Members\Entity\Member;

/**
 * Class Helper
 * @package Common\Modules\Members
 */
class Helper
{

    /**
     * @return array
     */
    public static function getYearsForDropdown()
    {
        $years = range(1900, (int)date('Y'));

        return array_combine($years, $years);
    }

    /**
     * @return array
     */
    public static function getMonthsForDropdown()
    {
        return array(
            1 => BL::lbl('January'),
            2 => BL::lbl('February'),
            3 => BL::lbl('March'),
            4 => BL::lbl('April'),
            5 => BL::lbl('May'),
            6 => BL::lbl('June'),
            7 => BL::lbl('July'),
            8 => BL::lbl('August'),
            9 => BL::lbl('September'),
            10 => BL::lbl('October'),
            11 => BL::lbl('November'),
            12 => BL::lbl('December'),
        );
    }

    /**
     * @return array
     */
    public static function getDaysForDropdown()
    {
        return range(1, 31);
    }

    /**
     * @param \SpoonForm $frm
     * @param $language
     * @param Member|null $member
     */
    public static function parseFieldsDateBirth(\SpoonForm &$frm, Member $member = null)
    {
        $frm->addDropdown(
            'date_birth_year',
            self::getYearsForDropdown(),
            isset($member) ? $member->getDateBirth('Y') : null
        );
        $frm->addDropdown(
            'date_birth_month',
            self::getMonthsForDropdown(),
            isset($member) ? $member->getDateBirth('n') : null
        );
        $frm->addDropdown(
            'date_birth_day',
            self::getDaysForDropdown(),
            isset($member) ? $member->getDateBirth('j') : null
        );
    }

    /**
     * @param \SpoonForm $frm
     * @throws \SpoonFormException
     */
    public static function validateFieldsDateBirth(\SpoonForm &$frm, $isRequired = true)
    {
        if (
            $frm->getField('date_birth_year')->isFilled($isRequired ? BL::err('FieldIsRequired') : null)
            && $frm->getField('date_birth_month')->isFilled($isRequired ? BL::err('FieldIsRequired') : null)
            && $frm->getField('date_birth_day')->isFilled($isRequired ? BL::err('FieldIsRequired') : null)
        ) {
            $dateBirth = self::getDateBirth($frm);

            if (strtotime($dateBirth) === false) {
                $frm->getField('date_birth_year')->addError(BL::err('FieldIsInvalid'));
                $frm->getField('date_birth_month')->addError(BL::err('FieldIsInvalid'));
                $frm->getField('date_birth_day')->addError(BL::err('FieldIsInvalid'));
            }
        }
    }

    /**
     * @param \SpoonForm $frm
     * @param string $format
     * @return bool|string
     */
    public static function getDateBirth(\SpoonForm $frm, $format = 'Y-m-d')
    {
        $dateBirth = null;

        if (
            $frm->existsField('date_birth_year')
            && $frm->existsField('date_birth_month')
            && $frm->existsField('date_birth_day')
            && $frm->getField('date_birth_year')->isFilled()
            && $frm->getField('date_birth_month')->isFilled()
            && $frm->getField('date_birth_day')->isFilled()
        ) {
            $dateBirthYear = $frm->getField('date_birth_year')->getValue();
            $dateBirthMonth = $frm->getField('date_birth_month')->getValue();
            $dateBirthDay = $frm->getField('date_birth_day')->getValue();

            $dateBirthRaw = $dateBirthYear.'-'.$dateBirthMonth.'-'.$dateBirthDay;

            $dateBirth = date($format, strtotime($dateBirthRaw));
        }

        return $dateBirth;
    }
}
