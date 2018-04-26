<?php

namespace mdm\admin\components;

/**
 * DbManager represents an authorization manager that stores authorization information in database.
 *
 * The database connection is specified by [[$db]]. The database schema could be initialized by applying migration:
 *
 * ```
 * yii migrate --migrationPath=@yii/rbac/migrations/
 * ```
 *
 * If you don't want to use migration and need SQL instead, files for all databases are in migrations directory.
 *
 * You may change the names of the three tables used to store the authorization data by setting [[\yii\rbac\DbManager::$itemTable]],
 * [[\yii\rbac\DbManager::$itemChildTable]] and [[\yii\rbac\DbManager::$assignmentTable]].
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DbManager extends \yii\rbac\DbManager
{
    /**
     * @var string the name of the table storing authorization items. Defaults to "auth_item".
     */
    // public $itemTable = '{{%auth_item}}';
    public $itemTable = 'sys_auth_item';
    /**
     * @var string the name of the table storing authorization item hierarchy. Defaults to "auth_item_child".
     */
    // public $itemChildTable = '{{%auth_item_child}}';
    public $itemChildTable = 'sys_auth_item_child';
    /**
     * @var string the name of the table storing authorization item assignments. Defaults to "auth_assignment".
     */
    // public $assignmentTable = '{{%auth_assignment}}';
    public $assignmentTable = 'sys_auth_assignment';
    /**
     * @var string the name of the table storing rules. Defaults to "auth_rule".
     */
    // public $ruleTable = '{{%auth_rule}}';
    public $ruleTable = 'sys_auth_rule';

    /**
     * Memory cache of assignments
     * @var array
     */
    private $_assignments = [];
    private $_childrenList;
    
    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if (!isset($this->_assignments[$userId])) {
            $this->_assignments[$userId] = parent::getAssignments($userId);
        }
        return $this->_assignments[$userId];
    }

    /**
     * @inheritdoc
     */
    protected function getChildrenList()
    {
        if ($this->_childrenList === null) {
            $this->_childrenList = parent::getChildrenList();
        }
        return $this->_childrenList;
    }
}
