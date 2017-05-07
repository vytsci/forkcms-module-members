<?php

namespace Common\Modules\Members\Engine;

use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;

use Common\Core\Model as CommonModel;
use Common\Modules\Members\Entity\Address;
use Common\Modules\Members\Entity\Group;
use Common\Modules\Members\Entity\GroupLocale;
use Common\Modules\Members\Entity\Member;
use Common\Modules\Members\Entity\MemberType;
use Common\Modules\Members\Entity\Pending;

/**
 * Class Model
 * @package Common\Modules\Members\Engine
 */
class Model
{

    const TBL_MEMBERS = 'members';

    const TBL_PENDING = 'members_pending';

    const TBL_REQUISITES = 'members_requisites';

    const TBL_GROUPS = 'members_groups';

    const TBL_GROUPS_RIGHTS = 'profiles_groups_rights';

    const TBL_GROUPS_LOCALE = 'members_groups_locale';

    const TBL_ADDRESSES = 'members_addresses';

    const QRY_ENTITY_MEMBER =
        'SELECT
            m.*,
            p.email,
            p.display_name,
            p.url
        FROM members AS m
        INNER JOIN profiles AS p ON p.id = m.id
        WHERE m.id = ?';

    const QRY_ENTITY_PENDING = 'SELECT mp.* FROM members_pending AS mp WHERE mp.id = ? AND mp.token = ?';

    const QRY_ENTITY_REQUISITES =
        'SELECT mr.* FROM members_requisites AS mr WHERE mr.member_id = ? ORDER BY mr.created_on DESC LIMIT 1';

    const QRY_ENTITY_GROUP =
        'SELECT mg.*, pg.name AS identifier
        FROM members_groups AS mg
        INNER JOIN profiles_groups AS pg ON pg.id = mg.id
        WHERE mg.id = ?';

    const QRY_ENTITY_GROUP_LOCALE =
        'SELECT mgl.* FROM members_groups_locale AS mgl WHERE mgl.id = ? AND mgl.language = ?';

    const QRY_ENTITY_ADDRESS = 'SELECT ma.* FROM members_addresses AS ma WHERE ma.id = ?';

    const QRY_DG_MEMBERS =
        'SELECT m.id, m.first_name, m.last_name, ma.phone, p.email, p.status, pgr.group_id
        FROM members AS m
        INNER JOIN profiles AS p ON p.id = m.id
        LEFT JOIN members_addresses AS ma ON ma.member_id = m.id AND ma.primary = 1
        LEFT JOIN profiles_groups_rights AS pgr ON pgr.profile_id = m.id
        GROUP BY m.id';

    const QRY_COUNT_MEMBERS =
        'SELECT
        COUNT(m.id)
        FROM members AS m
        INNER JOIN profiles AS p ON p.id = m.id';

    const QRY_LIST_MEMBERS =
        'SELECT
        m.*, p.*
        FROM members AS m
        INNER JOIN profiles AS p ON p.id = m.id';

    const QRY_DG_PENDING = 'SELECT mp.* FROM members_pending AS mp';

    const QRY_DG_REQUISITES =
        'SELECT m.first_name, m.last_name, p.email, mr.* FROM members_requisites AS mr
        INNER JOIN members AS m ON m.id = mr.member_id
        INNER JOIN profiles AS p ON p.id = m.id
        ORDER BY mr.created_on DESC';

    const QRY_LIST_MEMBERS_GROUPS =
        'SELECT
        pgr.*, mg.*, mgl.*
        FROM profiles_groups_rights AS pgr
        INNER JOIN members_groups AS mg ON mg.id = pgr.group_id
        INNER JOIN profiles_groups AS pg ON pg.id = mg.id
        INNER JOIN members_groups_locale AS mgl ON mgl.id = mg.id AND mgl.language = ?';

    const QRY_DG_GROUPS =
        'SELECT mg.id, pg.name AS identifier, mgl.title
        FROM members_groups AS mg
        INNER JOIN profiles_groups AS pg ON pg.id = mg.id
        LEFT JOIN members_groups_locale AS mgl ON mgl.id = mg.id AND mgl.language = ?';

    const QRY_DG_ADDRESSES = 'SELECT ma.* FROM members_addresses AS ma';

    /**
     * @var array
     */
    private static $memberTypes = array();

    /**
     * @var array
     */
    private static $requisitesTypes = array();

    /**
     * @var array
     */
    private static $approvedRequisites = array();

    /**
     * @param $query
     * @param $language
     * @param null $where
     * @return array
     */
    public static function getCount($query, $language, $where = null)
    {
        if ($where) {
            $query .= ' WHERE '.implode(' AND ', $where);
        }

        $result = (int)CommonModel::getContainer()->get('database')->getVar(
            $query,
            array($language)
        );

        return $result;
    }

