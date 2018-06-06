<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\StatUserDetail;
use Dcux\SSO\Core\Paging;

/**
 * �ͻ�����֤��־��Ϣ������,������������
 */
class StatUserDetailStore extends AbstractStore
{
    /**
     * ��ȡһ��LogClient
     *
     * @param StatUserDetail $StatUserDetail
     * @return mixed
     */
    public function read($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\StatUserDetail')) {
            $statUserDetail = $args;
            $field0 = $statUserDetail->toField('id');
            $field1 = $statUserDetail->toField('clientId');
            $value0 = $statUserDetail->getId();
            $value1 = $statUserDetail->getClientId();
            if ($value0 && $value1) {
                $condition = array(
                        $field0 => $value0,
                        $field1 => $value1
                );
            } elseif ($value0) {
                $condition = array(
                        $field0 => $value0
                );
            } else {
                $condition = '';
            }
        } elseif (is_array($args) || is_string($args)) {
            $statUserDetail = new StatUserDetail();
            $condition = $args;
        } else {
            $statUserDetail = new StatUserDetail();
            $condition = '';
        }
        
        $order = '';
        $limit = 'LIMIT 1';
        $table = $statUserDetail->toTable();
        $fields = $statUserDetail->toFields();
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray(1);
        $statUserDetail = $statUserDetail->rowToArray($rows[0]);
        return $statUserDetail;
    }
    /**
     * ��ȡ����LogClient
     *
     * @return mixed
     */
    public function reads($args, $order = '', $paging = '', $field = '')
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\StatUserDetail')) {
            $statUserDetail = $args;
            $field0 = $statUserDetail->toField('id');
            $field1 = $statUserDetail->toField('clientId');
            $value0 = $statUserDetail->getId();
            $value1 = $statUserDetail->getClientId();
            if ($value0 && $value1) {
                $condition = array(
                        $field0 => $value0,
                        $field1 => $value1
                );
            } elseif ($value0) {
                $condition = array(
                        $field0 => $value0
                );
            } else {
                $condition = '';
            }
        } elseif (is_array($args) || is_string($args)) {
            $statUserDetail = new statUserDetail();
            $condition = $args;
        } else {
            $statUserDetail = new statUserDetail();
            $condition = '';
        }
        
        $table = $statUserDetail->toTable();
        $fields = ($field) ? $field : $statUserDetail->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        $statUserDetails = $statUserDetail->rowsToArray($rows);
        unset($statUserDetail);
        return $statUserDetails;
    }
    /**
     * �ͻ�����
     */
    public function readCount($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\StatUserDetail')) {
            $statUserDetail = $args;
            $field0 = $statUserDetail->toField('id');
            $field1 = $statUserDetail->toField('clientId');
            $value0 = $statUserDetail->getId();
            $value1 = $statUserDetail->getClientId();
            if ($value0 && $value1) {
                $condition = array(
                        $field0 => $value0,
                        $field1 => $value1
                );
            } elseif ($value0) {
                $condition = array(
                        $field0 => $value0
                );
            } else {
                $condition = '';
            }
        } elseif (is_array($args) || is_string($args)) {
            $statUserDetail = new StatUserDetail();
            $condition = $args;
        } else {
            $statUserDetail = new StatUserDetail();
            $condition = '';
        }
        
        $table = $statUserDetail->toTable();
        unset($statUserDetail);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    public function write($args)
    {
        if (! $this->connection) {
            return false;
        }
        // return $args;
        if (is_a($args, 'Dcux\SSO\Model\StatUserDetail')) {
            $statUserDetail = $args;
        } elseif (is_array($args)) {
            $statUserDetail = new StatUserDetail();
            $return = $statUserDetail->build($args);
        } else {
            return false;
        }
        $table = $statUserDetail->toTable();
        $fields = $statUserDetail->toFields();
        $values = $statUserDetail->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    public function removeByDay($day = 30)
    {
        if (! $this->connection) {
            return false;
        }
        $logClient = new StatUserDetail();
        $field0 = $logClient->toField('time');
        $day = ($day) ? (0 + $day) : 30;
        $time = time() - $day * 86400;
        $condition = "WHERE `$field0` < '$time'";
        
        $table = $logClient->toTable();
        $return = $this->connection->delete($table, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
}
