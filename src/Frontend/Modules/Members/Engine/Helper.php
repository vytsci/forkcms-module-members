<?php

namespace Frontend\Modules\Members\Engine;

use Frontend\Core\Engine\Language as FL;
use Frontend\Core\Engine\Header as FrontendHeader;

use Common\Core\Model as CommonModel;
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
            1 => FL::lbl('January'),
            2 => FL::lbl('February'),
            3 => FL::lbl('March'),
            4 => FL::lbl('April'),
            5 => FL::lbl('May'),
            6 => FL::lbl('June'),
            7 => FL::lbl('July'),
            8 => FL::lbl('August'),
            9 => FL::lbl('September'),
            10 => FL::lbl('October'),
            11 => FL::lbl('November'),
            12 => FL::lbl('December'),
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
            $frm->getField('date_birth_year')->isFilled($isRequired ? FL::err('FieldIsRequired') : null)
            && $frm->getField('date_birth_month')->isFilled($isRequired ? FL::err('FieldIsRequired') : null)
            && $frm->getField('date_birth_day')->isFilled($isRequired ? FL::err('FieldIsRequired') : null)
        ) {
            $dateBirth = self::getDateBirth($frm);

            if (strtotime($dateBirth) === false) {
                $frm->getField('date_birth_year')->addError(FL::err('FieldIsInvalid'));
                $frm->getField('date_birth_month')->addError(FL::err('FieldIsInvalid'));
                $frm->getField('date_birth_day')->addError(FL::err('FieldIsInvalid'));
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

    /**
     * @return array
     */
    public static function getSourcesForDropdown()
    {
        $sources = array_filter(
            (array)explode(
                ',',
                CommonModel::get('fork.settings')->get('Members', FRONTEND_LANGUAGE.'_sources', '')
            )
        );

        $sourceValues = array();

        foreach ($sources as $source) {
            $sourceValues[$source] = $source;
        }

        return $sources;
    }

    public static function loadAssetsAddress(FrontendHeader $header)
    {
        $header->addCSS('/src/Frontend/Modules/Geo/Layout/Css/Geo.css', true);

        $header->addJS(
            '/src/Frontend/Modules/Geo/Js/Geo.js',
            true,
            true,
            FrontendHeader::PRIORITY_GROUP_MODULE
        );
    }
}
