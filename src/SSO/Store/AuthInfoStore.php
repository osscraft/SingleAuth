<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\AuthInfo;
use Dcux\SSO\Core\Paging;

/**
 * 授权信息处理类,与数据连接相关
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
class AuthInfoStore extends AbstractStore
{
    /**
     * 读取一条
     *
     * @param mixed $args
     * @return mixed
     */
    public function read($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthInfo')) {
            $authInfo = $args;
            $field1 = $authInfo->toField('id');
            $value1 = $authInfo->getId();
            $condition = array(
                    $field1 => $value1
            );
        } elseif (is_array($args) || is_string($args)) {
            $authInfo = new AuthInfo();
            $condition = $args;
        } else {
            $authInfo = new AuthInfo();
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $authInfo->toTable();
        $fields = $authInfo->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $authInfo = $authInfo->rowToArray($rows[0]);
        return $authInfo;
    }
    /**
     * 读取多条
     *
     * @param mixed $args
     *            AuthInfo,array,string
     * @param mixed $order
     *            string
     * @param mixed $paging
     *            Paging,string
     * @return mixed
     */
    public function reads($args, $order = '', $paging = '')
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthInfo')) {
            $authInfo = $args;
            $field1 = $authInfo->toField('clientId');
            $field2 = $authInfo->toField('username');
            $value1 = $authInfo->getClientId();
            $value2 = $authInfo->getUsername();
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
        } elseif (is_array($args) || is_string($args)) {
            $authInfo = new AuthInfo();
            $condition = $args;
        } else {
            $authInfo = new AuthInfo();
        }
        
        $table = $authInfo->toTable();
        $fields = $authInfo->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $authInfos = $authInfo->rowsToArray($rows);
        unset($authInfo);
        return $authInfos;
    }
    /**
     * 读取条的数目
     *
     * @param mixed $args
     *            AuthInfo,array,string
     * @return mixed
     */
    public function readCount($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthInfo')) {
            $authInfo = $args;
            $field1 = $authInfo->toField('clientId');
            $field2 = $authInfo->toField('username');
            $value1 = $authInfo->getClientId();
            $value2 = $authInfo->getUsername();
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
        } elseif (is_array($args) || is_string($args)) {
            $authInfo = new AuthInfo();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $authInfo->toTable();
        unset($authInfo);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    /**
     * 写入
     *
     * @param mixed $args
     *            AuthInfo,array,string
     * @return mixed
     */
    public function write($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthInfo')) {
            $authInfo = $args;
        } elseif (is_array($args)) {
            $authInfo = new AuthInfo();
            $return = $authInfo->build($args);
        } else {
            return false;
        }
        if (! $authInfo->getAuthTime()) {
            $authInfo->setAuthTime(time());
        }
        
        $table = $authInfo->toTable();
        $field1 = $authInfo->toField('id');
        $fields = $authInfo->toFields();
        $fields = array_diff($fields, array(
                $field1
        ));
        $values = $authInfo->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 更新
     *
     * @param mixed $args
     *            AuthInfo,array,string
     * @return mixed
     */
    public function modify($args, $values = '')
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthInfo')) {
            $authInfo = $args;
            $values = $authInfo->toValues();
            $field1 = $authInfo->toField('id');
            $value1 = $authInfo->getId();
            $condition = array(
                    $field1 => $value1
            );
        } elseif (is_array($args)) {
            if (empty($values)) {
                return false;
            }
            $authInfo = new AuthInfo();
            $condition = $args;
        } else {
            return false;
        }
        if (! $authInfo->getAuthTime()) {
            $authInfo->setAuthTime(time());
        }
        
        $table = $authInfo->toTable();
        $fields = '';
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    /**
     * 删除
     *
     * @param mixed $args
     *            AuthInfo,array,string
     * @return mixed
     */
    public function remove($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\AuthInfo')) {
            $authInfo = $args;
            $field1 = $authInfo->toField('id');
            $value1 = $authInfo->getId();
            $value2 = $authInfo->getUsername();
            $condition = array(
                    $field1 => $value1
            );
        } elseif (is_array($args)) {
            $authInfo = new AuthInfo();
            $condition = $args;
        } else {
            return false;
        }
        
        $table = $authInfo->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
}
