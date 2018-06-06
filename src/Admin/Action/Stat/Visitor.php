<?php

namespace Dcux\Admin\Action\Stat;

use Autoloader;
use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;

use Dcux\Admin\Kernel\AjaxPermission;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatService;

class Visitor extends AjaxPermission
{
    protected $statService;
    protected $statClientService;
    public function onCreate()
    {
        parent::onCreate();
        $this->statService = StatService::getInstance();
        $this->statClientService = StatClientService::getInstance();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        $client_id = empty($_REQUEST['client_id']) ? '' : $_REQUEST['client_id'];
        /*if(!$client_id){
            $this->template->push('code', 501002);
            $this->template->push('error', 'invalid client id');
        }else{*/
        $this->loadVisitors($client_id);
        //}
    }
    protected function detectDatetime()
    {
        $year = empty($_REQUEST['year']) ? '' : $_REQUEST['month'];
        $month = empty($_REQUEST['month']) ? '' : $_REQUEST['month'];
        $date = empty($_REQUEST['date']) ? '' : $_REQUEST['date'];
        $cyear = date('Y');
        $cmonth = date('m');
        $cdate = date('d');
        if ((empty($year) || $year >= $cyear) && empty($month) && empty($date)) {
            // 本年
             // include
            $startDate = empty($_REQUEST['startDate']) ? "$cyear-01-01" : $_REQUEST['startDate'];//date('Y-m-d', time() - 86400 * $period + 86400)
            // include
            $endDate = empty($_REQUEST['endDate']) ? "$cyear-$cmonth-$cdate" : $_REQUEST['endDate'];
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400);
        //$period = empty($_REQUEST['period']) ? 30 : intval($_REQUEST['period']);
        //} else if(!empty($_REQUEST['year']) && $_REQUEST['year'] >= date('Y') && empty($_REQUEST['month']) && empty($_REQUEST['date'])) {
        } elseif (empty($month) && empty($date)) {
            $startDate = "$year-01-01";
            $endDate = "$yea-12-31";
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400);
        } elseif (empty($year) && empty($month)) {
            //date
            $startDate = "$cyear-$cmonth-$date";
            $endDate = $startDate;
            $period = 1;
        } elseif (empty($year) && empty($date)) {
            $startDate = "$cyear-$month-01";
            if ($month == 12) {
                $endDate = "$cyear-12-31";
            } else {
                $nmonth = $month + 1;
                $endDate = date('Y-m-d', strtotime("$cyear-$nmonth-01") - 86400);
            }
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400);
        } elseif (empty($year)) {
            $startDate = "$cyear-$month-$date";
            $endDate = $startDate;
            $period = 1;
        } elseif (empty($month)) {
            $startDate = "$year-$cmonth-$date";
            $endDate = $startDate;
            $period = 1;
        } elseif (empty($date)) {
            $startDate = "$year-$month-01";
            if ($month == 12) {
                $endDate = "$year-12-31";
            } else {
                $nmonth = $month + 1;
                $endDate = date('Y-m-d', strtotime("$year-$nmonth-01") - 86400);
            }
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400);
        }
        return array($startDate, $endDate, $period);
    }
    protected function toDates($ret, $startDate, $endDate, $period)
    {
        $data = array();
        $j=0;
        $date=0;
        $startdate=strtotime($startDate)*1000;
        $enddate=strtotime($endDate)*1000;
        foreach ($ret as $k => $v) {
            $date=strtotime(date($v['date']))*1000;
            while ($startdate!=$date) {
                $data[$j]=array();
                if ($j==0) {
                    $data[$j][0]=$startdate;
                } else {
                    $data[$j][0]=$data[$j-1][0]+24*3600*1000;
                }
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
        while ($j<$period) {
            $data[$j]=array();
            $data[$j][0]=$startdate;
            $data[$j][1]=0;
            $startdate+=24*3600*1000;
            $j++;
        }
        return $data;
    }
    protected function loadVisitors($client_id)
    {
        /*$period = empty($_REQUEST['period']) ? 30 : intval($_REQUEST['period']);
         // include
        $startDate = empty($_REQUEST['startDate']) ? date('Y-m-d', time() - 86400 * $period + 86400) : $_REQUEST['startDate'];
        // include
        $endDate = empty($_REQUEST['endDate']) ? date('Y-m-d') : $_REQUEST['endDate'];*/
        // include
        /*$startDate = empty($_REQUEST['startDate']) ? date('Y').'-01-01' : $_REQUEST['startDate'];//date('Y-m-d', time() - 86400 * $period + 86400)
        // include
        $endDate = empty($_REQUEST['endDate']) ? date('Y-m-d') : $_REQUEST['endDate'];
        $period = ceil((strtotime($endDate) - strtotime($startDate))/86400);*/
        list($startDate, $endDate, $period) = $this->detectDatetime();


        $cond = array();
        $cond['startDate'] = $startDate;
        $cond['endDate'] = $endDate;
        $cond['client_id']=$client_id;
        if ($startDate == $endDate) {
            $data = $this->statService->getStatCountClientDaily($startDate, $client_id);
        //$data = array();
        } else {
            $ret = StatClientService::clientsCount($cond, $period);
            $data = $this->toDates($ret, $startDate, $endDate, $period);
        }
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
}
// PHP END
