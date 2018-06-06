<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\AuthCode;
use Dcux\SSO\Core\Paging;

/**
 * 令牌处理类,与数据连接相关
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
class AuthCodeStore extends AbstractStore
{
    /**
     * 读取一条AuthCode
     */
    public function read($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthCode')) {
            $authCode = $args;
            $field1 = $authCode->toField('clientId');
            $field2 = $authCode->toField('username');
            $value1 = $authCode->getClientId();
            $value2 = $authCode->getUsername();
            $condition = array(
                    $field1 => $value1,
                    $field2 => $value2
            );
            unset($field1);
            unset($value1);
            unset($field2);
            unset($value2);
            unset($args);
        } elseif (is_array($args) || is_string($args)) {
            $authCode = new AuthCode();
            $condition = $args;
        } else {
            $authCode = new AuthCode();
            $condition = '';
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $authCode->toTable();
        $fields = $authCode->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $authCode = $authCode->rowToArray($rows[0]);
        return $authCode;
    }
    /**
     * 读取多条AuthCode
     */
    public function reads($args, $order = '', $paging = '')
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthCode')) {
            $authCode = $args;
            $field1 = $authCode->toField('clientId');
            $field2 = $authCode->toField('username');
            $value1 = $authCode->getClientId();
            $value2 = $authCode->getUsername();
            if ($value1 && $value2) {
                $condition = array(
                        $field1 => $value1,
                        $field2 => $value2
                );
            } elseif ($value1) {
                $condition = array(
                        $field1 => $value1
                );
            } elseif ($value2) {
                $condition = array(
                        $field2 => $value2
                );
            } else {
                $condition = '';
            }
            unset($field1);
            unset($value1);
            unset($field2);
            unset($value2);
            unset($args);
        } elseif (is_array($args) || is_string($args)) {
            $authCode = new AuthCode();
            $condition = $args;
        } else {
            $authCode = new AuthCode();
        }
        
        $table = $authCode->toTable();
        $fields = $authCode->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $authCodes = $authCode->rowsToArray($rows);
        unset($authCode);
        return $authCodes;
    }
    /**
     * AuthCode数
     */
    public function readCount($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthCode')) {
            $authCode = $args;
            $field1 = $authCode->toField('clientId');
            $field2 = $authCode->toField('username');
            $value1 = $authCode->getClientId();
            $value2 = $authCode->getUsername();
            if ($value1 && $value2) {
                $condition = array(
                        $field1 => $value1,
                        $field2 => $value2
                );
            } elseif ($value1) {
                $condition = array(
                        $field1 => $value1
                );
            } elseif ($value2) {
                $condition = array(
                        $field2 => $value2
                );
            } else {
                $condition = '';
            }
            unset($field1);
            unset($value1);
            unset($field2);
            unset($value2);
            unset($args);
        } elseif (is_array($args) || is_string($args)) {
            $authCode = new AuthCode();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $authCode->toTable();
        $result = $this->connection->count($table, $condition);
        unset($authCode);
        unset($table);
        unset($condition);
        return $result;
    }
    /**
     * 写入一条AuthCode
     */
    public function write($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthCode')) {
            $authCode = $args;
        } elseif (is_array($args)) {
            $authCode = new AuthCode();
            $return = $authCode->build($args);
        } else {
            return false;
        }
        
        $table = $authCode->toTable();
        $fields = $authCode->toFields();
        $values = $authCode->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 修改AuthCode
     */
    public function modify($args, $values = '')
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthCode')) {
            $authCode = $args;
            $values = $authCode->toValues();
            $field1 = $authCode->toField('clientId');
            $field2 = $authCode->toField('username');
            $value1 = $authCode->getClientId();
            $value2 = $authCode->getUsername();
            $condition = array(
                    $field1 => $value1,
                    $field2 => $value2
            );
        } elseif (is_array($args)) {
            if (empty($values)) {
                return false;
            }
            $authCode = new AuthCode();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $authCode->toTable();
        $fields = '';
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 删除AuthCode
     */
    public function remove($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthCode')) {
            $authCode = $args;
            $field1 = $authCode->toField('clientId');
            $field3 = $authCode->toField('username');
            $value1 = $authCode->getClientId();
            $value3 = $authCode->getUsername();
            $condition = array(
                    $field1 => $value1,
                    $field3 => $value3
            );
        } elseif (is_array($args)) {
            $authCode = new AuthCode();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $authCode->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 删除过期AuthCode
     */
    public function expires()
    {
        global $CFG;
        $authCode = new AuthCode();
        $field1 = $authCode->toField('expires');
        if ($field1) {
            if ($CFG['DATA_TYPE'] == $CFG['DATA_MEMORY']) {
                $condition = $field1;
            } elseif ($CFG['DATA_TYPE'] == $CFG['DATA_MYSQL']) {
                $condition = 'WHERE ' . $field1 . ' < UNIX_TIMESTAMP()';
            }
        } else {
            $condition = 'WHERE 1 = 0';
        }
        
        $table = $authCode->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
}
