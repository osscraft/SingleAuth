<?php

namespace Dcux\Portal\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;

use Dcux\Portal\Kernel\PAction;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\StatClientService;

class Visitor extends PAction{
	public function onCreate() {
        parent::onCreate();
    }
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        $client_id = empty($_REQUEST['client_id']) ? false : $_REQUEST['client_id'];
		$this->template->push('code', $client_id);
		if(!$client_id){
			
		}else{
			$this->loadVisitors($client_id);
		}
    }
	protected function loadVisitors($client_id){
		 $period = empty($_REQUEST['period']) ? 30 : intval($_REQUEST['period']);
		 // include 
        $startDate = empty($_REQUEST['startDate']) ? date('Y-m-d', time() - 86400 * $period + 86400) : $_REQUEST['startDate'];
        // include
        $endDate = empty($_REQUEST['endDate']) ? date('Y-m-d') : $_REQUEST['endDate'];
		$cond = array();
        $cond['startDate'] = $startDate;
        $cond['endDate'] = $endDate;
		$cond['client_id']=$client_id;
		$ret=StatClientService::clientsCount($cond,$period);
        $data = array();
		$j=0;
		$date=0;
		$startdate=strtotime($startDate)*1000;
		$enddate=strtotime($endDate)*1000;
        foreach ($ret as $k => $v) {
			$date=strtotime(date($v['date']))*1000;
			while($startdate!=$date){
				$data[$j]=array();
				if($j==0){$data[$j][0]=$startdate;}
				else{$data[$j][0]=$data[$j-1][0]+24*3600*1000;}
				$data[$j][1]=0;
				$j++;
				$startdate+=24*3600*1000;
			}
			$data[$j]=array();
			$data[$j][0]=$date;
			$data[$j][1]=$v['count'];
			$startdate+=24*3600*1000;
			$j++;
        }
		while($j<$period){
			$data[$j]=array();
			$data[$j][0]=$startdate;
			$data[$j][1]=0;
			$startdate+=24*3600*1000;
			$j++;
		}
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
	}

}
// PHP END