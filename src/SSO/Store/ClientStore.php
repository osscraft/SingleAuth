<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\Client;

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
class ClientStore extends AbstractStore {
    /**
     * 读取一条Client
     *
     * @param Client $client            
     * @return mixed
     */
    public function read($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Client')) {
            $client = $args;
            $field0 = $client->toField('id');
            $field1 = $client->toField('clientId');
            $field2 = $client->toField('clientSecret');
            $field3 = $client->toField('clientType');
            $value0 = $client->getId();
            $value1 = $client->getClientId();
            $value2 = $client->getClientSecret();
            $value3 = $client->getClientType();
            if ($value0) {
                $condition = array (
                        $field0 => $value0 
                );
            } else if ($value1 && $value2) {
                $condition = array (
                        $field1 => $value1,
                        $field2 => $value2,
                        $field3 => $value3 
                );
            } else if ($value1) {
                $condition = array (
                        $field1 => $value1,
                        $field3 => $value3 
                );
            } else {
                $condition = '';
            }
        } else if (is_array($args) || is_string($args)) {
            $client = new Client();
            $condition = $args;
        } else {
            $client = new Client();
            $condition = '';
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $client->toTable();
        $fields = $client->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $client = $client->rowToArray($rows[0]);
        return $client;
    }
    /**
     * 读取多条Client
     *
     * @return mixed
     */
    public function reads($args, $order = '', $paging = '', $field = '') {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Client')) {
            $client = $args;
            $field0 = $client->toField('id');
            $field1 = $client->toField('clientId');
            $field2 = $client->toField('clientSecret');
            $field3 = $client->toField('clientType');
            $value0 = $client->getId();
            $value1 = $client->getClientId();
            $value2 = $client->getClientSecret();
            $value3 = $client->getClientType();
            if ($value0) {
                $condition = array (
                        $field0 => $value0 
                );
            } else if ($value1 && $value2) {
                $condition = array (
                        $field1 => $value1,
                        $field2 => $value2 
                );
            } else if ($value1) {
                $condition = array (
                        $field1 => $value1 
                );
            } else {
                $condition = '';
            }
        } else if (is_array($args) || is_string($args)) {
            $client = new Client();
            $condition = $args;
        } else {
            $client = new Client();
            $condition = '';
        }
        
        $table = $client->toTable();
        $fields = ($field) ? $field : $client->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $clients = $client->rowsToArray($rows);
        unset($client);
        return $clients;
    }
    /**
     * 客户端数
     */
    public function readCount($args = array()) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Client')) {
            $client = $args;
            $condition = '';
        } else if (is_array($args) || is_string($args)) {
            $client = new Client();
            $condition = $args;
        } else {
            $client = new Client();
            $condition = '';
        }
        
        $table = $client->toTable();
        unset($client);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    /**
     * 创建Client
     */
    public function write($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Client')) {
            $client = $args;
        } else if (is_array($args)) {
            $client = new Client();
            $return = $client->build($args);
        } else {
            return false;
        }
        
        $table = $client->toTable();
        $fields = $client->toFields();
        $values = $client->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 修改Client
     */
    public function modify($args, $values = '') {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Client')) {
            $client = $args;
            $values = $client->toValues();
            $field0 = $client->toField('id');
            $field1 = $client->toField('clientId');
            $field2 = $client->toField('clientSecret');
            $value0 = $client->getId();
            $value1 = $client->getClientId();
            $value2 = $client->getClientSecret();
            if ($value0) {
                $condition = array (
                        $field0 => $value0 
                );
            } else {
                $condition = array (
                        $field1 => $value1,
                        $field2 => $value2 
                );
            }
        } else if (is_array($args)) {
            if (empty($values))
                return false;
            $client = new Client();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $client->toTable();
        $fields = '';
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 删除Client
     */
    public function remove($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Client')) {
            $client = $args;
            $field0 = $client->toField('id');
            $field1 = $client->toField('clientId');
            $field2 = $client->toField('clientSecret');
            $value0 = $client->getId();
            $value1 = $client->getClientId();
            $value2 = $client->getClientSecret();
            if ($value0) {
                $condition = array (
                        $field0 => $value0 
                );
            } else {
                $condition = array (
                        $field1 => $value1,
                        $field2 => $value2 
                );
            }
        } else if (is_array($args)) {
            $client = new Client();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $client->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    
    /**
     * 读取可在首页产显示所有Client信息
     */
    public function readsByShow($order = '', $paging = '') {
        $client = new Client();
        $isShowF = $client->toField('clientIsShow');
        $condition = "WHERE `$isShowF` > '0'";
        $order = $order ? $order : "ORDER BY `$isShowF` DESC";
        $fields = array_diff($client->toFields(), array (
                $client->toField('clientSecret') 
        ));
        $result = $this->reads($condition, $order, $paging, $fields);
        return $result;
    }
    /**
     * 通过角色加载在首页显示的客户端列表
     */
    public function readsByRole($role = 0, $order = '', $paging = '') {
        $client = new Client();
        $fIsShow = $client->toField('clientIsShow');
        $fVisible = $client->toField('clientVisible');
        $fOrderNum = $client->toField('clientOrderNum');
        $role = intval($role);
        $condition = "WHERE `$fIsShow` > '0' AND `$fVisible` IN ('0', '$role')";
        $order = $order ? $order : "ORDER BY `$fOrderNum` DESC, `$fIsShow` DESC, `id` DESC";
        $fields = array_diff($client->toFields(), array (
                $client->toField('clientSecret') 
        ));
        $result = $this->reads($condition, $order, $paging, $fields);
        return $result;
    }
    /**
     * 读取可在首页产显示部分Client信息
     */
    public function readsByWord($word, $order = '', $paging = '') {
        $client = new Client();
        $clientId = $client->toField('clientId');
        $clientIsShow = $client->toField('clientIsShow');
        $clientName = $client->toField('clientName');
        $clientDescribe = $client->toField('clientDescribe');
        $word = addslashes($word);
        $condition = "WHERE $clientId LIKE '%$word%' OR ( $clientName LIKE '%$word%' )"; // OR $clientDescribe LIKE '%$word%'
        $fields = array_diff($client->toFields(), array (
                $client->toField('clientSecret') 
        ));
        $result = $this->reads($condition, $order, $paging, $fields);
        return $result;
    }
    public function readsByGroup($group, $order = '', $paging = '') {
        if (! is_array($group)) {
            return false;
        }
        $vars = '';
        foreach ( $group as $k => $item ) {
            if ($vars === '') {
                $vars .= '\'';
                $vars .= addslashes($item);
                $vars .= '\'';
            } else {
                $vars .= ',\'';
                $vars .= addslashes($item) ;
                $vars .= '\'';
            }
        }
        $group = array_map('addslashes', array_values($group));
        $vars = "'" . implode("', '", $group) . "'";
        // $vars = implode(',',$group);
        $client = new Client();
        $clientId = $client->toField('clientId');
        $condition = "WHERE $clientId IN ( $vars )";
        $fields = array_diff($client->toFields(), array (
                $client->toField('clientSecret') 
        ));
        $result = $this->reads($condition, $order, $paging, $fields);
        return $result;
    }
    public function updateOrderNum($client_id, $order_num) {
        if (empty($client_id)) {
            return false;
        }
        $client = new Client();
        $condition = array (
                'client_id' => $client_id 
        );
        $table = $client->toTable();
        $fields = array (
                'order_num' 
        );
        $values = array (
                'order_num' => $order_num 
        );
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
}
?>
