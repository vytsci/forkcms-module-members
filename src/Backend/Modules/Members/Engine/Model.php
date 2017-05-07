<?php

namespace Backend\Modules\Members\Engine;

use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Language as BL;

use Common\Modules\Members\Engine\Model as CommonMembersModel;

/**
 * Class Model
 * @package Backend\Modules\Members\Engine
 */
class Model
{

    const QRY_REQUISITES_HISTORY =
        'SELECT * FROM members_requisites AS mr WHERE mr.member_id = ? ORDER BY mr.created_on DESC';

    /**
     * @var array
     */
    private static $typesForDropDown = array();

    /**
     * @var array
     */
    private static $groupsForDropDown = array();

    /**
     * @param $URL
     * @param null $id
     *
     * @return string
     */
    public static function getURLForGroup($URL, $id = null)
    {
        $URL = (string)$URL;
        $db = BackendModel::getContainer()->get('database');

        $parameters = array(BL::getWorkingLanguage(), $URL);

        if ($id !== null) {
            $parameters[] = $id;
        }

        if ((bool)$db->getVar(
            'SELECT 1
            FROM '.CommonMembersModel::TBL_GROUPS_LOCALE.' AS i
            INNER JOIN meta AS m ON i.meta_id = m.id
            WHERE
                i.language = ?
                AND m.url = ?
                '.($id !== null ? 'AND i.id != ?' : '').'
             LIMIT 1',
            $parameters
        )
        ) {
            $URL = BackendModel::addNumber($URL);

            return self::getURLForGroup($URL, $id);
        }

        return $URL;
    }

    /**
     * @return array
     */
    public static function getMemberTypesForDropdown()
    {
        if (empty(self::$typesForDropDown)) {
            self::$typesForDropDown = array();

            foreach (CommonMembersModel::getMemberTypes() as $type) {
                self::$typesForDropDown[$type] = BL::lbl(\SpoonFilter::toCamelCase($type));
            }
        }

        return self::$typesForDropDown;
    }

    /**
     * @return array
     */
    public static function getGroupsForDropDown()
    {
        if (empty(self::$groupsForDropDown)) {
            self::$groupsForDropDown = (array)BackendModel::getContainer()->get('database')->getPairs(
                'SELECT mg.id, mgl.title
                 FROM '.CommonMembersModel::TBL_GROUPS.' AS mg
                 LEFT JOIN '.CommonMembersModel::TBL_GROUPS_LOCALE.' AS mgl ON mgl.id = mg.id AND mgl.language = ?',
                array(BL::getWorkingLanguage())
            );
        }

        return self::$groupsForDropDown;
    }

    /**
     * @return array
     */
    public static function getGroupsByTypes()
    {
        $groupsByTypes = array();
        foreach (array_keys(self::getMemberTypesForDropdown()) as $type) {
            $typeGroupId = BackendModel::getContainer()
                ->get('fork.settings')
                ->get('Members', 'default_group_'.$type);
            if (isset($typeGroupId)) {
                $groupsByTypes[$type] = $typeGroupId;
            }
        }

        return $groupsByTypes;
    }

    /**
     * @param $query
     *
     * @return array
     */
    public static function getMembersForDataGrid($query)
    {
        $result = (array)BackendModel::getContainer()->get('database')->getRecords($query);

        return $result;
    }

    /**
     * @param $ids
     */
    public static function changeStatusRequisites($ids, $status)
    {
        if (empty($ids) || !is_array($ids) || !in_array($status, array('pending', 'approved', 'rejected'))) {
            return;
        }

        $db = BackendModel::getContainer()->get('database');

        $db->update(
            CommonMembersModel::TBL_REQUISITES,
            array('status' => $status),
            'id IN ('.implode(', ', $ids).')'
        );
    }

    /**
     * @param $id
     *
     * @return array
     * @throws \SpoonDatabaseException
     */
    public static function getMemberRequisitesHistory($id)
    {
        $result = (array)BackendModel::getContainer()->get('database')->getRecords(
            self::QRY_REQUISITES_HISTORY,
            array((int)$id)
        );

        return $result;
    }

    /**
     * @param $requisitesIds
     */
    public static function setAppointmentsBasedOnRequisites($requisitesIds)
    {
        $db = BackendModel::getContainer()->get('database');

        $requisites = (array)$db->getRecords(
            'SELECT * FROM members_requisites AS mr
            WHERE mr.id IN ('.implode(', ', array_fill(0, count($requisitesIds), '?')).')',
            $requisitesIds
        );

        $groupsByTypes = self::getGroupsByTypes();
        $profilesGroupsRights = array();
        foreach ($requisites as $requisite) {
            $db->update(
                CommonMembersModel::TBL_MEMBERS,
                array('type' => $requisite['type']),
                'id = ?',
                array($requisite['member_id'])
            );
            $db->delete(
                CommonMembersModel::TBL_GROUPS_RIGHTS,
                'profile_id = ? AND group_id = ?',
                array($requisite['member_id'], $groupsByTypes[$requisite['type']])
            );
            if ($groupsByTypes[$requisite['type']]) {
                $profilesGroupsRights[] = array(
                    'profile_id' => $requisite['member_id'],
                    'group_id' => $groupsByTypes[$requisite['type']],
                );
            }
        }

        $db->insert(CommonMembersModel::TBL_GROUPS_RIGHTS, $profilesGroupsRights);
    }
}
