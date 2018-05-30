<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\LogClient;
use Dcux\SSO\Core\Paging;

/**
 * 客户端验证日志信息处理类,与数据连接相关
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
class LogClientStore extends AbstractStore {
    /**
     * 读取一条LogClient
     *
     * @param LogClient $logClient            
     * @return mixed
     */
    public function read($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogClient')) {
            $logClient = $args;
            $field0 = $logClient->toField('uid');
            $field1 = $logClient->toField('clientId');
            $value0 = $logClient->getUid();
            $value1 = $logClient->getClientId();
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
            $logClient = new LogClient();
            $condition = $args;
        } else {
            $logClient = new LogClient();
            $condition = '';
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $logClient->toTable();
        $fields = $logClient->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $logClient = $logClient->rowToArray($rows[0]);
        return $logClient;
    }
    /**
     * 读取多条LogClient
     *
     * @return mixed
     */
    public function reads($args, $order = '', $paging = '', $field = '') {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogClient')) {
            $logClient = $args;
            $field0 = $logClient->toField('uid');
            $field1 = $logClient->toField('clientId');
            $value0 = $logClient->getUid();
            $value1 = $logClient->getClientId();
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
            $logClient = new LogClient();
            $condition = $args;
        } else {
            $logClient = new LogClient();
            $condition = '';
        }
        
        $table = $logClient->toTable();
        $fields = ($field) ? $field : $logClient->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $logClients = $logClient->rowsToArray($rows);
        unset($logClient);
        return $logClients;
    }
    /**
     * 客户端数
     */
    public function readCount($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\LogClient')) {
            $logClient = $args;
            $field0 = $logClient->toField('uid');
            $field1 = $logClient->toField('clientId');
            $value0 = $logClient->getUid();
            $value1 = $logClient->getClientId();
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
            $logClient = new LogClient();
            $condition = $args;
        } else {
            $logClient = new LogClient();
            $condition = '';
        }
        
        $table = $logClient->toTable();
        unset($logClient);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    /**
     * 删除$day天前的记录
     */
    public function removeByDay($day = 30) {
        if (! $this->connection)
            return false;
        $logClient = new LogClient();
        $field0 = $logClient->toField('timeReported');
        $day = ($day) ? (0 + $day) : 30;
        $time = time() - $day * 86400;
        $timeReported = date('Y-m-d H:i:s', $time);
        $condition = "WHERE `$field0` < '$timeReported'";
        
        $table = $logClient->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
}
?>
