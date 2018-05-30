<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\Token;
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
class TokenStore extends AbstractStore {
    /**
     * 读取一条Token
     */
    public function read($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Token')) {
            $token = $args;
            $field1 = $token->toField('clientId');
            $field2 = $token->toField('username');
            $value1 = $token->getClientId();
            $value2 = $token->getUsername();
            $condition = array (
                    $field1 => $value1,
                    $field2 => $value2 
            );
            unset($field1);
            unset($value1);
            unset($field2);
            unset($value2);
            unset($args);
        } else if (is_array($args) || is_string($args)) {
            $token = new Token();
            $condition = $args;
        } else {
            $token = new Token();
            $condition = '';
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $token->toTable();
        $fields = $token->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $token = $token->rowToArray($rows[0]);
        return $token;
    }
    /**
     * 读取多条Token
     */
    public function reads($args, $order = '', $paging = '') {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Token')) {
            $token = $args;
            $field1 = $token->toField('clientId');
            $field2 = $token->toField('username');
            $value1 = $token->getClientId();
            $value2 = $token->getUsername();
            if ($value1 && $value2)
                $condition = array (
                        $field1 => $value1,
                        $field2 => $value2 
                );
            else if ($value1)
                $condition = array (
                        $field1 => $value1 
                );
            else if ($value2)
                $condition = array (
                        $field2 => $value2 
                );
            else
                $condition = '';
            
            unset($field1);
            unset($value1);
            unset($field2);
            unset($value2);
            unset($args);
        } else if (is_array($args) || is_string($args)) {
            $token = new Token();
            $condition = $args;
        } else {
            $token = new Token();
        }
        
        $table = $token->toTable();
        $fields = $token->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $tokens = $token->rowsToArray($rows);
        unset($token);
        return $tokens;
    }
    /**
     * Token数
     */
    public function readCount($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Token')) {
            $token = $args;
            $field1 = $token->toField('clientId');
            $field2 = $token->toField('username');
            $value1 = $token->getClientId();
            $value2 = $token->getUsername();
            if ($value1 && $value2)
                $condition = array (
                        $field1 => $value1,
                        $field2 => $value2 
                );
            else if ($value1)
                $condition = array (
                        $field1 => $value1 
                );
            else if ($value2)
                $condition = array (
                        $field2 => $value2 
                );
            else
                $condition = '';
            unset($field1);
            unset($value1);
            unset($field2);
            unset($value2);
            unset($args);
        } else if (is_array($args) || is_string($args)) {
            $token = new Token();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $token->toTable();
        $result = $this->connection->count($table, $condition);
        unset($token);
        unset($table);
        unset($condition);
        return $result;
    }
    /**
     * 创建Token
     */
    public function write($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Token')) {
            $token = $args;
        } else if (is_array($args)) {
            $token = new Token();
            $return = $token->build($args);
        } else {
            return false;
        }
        
        $table = $token->toTable();
        $fields = $token->toFields();
        $values = $token->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 修改Token
     */
    public function modify($args, $values = '') {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Token')) {
            $token = $args;
            $values = $token->toValues();
            $field1 = $token->toField('clientId');
            $field2 = $token->toField('username');
            $value1 = $token->getClientId();
            $value2 = $token->getUsername();
            $condition = array (
                    $field1 => $value1,
                    $field2 => $value2 
            );
        } else if (is_array($args)) {
            if (empty($values))
                return false;
            $token = new Token();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $token->toTable();
        $fields = '';
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 删除Token
     */
    public function remove($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\Token')) {
            $token = $args;
            $field1 = $token->toField('clientId');
            $field3 = $token->toField('username');
            $value1 = $token->getTokenId();
            $value3 = $token->getUsername();
            $condition = array (
                    $field1 => $value1,
                    $field3 => $value3 
            );
        } else if (is_array($args)) {
            $token = new Token();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $token->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 删除过期token
     */
    public function expires() {
        global $CFG;
        $token = new Token();
        $field1 = $token->toField('expires');
        if ($field1) {
            if ($CFG['DATA_TYPE'] == $CFG['DATA_MEMORY']) {
                $condition = 'expires';
            } else if ($CFG['DATA_TYPE'] == $CFG['DATA_MYSQL']) {
                $condition = 'WHERE ' . $field1 . ' < UNIX_TIMESTAMP()';
            }
        } else {
            $condition = 'WHERE 1 = 0';
        }
        
        $table = $token->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
}
?>
