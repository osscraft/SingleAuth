<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\StatUser;
use Dcux\SSO\Core\Paging;

class StatUserStore extends AbstractStore
{
    /**
     *
     */
    public function addByDay($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\StatUser')) {
            $statUser = $args;
        } elseif (is_array($args)) {
            $statUser = new StatUser();
            $return = $statUser->build($args);
        } else {
            return false;
        }
        // return $statUser;
        $field1 = $statUser->toField('date');
        $value1 = $statUser->getDate();
        $field2 = $statUser->toField('username');
        $value2 = $statUser->getUsername();
        $statUser1 = $this->get(array(
                $field1 => $value1,
                $field2 => $value2
        ));
        // return $statUser1;
        if ($statUser1) {
            $success = $this->modify($statUser1[0]);
            return $success;
        } else {
            $success = $this->insert($statUser);
            return 'insert=' . $success;
        }
    }
    public function get($args, $order = '', $paging = '')
    {
        if (! $this->connection) {
            return false;
        }
        // return $args;
        if (is_a($args, 'Dcux\SSO\Model\StatUser')) {
            $statUser = $args;
            $field1 = $statUser->toField('id');
            $value1 = $statUser->getId();
            if ($value1) {
                $condition = array(
                        $field1 => $value1
                );
            } else {
                $condition = '';
            }
        } elseif (is_array($args) || is_string($args)) {
            $statUser = new StatUser();
            // return $args;
            $condition = $args;
        } else {
            $statUser = new StatUser();
            $condition = '';
        }
        // return $condition;
        $table = $statUser->toTable();
        $fields = $statUser->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        unset($statUser);
        return $rows;
        $statUsers = $statUser->rowToArray($rows);
        
        return $statUsers;
    }
    public function modify($args)
    {
        if (! $this->connection) {
            return false;
        }
        // return $args;
        if (is_array($args)) {
            $statUser = new StatUser();
            $statUser->build($args);
            $field1 = $statUser->toField('id');
            $value1 = $statUser->getId();
            $field2 = $statUser->toField('count');
            $condition = array(
                    $field1 => $value1
            );
            $values = array(
                    $field2 => ($statUser->getCount() + 1)
            );
        } else {
            return false;
        }
        $table = $statUser->toTable();
        $fields = '';
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    public function insert($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\StatUser')) {
            $statUser = $args;
        } elseif (is_array($args)) {
            $statUser = new StatUser();
            $return = $statUser->build($args);
        } else {
            return false;
        }
        $statUser->setCount(1);
        $table = $statUser->toTable();
        $fields = $statUser->toFields();
        $values = $statUser->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    public function readCount($args)
    {
        if (! $this->connection) {
            return false;
        }
        if (is_a($args, 'Dcux\SSO\Model\StatUser')) {
            $statUser = $args;
            $condition = '';
        } elseif (is_array($args) || is_string($args)) {
            $user = new User();
            $condition = $args;
        } else {
            $user = new User();
            $condition = '';
        }
        
        $table = $statUser->toTable();
        unset($statUser);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
}
