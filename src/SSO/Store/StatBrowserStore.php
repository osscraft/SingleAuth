<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\StatBrowser;
use Dcux\SSO\Core\Paging;
use Dcux\Util\Logger;

class StatBrowserStore extends AbstractStore {
    public function increase($args) {
        if (! $this->connection)
            return false;
        if(is_array($args) && !empty($args)) {
            $statBrowser = new StatBrowser();
            $cond = array();
            $info = array();
            $cond['browser'] = $info['browser'] = empty($args['browser']) ? '' : $args['browser'];
            $cond['version'] = $info['version'] = empty($args['version']) ? '' : $args['version'];
        } else {
            return false;
        }
        
        $table = $statBrowser->toTable();
        $fId = $statBrowser->toField('id');
        $fCount = $statBrowser->toField('count');
        //先查找，再更新
        $ret = $this->connection->select($table, array($fId), $cond);
        !empty($ret) && $row = $this->connection->toArray(1);
        if(!empty($row)) {
            $upd = "`$fCount` = `$fCount` + 1";
            $condition = array('id' => $row[0]['id']);
            $return = $this->connection->update($table, '', $upd, $condition);
        } else {
            $info['count'] = 1;
            $fields = array_keys($info);
            $return = $this->connection->insert($table, $fields, $info);
        }
        $success = $this->connection->toResult();
        return $success;
    }

    /**
     * 获取浏览器使用分布
     * @return multitype
     */
    public function readAll() {
        if(!$this->connection) return false;

        $stat = new StatBrowser();
        $table = $stat->toTable();
        //$sql = "SELECT * FROM $table ORDER BY `count` DESC";
        //$ret = $this->connection->query($sql);
        $ret = $this->connection->select($table, '*', array(), 'ORDER BY `count` DESC');
        if ($ret) {
            return $this->connection->toArray();
        } else {
            return array();
        }
    }
}

// PHP END