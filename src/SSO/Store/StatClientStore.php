<?php

namespace Dcux\SSO\Store;

use Dcux\SSO\Core\AbstractStore;
use Dcux\SSO\Model\StatClient;
use Dcux\SSO\Core\Paging;
use Dcux\Util\Logger;

class StatClientStore extends AbstractStore {
    /**
     */
    public function addByDay($args, $succ = 0) {
        // return 'a.'.$succ;
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\StatClient')) {
            $statClient = $args;
        } else if (is_array($args)) {
            $statClient = new StatClient();
            $return = $statClient->build($args);
        } else {
            return false;
        }
        $field1 = $statClient->toField('date');
        $value1 = $statClient->getDate();
        $field2 = $statClient->toField('clientId');
        $value2 = $statClient->getClientId();
        $statClients = $this->get($args);
        // return $statClients;
        if ($statClients) {
            // return $statClients[0];
            $success = $this->modify($statClients[0], $succ);
            // return 'b.'.$succ;
            return $success;
        } else {
            $success = $this->insert($statClient, $succ);
            return $success;
        }
    }
    public function get($args, $order = '', $paging = '') {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\StatClient')) {
            $statClient = $args;
            $field1 = $statClient->toField('id');
            $value1 = $statClient->getId();
            if ($value1) {
                $condition = array (
                        $field1 => $value1 
                );
            } else {
                $condition = '';
            }
        } else if (is_array($args) || is_string($args)) {
            $statClient = new StatClient();
            $condition = $args;
        } else {
            $statClient = new StatClient();
            $condition = '';
        }
        $table = $statClient->toTable();
        $fields = $statClient->toFields();
        $order = is_string($order) ? $order : '';
        $limit = is_a($paging, 'Dcux\SSO\Core\Paging') ? $paging->toLimit() : (is_string($paging) ? $paging : '');
        $return = $this->connection->select($table, $fields, $condition, $order, $limit);
        $rows = $this->connection->toArray();
        // return $rows;
        /*
         * if(!$rows){
         * return null;
         * }
         */
        // $statUsers=$statClient->rowToArray($rows);
        unset($statClient);
        return $rows;
    }
    public function modify($args, $succ = 0) {
        if (! $this->connection)
            return false;
            // return $args;
        if (is_array($args)) {
            $statClient = new StatClient();
            $statClient->build($args);
            $field1 = $statClient->toField('id');
            $value1 = $statClient->getId();
            $field2 = $statClient->toField('count');
            $field3 = $statClient->toField('count_visit');
            $condition = array (
                    $field1 => $value1 
            );
            // return $statClient;
            if ($succ == 1) {
                $values = array (
                        $field2 => ($args['count'] + 1) 
                );
            } else {
                // return false;
                $values = array (
                        $field3 => ($args['count_visit'] + 1) 
                );
                // $values=array($field3=>($args['count_visit']+1));
            }
            // return $values;
        } else {
            return false;
        }
        $table = $statClient->toTable();
        $fields = '';
        $return = $this->connection->update($table, $fields, $values, $condition);
        $success = $this->connection->toResult();
        return $success;
    }
    public function insert($args, $succ) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\StatClient')) {
            $statClient = $args;
        } else if (is_array($args)) {
            $statClient = new StatClient();
            $return = $statClient->build($args);
        } else {
            return false;
        }
        if ($succ)
            $statClient->setCount(1);
        $statClient->setCountVisit(1);
        // return $statClient;
        $table = $statClient->toTable();
        $fields = $statClient->toFields();
        $values = $statClient->toValues();
        $return = $this->connection->insert($table, $fields, $values);
        $success = $this->connection->toResult();
        return $success;
    }
    public function readCount($args) {
        if (! $this->connection)
            return false;
        if (is_a($args, 'Dcux\SSO\Model\StatClient')) {
            $statClient = $args;
            $condition = '';
        } else if (is_array($args) || is_string($args)) {
            $statClient = new StatClient();
            $condition = $args;
        } else {
            $statClient = new StatClient();
            $condition = '';
        }
        
        $table = $statClient->toTable();
        unset($statClient);
        $result = $this->connection->count($table, $condition);
        return $result;
    }
    /**
     * 获取指定周期内的客户端访问记录
     * @param string $clientId
     * @param number $period
     * @param boolean $include_today
     * @return multitype
     */
    public function readPeriodByClient($clientId, $period = 7, $include_today = true) {
        if (empty($clientId)) {
            return array ();
        }
        $statClient = new StatClient();
        $table = $statClient->toTable();
        if (! empty($include_today)) {
            $date = date('Y-m-d');
            $date_offset = date('Y-m-d', time() - 86400 * $period);
        } else {
            $date = date('Y-m-d', time() - 86400);
            $date_offset = date('Y-m-d', time() - 86400 * ($period + 1));
        }
        $sql = "SELECT SUM(`count`) as count, SUM(`count_visit`) as count_visit FROM $table WHERE `client_id` = '$clientId' AND `date` >= '$date_offset' AND `date` <= '$date'";
        $ret = $this->connection->query($sql);

        if ($ret) {
            $ret = $this->connection->toArray(1);
            return $ret[0];
        } else {
            return array ();
        }
    }
	/**
	*统计某天或某几天内客户端访问信息
	*@param mixed $args
    *@param number $num
	*@return multitype
	**/
	public function clientsCount($args='',$num=10){
		if(!$this->connection) return false;
		$statClient=new StatClient();
		$table = $statClient->toTable();
		if(is_array($args)&&$args['startDate']&&$args['endDate']){
			$startDate=$args['startDate'];
			$endDate=$args['endDate'];
			if($startDate>$endDate)return false;
			if($args['client_id']){
				$client_id=$args['client_id'];
				$sql="SELECT `client_id`,`date`,`count` FROM `$table` WHERE `client_id`='$client_id' AND `date` >='$startDate' AND `date`<='$endDate' LIMIT 0,$num";
			}else{
				$sql="SELECT `client_id`, sum(`count`) AS num FROM `$table` WHERE `date`>='$startDate' AND `date`<='$endDate' GROUP BY `client_id` ORDER BY `num` DESC LIMIT 0,$num";
			}	
			$ret = $this->connection->query($sql);
			if ($ret) {
				$ret = $this->connection->toArray();
				return $ret;
			} else {
				return array ();
			}
		}else{
			return array();
		}
	}
}
?>