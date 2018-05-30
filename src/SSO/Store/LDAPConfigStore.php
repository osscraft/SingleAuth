<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\LDAPConfig;
use Dcux\SSO\Core\Paging;

/**
 * LDAP服务器配置处理类,与数据连接相关
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
class LDAPConfigStore extends AbstractStore {
    /**
     * 读取一条LDAPConfig
     *
     * @return mixed
     */
    public function readLDAPConfig($isObj = false) {
        if (! $this->connection)
            return;
        
        $ldapConfig = new LDAPConfig();
        $table = $ldapConfig->toTable();
        $fields = $ldapConfig->toFields();
        $condition = "";
        $order = "";
        $limit = "limit 1";
        
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $ldapConfig = $ldapConfig->rowToArray($rows[0]);
        
        return $ldapConfig;
    }
    /**
     * 读取多条LDAPConfig
     *
     * @return mixed
     */
    public function readLDAPConfigs($condition = '', $order = '', $paging = '', $isObj = false) {
        if (! $this->connection)
            return;
        
        $ldapConfig = new LDAPConfig();
        $table = $ldapConfig->toTable();
        $fields = $ldapConfig->toFields();
        $limit = (is_a($paging, 'Dcux\SSO\Core\Paging')) ? $paging->toLimit() : '';
        
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $ldapConfigs = $ldapConfig->rowsToArray($rows);
        
        return $ldapConfigs;
    }
    /**
     * 修改LDAPConfig信息
     *
     * @param LDAPConfig $ldapConfig            
     * @return boolean
     */
    public function modifyLDAPConfig($ldapConfig) {
        if (! $this->connection)
            return;
        if (! is_a($ldapConfig, 'Dcux\SSO\Model\LDAPConfig'))
            return;
        
        $table = $ldapConfig->toTable();
        $fields = $ldapConfig->toFields();
        $values = $ldapConfig->toValues();
        $condition = "";
        
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        
        return $success;
    }
    /**
     * 更新或初始化LDAPConfig信息
     *
     * @param LDAPConfig $ldapConfig            
     * @return boolean
     */
    public function updateLDAPConfig($ldapConfig) {
        if (! $this->connection)
            return;
        if (! is_a($ldapConfig, 'Dcux\SSO\Model\LDAPConfig'))
            return;
        
        $temp = $this->readLDAPConfig();
        
        if (! count($temp)) {
            $table = $ldapConfig->toTable();
            $fields = $ldapConfig->toFields();
            $values = $ldapConfig->toValues();
            
            $return = $this->connection->insert($table, $fields, $values);
            $success = $this->connection->toResult();
        } else {
            $success = $this->modifyLDAPConfig($ldapConfig);
        }
        
        return $success;
    }
}
?>
