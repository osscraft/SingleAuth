<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\User;
use Dcux\SSO\Core\Paging;

/**
 * 客户端信息处理类,与数据连接相关
 *
 * @category
 *
 * @package classes
 * @author liaiyong <liaiyong@dcux.com>
 * @version 1.0
 * @copyright 2005-2012 dcux Inc.
 * @link http://www.dcux.com
 *
 */
class UserStore extends AbstractStore
{
    /**
     * 读取一条User
     *
     * @param User $user
     * @return mixed
     */
    public function read($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\User')) {
            $user = $args;
            $field1 = $user->toField('uid');
            $value1 = $user->getUid();
            if ($value1) {
                $condition = array(
                        $field1 => $value1
                );
            } else {
                $condition = '';
            }
        } elseif (is_array($args) || is_string($args)) {
            $user = new User();
            $condition = $args;
        } else {
            $user = new User();
            $condition = '';
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $user->toTable();
        $fields = $user->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $user = $user->rowToArray($rows[0]);
        return $user;
    }
    /**
     * 读取多条User
     *
     * @return mixed
     */
    public function reads($args, $order = '', $paging = '')
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\User')) {
            $user = $args;
            $field1 = $user->toField('isAdmin');
            $value1 = $user->getIsAdmin();
            if ($value1) {
                $condition = array(
                        $field1 => $value1
                );
            } else {
                $condition = '';
            }
        } elseif (is_array($args) || is_string($args)) {
            $user = new User();
            $condition = $args;
        } else {
            $user = new User();
            $condition = '';
        }
        
        $table = $user->toTable();
        $fields = $user->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $users = $user->rowsToArray($rows);
        unset($user);
        return $users;
    }
    /**
     * 客户端数
     *
     * @return mixed
     */
    public function readCount($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\User')) {
            $user = $args;
            $condition = '';
        } elseif (is_array($args) || is_string($args)) {
            $user = new User();
            $condition = $args;
        } else {
            $user = new User();
            $condition = '';
        }
        
        $table = $user->toTable();
        unset($user);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    /**
     * 创建User
     *
     * @return mixed
     */
    public function write($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\User')) {
            $user = $args;
        } elseif (is_array($args)) {
            $user = new User();
            $return = $user->build($args);
        } else {
            return false;
        }
        
        $table = $user->toTable();
        $fields = $user->toFields();
        $values = $user->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    
    /**
     * 修改User
     *
     * @return mixed
     */
    public function modify($args, $values = '')
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\User')) {
            $user = $args;
            $values = $user->toValues();
            $field1 = $user->toField('uid');
            $value1 = $user->getUid();
            $condition = array(
                    $field1 => $value1
            );
        } elseif (is_array($args)) {
            if (empty($values)) {
                return false;
            }
            $user = new User();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $user->toTable();
        $fields = '';
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    
    /**
     * 删除User
     *
     * @return mixed
     */
    public function remove($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\User')) {
            $user = $args;
            $values = $user->toValues();
            $field1 = $user->toField('uid');
            $value1 = $user->getUid();
            $condition = array(
                    $field1 => $value1
            );
        } elseif (is_array($args)) {
            $user = new User();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $user->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
}
