<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\LogUser;
use Dcux\SSO\Core\Paging;

/**
 * 用户验证日志处理类,与数据连接相关
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
class LogUserStore extends AbstractStore {
    /**
     * 读取一条LogUser
     *
     * @param LogUser $logUser            
     * @return mixed
     */
    public function read($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field0 = $logUser->toField('uid');
            $field1 = $logUser->toField('clientId');
            $value0 = $logUser->getUid();
            $value1 = $logUser->getClientId();
            if ($value0 && $value1) {
                $condition = array (
                        $field0 => $value0,
                        $field1 => $value1 
                );
            } else if ($value0) {
                $condition = array (
                        $field0 => $value0 
                );
            } else {
                $condition = '';
            }
        } else if (is_array($args) || is_string($args)) {
            $logUser = new LogUser();
            $condition = $args;
        } else {
            $logUser = new LogUser();
            $condition = '';
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $logUser->toTable();
        $fields = $logUser->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $logUser = $logUser->rowToArray($rows[0]);
        return $logUser;
    }
    /**
     * 读取多条LogUser
     *
     * @return mixed
     */
    public function reads($args, $order = '', $paging = '', $field = '') {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field0 = $logUser->toField('uid');
            $field1 = $logUser->toField('clientId');
            $value0 = $logUser->getUid();
            $value1 = $logUser->getClientId();
            if ($value0 && $value1) {
                $condition = array (
                        $field0 => $value0,
                        $field1 => $value1 
                );
            } else if ($value0) {
                $condition = array (
                        $field0 => $value0 
                );
            } else {
                $condition = '';
            }
        } else if (is_array($args) || is_string($args)) {
            $logUser = new LogUser();
            $condition = $args;
        } else {
            $logUser = new LogUser();
            $condition = '';
        }
        
        $table = $logUser->toTable();
        $fields = ($field) ? $field : $logUser->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $logUsers = $logUser->rowsToArray($rows);
        unset($logUser);
        return $logUsers;
    }
    /**
     * 删除$day天前的记录
     */
    public function removeByDay($day = 30) {
        if (! $this->connection)
            return false;
        $logUser = new LogUser();
        $field0 = $logUser->toField('timeReported');
        $day = ($day) ? (0 + $day) : 30;
        $time = time() - $day * 86400;
        $timeReported = date('Y-m-d H:i:s', $time);
        $condition = "WHERE `$field0` < '$timeReported'";
        
        $table = $logUser->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 客户端数
     */
    public function readCount($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field1 = $logUser->toField('username');
            $value1 = $logUser->getUsername();
            if ($value1) {
                $condition = array (
                        $field1 => $value1 
                );
            } else {
                return false;
            }
        } else if (is_array($args) || is_string($args)) {
            $logUser = new LogUser();
            $condition = $args;
        } else {
            $logUser = new LogUser();
            $condition = '';
        }
        
        $table = $logUser->toTable();
        unset($logUser);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    public function readsByUser($args, $order, $paging = '', $field = '') {
        if (! $this->connection)
            return false;
        if (! $args)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field1 = $logUser->toField('username');
            $value1 = $logUser->getUsername();
            $condition = array (
                    $field1 => $value1 
            );
        } else if (is_array($args) || is_string($args)) {
            $logUser = new LogUser();
            $condition = $args;
        } else {
            return false;
        }
        
        $odrfid = $logUser->toField('timeReported');
        $table = $logUser->toTable();
        $order = ($order) ? $order : " ORDER BY $odrfid DESC ";
        $fields = ($field) ? $field : $logUser->toFields();
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $logUsers = $logUser->rowsToArray($rows);
        unset($logUser);
        return $logUsers;
    }
    public function readCountTime($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field1 = $logUser->toField('username');
            $field2 = $logUser->toField('timereported');
            $value1 = $logUser->getUsername();
            $value2 = $logUser->getTimereported();
            if ($value1 && $value2) {
                $value2 = mysqli_real_escape_string($this->connection, $value2);
                $condition = " WHERE `$field1` = '$value1' AND `$field2` LIKE '%$value2%' ";
            } else {
                return false;
            }
        } else {
            return false;
        }
        
        $table = $logUser->toTable();
        unset($logUser);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    public function readsByTime($args, $order, $paging = '', $field = '') {
        if (! $this->connection)
            return false;
        if (! $args)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field1 = $logUser->toField('username');
            $field2 = $logUser->toField('timereported');
            $value1 = $logUser->getUsername();
            $value2 = $logUser->getTimereported();
            if ($value1 && $value2) {
                $value2 = mysqli_real_escape_string($this->connection, $value2);
                $condition = " WHERE `$field1` = '$value1' AND `$field2` LIKE '%$value2%' ";
            } else {
                return false;
            }
        } else {
            return false;
        }
        
        $odrfid = $logUser->toField('timeReported');
        $table = $logUser->toTable();
        $order = ($order) ? $order : " ORDER BY $odrfid DESC ";
        $fields = ($field) ? $field : $logUser->toFields();
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $logUsers = $logUser->rowsToArray($rows);
        unset($logUser);
        return $logUsers;
    }
    public function readCountOther($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field0 = $logUser->toField('username');
            $field1 = $logUser->toField('clientId');
            $field2 = $logUser->toField('success');
            $field3 = $logUser->toField('ip');
            $field4 = $logUser->toField('os');
            $field5 = $logUser->toField('browser');
            $value0 = $logUser->getUsername();
            $value1 = $logUser->getClientId();
            $value2 = $logUser->getSuccess();
            $value3 = $logUser->getIp();
            $value4 = $logUser->getOs();
            $value5 = $logUser->getBrowser();
            if ($value0 && $value1) {
                $condition = array (
                        $field0 => $value0,
                        $field1 => $value1 
                );
            } else if ($value0 && $value2) {
                $condition = array (
                        $field0 => $value0,
                        $field2 => $value2 
                );
            } else if ($value0 && $value3) {
                $condition = array (
                        $field0 => $value0,
                        $field3 => $value3 
                );
            } else if ($value0 && $value4) {
                $condition = array (
                        $field0 => $value0,
                        $field4 => $value4 
                );
            } else if ($value0 && $value5) {
                $condition = array (
                        $field0 => $value0,
                        $field5 => $value5 
                );
            } else {
                return false;
            }
        } else {
            return false;
        }
        
        $table = $logUser->toTable();
        unset($logUser);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    public function readsByOther($args, $order, $paging = '', $field = '') {
        if (! $this->connection)
            return false;
        if (! $args)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogUser')) {
            $logUser = $args;
            $field0 = $logUser->toField('username');
            $field1 = $logUser->toField('clientId');
            $field2 = $logUser->toField('success');
            $field3 = $logUser->toField('ip');
            $field4 = $logUser->toField('os');
            $field5 = $logUser->toField('browser');
            $value0 = $logUser->getUsername();
            $value1 = $logUser->getClientId();
            $value2 = $logUser->getSuccess();
            $value3 = $logUser->getIp();
            $value4 = $logUser->getOs();
            $value5 = $logUser->getBrowser();
            if ($value0 && $value1) {
                $condition = array (
                        $field0 => $value0,
                        $field1 => $value1 
                );
            } else if ($value0 && $value2) {
                $condition = array (
                        $field0 => $value0,
                        $field2 => $value2 
                );
            } else if ($value0 && $value3) {
                $condition = array (
                        $field0 => $value0,
                        $field3 => $value3 
                );
            } else if ($value0 && $value4) {
                $condition = array (
                        $field0 => $value0,
                        $field4 => $value4 
                );
            } else if ($value0 && $value5) {
                $condition = array (
                        $field0 => $value0,
                        $field5 => $value5 
                );
            } else {
                return false;
            }
        } else {
            return false;
        }
        
        $odrfid = $logUser->toField('timeReported');
        $table = $logUser->toTable();
        $order = ($order) ? $order : " ORDER BY $odrfid DESC ";
        $fields = ($field) ? $field : $logUser->toFields();
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $logUsers = $logUser->rowsToArray($rows);
        unset($logUser);
        return $logUsers;
    }
}
?>
