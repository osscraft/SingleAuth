<?php

namespace Dcux\Admin\Action\Ajax\Stat;

use Autoloader;
use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;
use Dcux\Admin\Kernel\MultiDatePermission;
use Dcux\SSO\Core\MemSession;
use Dcux\SSO\Kernel\Security;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatService;

class Visit extends MultiDatePermission
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
    protected function toDateData($ret, $date)
    {
        $stime = strtotime($date);
        $tmp = array();
        $data = array();
        foreach ($ret as $r) {
            $h = intval(date('H', strtotime($r['time'])));
            $tmp[$h] = empty($tmp[$h]) ? 0 : $tmp[$h];
            $tmp[$h] += 1;
        }
        for ($i=0; $i < 24; $i++) {
            //$hour = strtotime($date) + $i*3600;
            $h = $i;
            $tmp[$h] = empty($tmp[$h]) ? 0 : $tmp[$h];
        }
        ksort($tmp);
        /*uksort($tmp, function($a, $b) {
            return $a > $b ? 1 : ($a < $b ? -1 : 0);
        });*/
        foreach ($tmp as $k => $v) {
            $k = $k < 10 ? "0$k":"$k";
            $t = strtotime("$date $k:00:00");
            $data[] = array($t * 1000, $v);
        }
        return $data;
    }
    protected function toMonthData($ret, $startDate, $endDate, $period)
    {
        $data = array();
        $tmp = array();
        $stime = strtotime($startDate);
        $etime = strtotime($endDate);
        foreach ($ret as $v) {
            $d = intval(date('d', strtotime($v['date'])));
            $c = intval($v['count']);
            $tmp[$d] = $c;
        }
        for ($i=0; $i < $period; $i++) {
            //$d = $stime + $i * 86400;//第$i天
            $d = $i + 1;
            $tmp[$d] = empty($tmp[$d]) ? 0 : $tmp[$d];
        }
        ksort($tmp);
        foreach ($tmp as $k => $v) {
            $t = $stime + ($k -1) * 86400;
            //$t = strtotime("$date $k:00:00");
            $data[] = array($t * 1000, $v);
        }
        return $data;
    }
    protected function toPeriodData($ret, $startDate, $endDate, $period)
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
                    $data[$j][0]=$data[$j-1][0]+86400*1000;
                }
                $data[$j][1]=0;
                $j++;
                $startdate+=86400*1000;
            }
            $data[$j]=array();
            $data[$j][0]=$date;
            $data[$j][1]=intval($v['count']);
            $startdate+=86400*1000;
            $j++;
        }
        while ($j<$period) {
            $data[$j]=array();
            $data[$j][0]=$startdate;
            $data[$j][1]=0;
            $startdate+=86400*1000;
            $j++;
        }
        return $data;
    }
    protected function loadVisitors($client_id)
    {
        list($startDate, $endDate, $period, $type) = $this->detectDatetime();

        $data = array();
        $cond = array();
        $cond['startDate'] = $startDate;
        $cond['endDate'] = $endDate;
        $cond['client_id']=$client_id;
        if ($type == 'date') {
            $ret = $this->statService->getStatCountClientDaily($startDate, $client_id);
            $data = $this->toDateData($ret, $startDate);
            $this->template->push('ret', $ret);
        } elseif ($type == 'month') {
            $ret = StatClientService::clientsCount($cond, $period);
            $data = $this->toMonthData($ret, $startDate, $endDate, $period);
        } elseif ($type == 'year' || $type == 'period') {
            $ret = StatClientService::clientsCount($cond, $period);
            $data = $this->toPeriodData($ret, $startDate, $endDate, $period);
        }
        // push data
        $this->template->push('code', 0);
        $this->template->push('data', $data);
    }
}
// PHP END
