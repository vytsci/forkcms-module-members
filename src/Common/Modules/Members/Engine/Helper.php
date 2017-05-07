<?php

namespace Common\Modules\Members\Engine;

use Symfony\Component\Intl\Intl;

use Common\Core\Model as CommonModel;
use Common\Modules\Members\Entity\Address;
use Common\Modules\Members\Entity\Group;
use Common\Modules\Members\Entity\GroupLocale;
use Common\Modules\Members\Entity\Member;
use Common\Modules\Members\Entity\MemberType;
use Common\Modules\Members\Entity\Pending;

/**
 * Class Helper
 * @package Common\Modules\Members\Engine
 */
class Helper
{

    /**
     * @param \SpoonForm $frm
     * @param $language
     * @param bool|false $checkPrimary
     * @param Address|null $address
     */
    public static function parseFieldsAddress(
        \SpoonForm &$frm,
        $language,
        $checkPrimary = false,
        Address $address = null,
        $readOnly = false
    ) {
        $request = CommonModel::getContainer()->get('request');

        $fieldAddAddress = $frm->addCheckbox('address_add_address');
        $fieldPrimary = $frm->addCheckbox('address_primary', $checkPrimary);
        $fieldBilling = $frm->addCheckbox('address_billing', isset($address) ? $address->isBilling() : null);


        $fieldCountry = $frm->addDropdown('address_country');
        $fieldCountryValue = $request->get('address_country', null);
        if (empty($fieldCountryValue) && isset($address)) {
            $fieldCountryValue = $address->getCountry($language)->getId();
        }
        $fieldCountry
            ->setDefaultElement('-')
            ->setAllowExternalData()
            ->setAttributes(
                array(
                    'class' => 'jsGeoSelect',
                    'data-select-states' => '#addressState',
                    'data-selected' => $fieldCountryValue,
                )
            );

        $fieldState = $frm->addDropdown('address_state');
        $fieldStateValue = $request->get('address_state', null);
        if (empty($fieldStateValue) && isset($address)) {
            $fieldStateValue = $address->getState($language)->getId();
        }
        $fieldState
            ->setDefaultElement('-')
            ->setAllowExternalData()
            ->setAttributes(
                array(
                    'data-select-cities' => '#addressCity',
                    'data-selected' => $fieldStateValue,
                )
            );

        $fieldCity = $frm->addDropdown('address_city');
        $fieldCityValue = $request->get('address_city', null);
        if (empty($fieldCityValue) && isset($address)) {
            $fieldCityValue = $address->getCity($language)->getId();
        }
        $fieldCity
            ->setDefaultElement('-')
            ->setAllowExternalData()
            ->setAttributes(
                array(
                    'data-selected' => $fieldCityValue,
                )
            );

        $fieldPostalCode = $frm->addText('address_postal_code', isset($address) ? $address->getPostalCode() : null);
        $fieldAddress = $frm->addText('address_address', isset($address) ? $address->getAddress() : null);
        $fieldPhone = $frm->addText('address_phone', isset($address) ? $address->getPhone() : null);

        if ($readOnly) {
            $fieldAddAddress->setAttribute('readonly');
            $fieldPrimary->setAttribute('readonly');
            $fieldBilling->setAttribute('readonly');
            $fieldCountry->setAttribute('readonly');
            $fieldState->setAttribute('readonly');
            $fieldCity->setAttribute('readonly');
            $fieldPostalCode->setAttribute('readonly');
            $fieldAddress->setAttribute('readonly');
            $fieldPhone->setAttribute('readonly');
        }
    }

    /**
     * @param \SpoonForm $frm
     * @param Address $address
     * @return Address
     * @throws \SpoonFormException
     */
    public static function validateAddress(\SpoonForm &$frm, Address &$address)
    {
        $address
            ->setPrimary($frm->getField('address_primary')->isChecked())
            ->setBilling($frm->getField('address_billing')->isChecked())
            ->setGeoCityId($frm->getField('address_city')->getValue())
            ->setPostalCode($frm->getField('address_postal_code')->getValue())
            ->setAddress($frm->getField('address_address')->getValue())
            ->setPhone($frm->getField('address_phone')->getValue());

        return $address;
    }

    /**
     * @param $profile
     * @param $url
     * @param $subject
     */
    public static function sendActivation($profile, $url, $subject)
    {
        $mailValues['activationUrl'] = $url;
        $from = CommonModel::get('fork.settings')->get('Core', 'mailer_from');
        $replyTo = CommonModel::get('fork.settings')->get('Core', 'mailer_reply_to');
        $message = \Common\Mailer\Message::newInstance($subject)
            ->setFrom(array($from['email'] => $from['name']))
            ->setTo(array($profile['email'] => $profile['display_name']))
            ->setReplyTo(array($replyTo['email'] => $replyTo['name']))
            ->parseHtml(
                FRONTEND_MODULES_PATH.'/Profiles/Layout/Templates/Mails/Register.tpl',
                $mailValues,
                true
            );
        CommonModel::get('mailer')->send($message);
    }

    /**
     * @param $profile
     * @param $url
     * @param $subject
     */
    public static function sendEmailRegistration(Pending $pending, $url, $subject)
    {
        $mailValues['registrationUrl'] = $url;
        $from = CommonModel::get('fork.settings')->get('Core', 'mailer_from');
        $replyTo = CommonModel::get('fork.settings')->get('Core', 'mailer_reply_to');
        $message = \Common\Mailer\Message::newInstance($subject)
            ->setFrom(array($from['email'] => $from['name']))
            ->setTo(array($pending->getEmail()))
            ->setReplyTo(array($replyTo['email'] => $replyTo['name']))
            ->parseHtml(
                FRONTEND_MODULES_PATH.'/Members/Layout/Templates/Mails/EmailRegistration.tpl',
                $mailValues,
                true
            );
        CommonModel::get('mailer')->send($message);
    }

    /**
     * @todo: when events will be working again remove this
     *
     * @param $item
     */
    public static function afterSetAddressPrimary($item)
    {
        Model::switchAddressPrimary($item);
    }

    /**
     * @todo: when events will be working again remove this
     *
     * @param $item
     */
    public static function afterSetAddressBilling($item)
    {
        Model::switchAddressBilling($item);
    }

    /**
     * @return string
     */
    public static function getLoginQueryString()
    {
        $queryString = '';

        if (isset($_GET['queryString'])) {
            $queryString = SITE_URL.'/'.urldecode($_GET['queryString']);
        }

        $queryString = ($queryString != '') ? '?queryString='.urlencode($queryString) : '';

        return $queryString;
    }

    /**
     * @return array
     */
    public static function getAvatarsPaths()
    {
        $path = FRONTEND_FILES_PATH.'/Members/avatars';

        /* @todo: Paths should be stored within settings */

        return array(
            $path.'/source',
            $path.'/64x64',
            $path.'/128x128',
            $path.'/182x182',
            $path.'/256x256',
            $path.'/320x320',
            $path.'/384x384',
            $path.'/448x448',
            $path.'/512x512',
        );
    }
}