    /**
     * @param $query
     * @param $language
     * @param null $where
     * @param null $order
     * @param null $limit
     * @return array
     */
    public static function getArray($query, $language, $where = null, $order = null, $limit = null)
    {
        if ($where) {
            $query .= ' WHERE '.implode(' AND ', $where);
        }

        if ($order) {
            $query .= ' ORDER BY '.implode(', ', $order);
        }

        if ($limit) {
            $query .= ' LIMIT '.$limit;
        }

        $result = (array)CommonModel::getContainer()->get('database')->getRecords(
            $query,
            array($language)
        );

        return $result;
    }

    /**
     * @param $id
     * @return bool
     */
    public static function existsMember($id)
    {
        return (bool)CommonModel::getContainer()->get('database')->getVar(
            'SELECT 1 FROM members AS m WHERE m.id = ? LIMIT 1',
            (int)$id
        );
    }

    /**
     * @param $id
     * @return Member
     */
    public static function getMember($id)
    {
        $record = (array)CommonModel::getContainer()->get('database')->getRecord(
            'SELECT m.*, p.* FROM members AS m
            INNER JOIN profiles AS p ON p.id = m.id
            WHERE m.id = ?',
            (int)$id
        );

        $member = new Member();
        $member->assemble($record);

        return $member;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getMemberAddresses($id, $language)
    {
        $result = array();

        $records = (array)CommonModel::getContainer()->get('database')->getRecords(
            'SELECT ma.*, gcl.name AS country, gsl.name AS state, gctl.name AS city
            FROM members_addresses AS ma
            LEFT JOIN geo_cities AS gct ON gct.id = ma.geo_city_id
            LEFT JOIN geo_cities_locale AS gctl ON gctl.id = gct.id AND gctl.language = ?
            LEFT JOIN geo_states AS gs ON gs.id = gct.state_id
            LEFT JOIN geo_states_locale AS gsl ON gsl.id = gs.id AND gsl.language = gctl.language
            LEFT JOIN geo_countries AS gc ON gc.id = gs.country_id
            LEFT JOIN geo_countries_locale AS gcl ON gcl.id = gc.id AND gcl.language = gsl.language
            WHERE ma.member_id = ?',
            array($language, (int)$id)
        );

        foreach ($records as $record) {
            $address = new Address();
            $address->assemble($record);
            $result[$address->getId()] = $address;
        }

        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getMemberAddressPrimary($id, $language)
    {
        $record = (array)CommonModel::getContainer()->get('database')->getRecord(
            'SELECT ma.*, gcl.name AS country, gsl.name AS state, gctl.name AS city FROM members_addresses AS ma
            LEFT JOIN geo_cities AS gct ON gct.id = ma.geo_city_id
            LEFT JOIN geo_cities_locale AS gctl ON gctl.id = gct.id AND gctl.language = ?
            LEFT JOIN geo_states AS gs ON gs.id = gct.state_id
            LEFT JOIN geo_states_locale AS gsl ON gsl.id = gs.id AND gsl.language = gctl.language
            LEFT JOIN geo_countries AS gc ON gc.id = gs.country_id
            LEFT JOIN geo_countries_locale AS gcl ON gcl.id = gc.id AND gcl.language = gsl.language
            WHERE ma.member_id = ? AND ma.primary = ?',
            array($language, (int)$id, true)
        );

        $address = new Address();
        $address->assemble($record);

        return $address;
    }

    /**
     * @param $id
     * @return array
     */
    public static function getMemberAddressBilling($id, $language)
    {
        $record = (array)CommonModel::getContainer()->get('database')->getRecord(
            'SELECT ma.*, gcl.name AS country, gsl.name AS state, gctl.name AS city
            FROM members_addresses AS ma
            LEFT JOIN geo_cities AS gct ON gct.id = ma.geo_city_id
            LEFT JOIN geo_cities_locale AS gctl ON gctl.id = gct.id AND gctl.language = ?
            LEFT JOIN geo_states AS gs ON gs.id = gct.state_id
            LEFT JOIN geo_states_locale AS gsl ON gsl.id = gs.id AND gsl.language = gctl.language
            LEFT JOIN geo_countries AS gc ON gc.id = gs.country_id
            LEFT JOIN geo_countries_locale AS gcl ON gcl.id = gc.id AND gcl.language = gsl.language
            WHERE ma.member_id = ? AND ma.billing = ?',
            array($language, (int)$id, true)
        );

        $address = new Address();
        $address->assemble($record);

        return $address;
    }

    /**
     * @param $item
     */
    public static function switchAddressPrimary($item)
    {
        CommonModel::getContainer()->get('database')->update(
            'members_addresses',
            array('primary' => 0),
            'id != ? AND member_id = ?',
            array((int)$item['id'], (int)$item['member_id'])
        );
    }

    /**
     * @param $item
     */
    public static function switchAddressBilling($item)
    {
        CommonModel::getContainer()->get('database')->update(
            'members_addresses',
            array('billing' => 0),
            'id != ? AND member_id = ?',
            array((int)$item['id'], (int)$item['member_id'])
        );
    }

    /**
     * @param $url
     * @return Member
     */
    public static function getGroupByUrl($url, $language)
    {
        $group = new Group();

        if (!isset($url)) {
            return $group;
        }

        $record = (array)CommonModel::getContainer()->get('database')->getRecord(
            'SELECT mg.* FROM members_groups AS mg
            INNER JOIN members_groups_locale AS mgl ON mgl.id = mg.id AND mgl.language = ?
            INNER JOIN meta AS m ON m.id = mgl.meta_id
            WHERE m.url = ?',
            array($language, $url)
        );

        $group->assemble($record, array($language));

        return $group;
    }

    /**
     * @param $id
     * @param $language
     * @return array
     * @throws \SpoonDatabaseException
     */
    public static function getMemberGroups($id, $language)
    {
        $result = array();

        $records = (array)CommonModel::getContainer()->get('database')->getRecords(
            'SELECT pgr.group_id FROM profiles_groups_rights AS pgr WHERE pgr.profile_id = ?',
            (int)$id
        );

        foreach ($records as $record) {
            $group = new Group(array($record['group_id']), array($language));
            $result[$group->getId()] = $group;
        }

        return $result;
    }

    /**
     * @return int
     */
    public static function getGroupsDefaultId()
    {
        $result = CommonModel::getContainer()->get('database')->getVar(
            'SELECT mg.id FROM members_groups AS mg WHERE mg.default = ? LIMIT 1',
            array(1)
        );

        return empty($result) ? null : $result;
    }

    /**
     *
     */
    public static function unsetGroupsDefault()
    {
        CommonModel::getContainer()->get('database')->execute(
            'UPDATE '.self::TBL_GROUPS.' SET `default` = 0'
        );
    }

    /**
     * @param $memberId
     * @param $groupsIds
     */
    public static function setGroupsForMember($memberId, $groupsIds)
    {
        $db = CommonModel::getContainer()->get('database');

        $groups = array();

        foreach ($groupsIds as $groupId) {
            $groups[] = array(
                'profile_id' => $memberId,
                'group_id' => $groupId,
            );
        }

        try {
            self::unsetGroupsForMember($memberId);
            $db->insert(self::TBL_GROUPS_RIGHTS, $groups);
        } catch (\Exception $e) {
            //@todo: log this error somewhere
        }
    }

    /**
     * @param $memberId
     */
    public static function unsetGroupsForMember($memberId)
    {
        CommonModel::getContainer()->get('database')->delete(
            self::TBL_GROUPS_RIGHTS,
            'profile_id = ?',
            array($memberId)
        );
    }

    /**
     * @return array
     */
    public static function getListMembersGroups($language)
    {
        $membersGroups = array();

        $groups = self::getArray(self::QRY_LIST_MEMBERS_GROUPS, array($language));

        foreach ($groups as $groupId => $group) {
            if (isset($group['profile_id'])) {
                $membersGroups[$group['profile_id']][$groupId] = $group;
            }
        }

        return $membersGroups;
    }

    /**
     * @return mixed
     */
    public static function getMemberTypes()
    {
        if (empty(self::$memberTypes)) {
            self::$memberTypes = CommonModel::getContainer()->get('database')->getEnumValues(
                self::TBL_MEMBERS,
                'type'
            );
        }

        return self::$memberTypes;
    }

    /**
     * @return mixed
     */
    public static function getRequisitesTypes()
    {
        if (empty(self::$requisitesTypes)) {
            self::$requisitesTypes = CommonModel::getContainer()->get('database')->getEnumValues(
                self::TBL_REQUISITES,
                'type'
            );
        }

        return self::$requisitesTypes;
    }

    /**
     * @param $type
     * @return bool
     */
    public static function isValidMemberType($type)
    {
        return in_array($type, self::getMemberTypes());
    }

    /**
     * @param $type
     * @return bool
     */
    public static function isValidRequisitesType($type)
    {
        return in_array($type, self::getMemberTypes());
    }

    /**
     * @param $id
     * @return mixed
     * @throws \SpoonDatabaseException
     */
    public static function hasApprovedRequisites($id)
    {
        if (!isset(self::$approvedRequisites[$id])) {
            $result = CommonModel::getContainer()->get('database')->getVar(
                'SELECT mr.status FROM members_requisites AS mr WHERE mr.member_id = ? ORDER BY mr.created_on DESC LIMIT 1',
                array($id)
            );

            self::$approvedRequisites[$id] = false;
            if ($result == 'approved') {
                self::$approvedRequisites[$id] = true;
            }
        }


        return self::$approvedRequisites[$id];
    }

    /**
     * @param $id
     * @return null|string
     * @throws \SpoonDatabaseException
     */
    public static function hasPendingRequisites($id)
    {
        $result = CommonModel::getContainer()->get('database')->getVar(
            'SELECT mr.status FROM members_requisites AS mr WHERE mr.member_id = ? ORDER BY mr.created_on DESC LIMIT 1',
            array($id)
        );

        if ($result == 'pending') {
            return true;
        }

        return false;
    }
}
