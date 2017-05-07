<?php

namespace Frontend\Modules\Members\Engine;

use Symfony\Component\Filesystem\Filesystem;

use Frontend\Core\Engine\Navigation as FrontendNavigation;

use Common\Core\Model as CommonModel;
use Common\Modules\Members\Engine\Model as CommonMembersModel;
use Common\Modules\Members\Engine\Helper as CommonMembersHelper;
use Common\Modules\Members\Entity\Member;

class Model
{

    /**
     * @var array
     */
    private static $memberTypesForRegistration = array();

    /**
     * @var array
     */
    private static $memberTypesForDropdown = array();

    /**
     * @var array
     */
    private static $requisitesTypesForDropdown = array();

    /**
     * @param null $where
     * @return int
     */
    public static function getCountMembers($where = null)
    {
        $count = (int)CommonMembersModel::getCount(
            CommonMembersModel::QRY_COUNT_MEMBERS,
            FRONTEND_LANGUAGE,
            $where
        );

        return $count;
    }

    /**
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return array
     */
    public static function getListMembers($where = null, $order = null, $limit = null, $columnSize = 0)
    {
        $members = CommonMembersModel::getArray(
            CommonMembersModel::QRY_LIST_MEMBERS,
            array(FRONTEND_LANGUAGE),
            $where,
            $order,
            $limit
        );

        $groups = CommonMembersModel::getListMembersGroups(FRONTEND_LANGUAGE);

        $index = 0;
        foreach ($members as &$member) {
            $index++;

            $member['member_url'] =
                FrontendNavigation::getURLForBlock('Members')
                .'/'.$member['url'];

            $member['groups'] = isset($groups[$member['id']]) ? $groups[$member['id']] : array();
        }

        return $members;
    }

    /**
     * @param $url
     * @param $language
     * @return Member
     */
    public static function getMemberByUrl($url)
    {
        $record = (array)CommonModel::getContainer()->get('database')->getRecord(
            'SELECT m.*, p.* FROM '.CommonMembersModel::TBL_MEMBERS.' AS m
            INNER JOIN profiles AS p ON p.id = m.id
            WHERE p.url = ?',
            array($url)
        );

        $member = new Member();
        $member->assemble($record);
        $member->loadGroups(FRONTEND_LANGUAGE);

        return $member;
    }

    /**
     * @param $url
     * @return int
     */
    public static function getMemberIdByUrl($url)
    {
        $id = (int)CommonModel::getContainer()->get('database')->getVar(
            'SELECT m.id FROM members AS m
            INNER JOIN profiles AS p ON p.id = m.id
            WHERE p.url = ?',
            array($url)
        );

        return $id;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getAvatarInfo($id)
    {
        $record = (array)CommonModel::getContainer()->get('database')->getRecord(
            'SELECT m.id, m.avatar, p.display_name, p.email, p.url FROM '.CommonMembersModel::TBL_MEMBERS.' AS m
            INNER JOIN profiles AS p ON p.id = m.id
            WHERE m.id = ?',
            array((int)$id)
        );

        return $record;
    }

    /**
     * @return array
     */
    public static function getMemberTypesForRegistration()
    {
        if (empty(self::$memberTypesForRegistration)) {
            self::$memberTypesForRegistration = array();

            foreach (CommonMembersModel::getMemberTypes() as $typeValue) {
                $type = new MemberType($typeValue);
                self::$memberTypesForRegistration[$typeValue] = $type->toArray();
            }
        }

        return self::$memberTypesForRegistration;
    }

    /**
     * @return array
     */
    public static function getMemberTypesForDropdown()
    {
        if (empty(self::$memberTypesForDropdown)) {
            self::$memberTypesForDropdown = array();

            foreach (CommonMembersModel::getMemberTypes() as $typeValue) {
                $type = new MemberType($typeValue);
                self::$memberTypesForDropdown[$type->getValue()] = $type->getLabel();
            }
        }

        return self::$memberTypesForDropdown;
    }

    /**
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $gender
     * @param $picture
     */
    public static function validateMember($id, $firstName, $lastName, $gender, $picture)
    {
        $member = new Member();

        $avatar = null;
        if (isset($picture)) {
            $avatarsPath = FRONTEND_FILES_PATH.'/Members/avatars';

            $fs = new Filesystem();
            $fs->mkdir(CommonMembersHelper::getAvatarsPaths());

            $extension = strstr(pathinfo($picture, PATHINFO_EXTENSION), '?', true);
            $avatar = uniqid().'.'.$extension;
            $avatarPath = $avatarsPath.'/source/'.$avatar;
            file_put_contents($avatarPath, file_get_contents($picture));

            CommonModel::generateThumbnails($avatarsPath, $avatarPath);
        }

        $member
            ->setId($id)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setGender($gender)
            ->setAvatar($avatar)
            ->save();
    }

    /**
     * @return mixed
     */
    public static function getDefaultType()
    {
        return CommonModel::getContainer()->get('fork.settings')->get('Members', 'default_type');
    }

    /**
     * @return mixed|null
     */
    public static function getDefaultGroup()
    {
        $type = self::getDefaultType();

        if (isset($type)) {
            return CommonModel::getContainer()->get('fork.settings')->get('Members', 'default_group_'.$type);
        }

        return null;
    }
}
