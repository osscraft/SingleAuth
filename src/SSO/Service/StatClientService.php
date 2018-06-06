<?php
namespace Dcux\SSO\Service;

use Lay\Advance\Core\Service;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Paging;

use Dcux\SSO\Model\StatClient;

class StatClientService extends Service
{
    private $statClient;
    public static function getInstance()
    {
        $instance = parent::getInstance();
        return $instance;
    }
    public function model()
    {
        $this->statClient = StatClient::getInstance();
        return $this->statClient;
    }
    public static function addByDay($args='', $succ = 0)
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            if ($args['clientId']&&$args['date']) {
                $clientId=$args['clientId'];
                $date=$args['date'];
            } else {
                return false;
            }
        } else {
            return false;
        }
        $ret = $instance->model()->query(array(), array('clientId'=>$clientId,'date'=>$date));
        if ($ret) {
            $id=$ret[0]['id'];
            if ($succ) {
                $condition=array('count'=>$ret[0]['count']+1);
            } else {
                $condition=array('countVisit'=>$ret[0]['countVisit']+1);
            }
            return $instance->upd($id, $condition);
        } else {
            if ($succ) {
                return $instance->add(array('date'=>date('Y-m-d'),'clientId'=>$clientId,'count'=>1,'countVisit'=>1));
            } else {
                return $instance->add(array('date'=>date('Y-m-d'),'clientId'=>$clientId,'countVisit'=>1));
            }
        }
    }
    public static function readStatClientPaging()
    {
        $p = new Paging();
        return $p->build($_REQUEST);
    }
    public static function readStatClientTotal($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readStatClientPaging();
        $ret = self::getInstance()->count();
        return empty($ret) ? 0 : $ret;
    }
    public static function readStatClientList($paging = array())
    {
        $paging = $paging instanceof Paging ? $paging : self::readStatClientPaging();
        return self::getInstance()->getStatClientListPaging(array(), array('id' => 'ASC'), $paging->toLimit());
    }
    public function getStatClientListPaging($condition = array(), $order = array(), $limit = array())
    {
        $ret = $this->model()->db()->select(array(), $condition, $order, $limit, false);
        return empty($ret) ? array() : $ret;
    }
    public static function counts($args='')
    {
        $instance=self::getInstance();
        if (is_array($args)) {
            $condition=$args;
        } else {
            return false;
        }
        return $instance->count($condition);
    }
    public static function clientsCount($args='', $num=10)
    {
        $instance=self::getInstance();
        if (is_array($args)&&$args['startDate']&&$args['endDate']) {
            $startDate=$args['startDate'];
            $endDate=$args['endDate'];
            //if($startDate>$endDate)return false;
            if (!empty($args['client_id'])) {
                $client_id=$args['client_id'];
                $fields=array(
                    'client_id' => 'clientId',
                    'date' => 'date',
                    'count' => 'count'
                );
                $condition=array(
                    'client_id'=>"$client_id",
                    'date' =>array(array($startDate,$endDate),'BETWEEN')
                );
                $limit=array(0,$num);
                $ret=$instance->model()->query($fields, $condition, '', $limit);
            //$sql="SELECT `client_id`,`date`,`count` FROM `$table` WHERE `client_id`='$client_id' AND `date` >='$startDate' AND `date`<='$endDate' LIMIT 0,$num";
            } else {
                $table=$instance->model()->table();
                $sql="SELECT `date` AS `date`, sum(`count`) AS `count` FROM `$table` WHERE `date`>='$startDate' AND `date`<='$endDate' GROUP BY `date` ORDER BY `date` ASC LIMIT 0,$num";
                $ret = $instance->querys($sql);
            }
            if ($ret) {
                return $ret;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    public static function readPeriodByClient($clientId, $period = 7, $include_today = true)
    {
        if (empty($clientId)) {
            return array();
        }
        $instance=self::getInstance();
        $table=$instance->model()->table();
        if (! empty($include_today)) {
            $date = date('Y-m-d');
            $date_offset = date('Y-m-d', time() - 86400 * $period);
        } else {
            $date = date('Y-m-d', time() - 86400);
            $date_offset = date('Y-m-d', time() - 86400 * ($period + 1));
        }
        $sql = "SELECT SUM(`count`) as count, SUM(`count_visit`) as count_visit FROM $table WHERE `client_id` = '$clientId' AND `date` >= '$date_offset' AND `date` <= '$date'";
        $ret = $instance->querys($sql);
        if ($ret) {
            //$ret = $this->connection->toArray(1);
            return $ret[0];
        } else {
            return array();
        }
    }
    
    public function getClientTop($num = 5, $condition = array())
    {
        if ($num < 1 || empty($condition['startDate']) || empty($condition['endDate'])) {
            return array();
        }
        $startDate = $condition['startDate'];
        $endDate = $condition['endDate'];
        $table=$this->model()->table();
        $sql="SELECT `client_id`, sum(`count`) AS `count` FROM `$table` WHERE `date`>='$startDate' AND `date`<='$endDate' GROUP BY `client_id` ORDER BY `count` DESC LIMIT 0,$num";
        $ret = $this->querys($sql);
        return $ret;
    }
}
