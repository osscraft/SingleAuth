<?php

namespace Dcux\Portal\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Utility;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Paging;

use Dcux\Portal\Kernel\PAction;
use Dcux\SSO\Service\StatUserDetailService;
use Dcux\SSO\Service\ClientService;

class Log extends PAction {
    public function onGet() {
		$client_id=empty($_REQUEST['client_id']) ? '' : $_REQUEST['client_id'];
        $_REQUEST['pageSize'] = empty($_REQUEST['rp']) ? '' : $_REQUEST['rp'];
        $query = empty($_REQUEST['query']) ? '' : $_REQUEST['query'];
        $qtype = empty($_REQUEST['qtype']) ? '' : $_REQUEST['qtype'];
        $uid = empty($_SESSION['uid']) ? '' : $_SESSION['uid'];
        $out = array ();
        $group = array ();
        $rows = array ();
        if (! $uid) {
            // echo json_encode($out);
            return false;
        } else {
            $_REQUEST['username'] = $uid;
        }
		$paging=StatUserDetailService::readSUDetailPaging();
		if($client_id){
			$arr['clientId']=$client_id;
		} 
		if ($query && $qtype == 'time') {
            $_REQUEST[$qtype] = $query;
			$arr['username']=$uid;
			$arr['time']=array($query,'LIKE');
        } else if ($query && $qtype && $qtype != 'time') {
            if ($qtype == 'clientName') {
				$client=ClientService::read(array (
                        'clientName' => $query 
                ));
				if($client_id){
					$arr['clientId']=$client_id;
				}else{
					$arr['clientId']=$client['clientId'];
				}
            } else if ($qtype == 'success') {
				$arr['success']=($query == '是') ? 1 : 0;
            } else if ($qtype == 'ip') {
                $arr['ip'] = Utility::ipton($query);
            } else {
                $arr[$qtype] = $query;
            }
			if(empty($_SESSION['user']['isAdmin'])){
				$arr['username']=$uid;
			}
        } else {
			if(empty($_SESSION['user']['isAdmin'])){
				$arr['username']=$uid;
			}
        }
		if(empty($arr)){
			$arr=array();
		}
		$total = StatUserDetailService::counts($arr);
        
		$paging->count = $total;
		$logUsers=StatUserDetailService::getInstance()->getSUDetailListPaging($arr,array('id' => 'DESC'),$paging->toLimit());
		$page=$paging->toPaging();
        foreach ( $logUsers as $k => $logUser ) {
            $clientId = $logUser['clientId'];
            $group[$clientId] = $clientId;
        }
        
        //$clients = ClientManager::readClientsByGroup($group);
		$clients=ClientService::readClientsByGroup($group);
        foreach ( $logUsers as $k => $logUser ) {
            $clientId = $logUser['clientId'];
            $cell['id'] = $logUser['id'];
            $cell['time'] = $logUser['time'];
            $cell['clientName'] = $clients[$clientId]['clientName'];
            $cell['username'] = $logUser['username'];
            //$cell['facilityHost'] = Utility::ntoip(0 + $logUser['facilityHost']);
            $cell['success'] = ($logUser['success']) ? '是' : '否';
            $cell['ip'] = Utility::ntoip(0 + $logUser['ip']);
            $cell['os'] = $logUser['os'];
            $cell['browser'] = $logUser['browser'];
            $logUsers[$k]['cell'] = $cell;
        }      
        $result['page'] = $page['page']['page'];
        $result['rows'] = $logUsers;
        $result['logUsers'] = $logUsers;
        $result['clients'] = $clients;
        $result['paging'] = $page;
        //$result['total'] = $page['pages']['count'];
		$result['total'] = $total;
		/*var_dump($page);
		exit;*/
        $this->template->push($result);
    }
    public function onPost() {
        $this->onGet();
    }
}
// PHP END