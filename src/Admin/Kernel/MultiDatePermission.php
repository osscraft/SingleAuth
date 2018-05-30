<?php


namespace Dcux\Admin\Kernel;

use Lay\Advance\Core\Action;
use Lay\Advance\Core\Configuration;

use Dcux\Admin\Kernel\AjaxPermission;

abstract class MultiDatePermission extends AjaxPermission {
    protected function detectDatetime() {
        $year = empty($_REQUEST['year']) ? '' : $_REQUEST['year'];
        $month = empty($_REQUEST['month']) ? '' : $_REQUEST['month'];
        $date = empty($_REQUEST['date']) ? '' : $_REQUEST['date'];
        $cyear = date('Y');
        $cmonth = date('m');
        $cdate = date('d');
        if((empty($year) || $year >= $cyear) && empty($month) && empty($date)) {
            // 本年
             // include 
            $startDate = empty($_REQUEST['startDate']) ? "$cyear-01-01" : date('Y-m-d', strtotime($_REQUEST['startDate']));//date('Y-m-d', time() - 86400 * $period + 86400)
            // include
            $endDate = empty($_REQUEST['endDate']) ? "$cyear-$cmonth-$cdate" : date('Y-m-d', strtotime($_REQUEST['endDate']));
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400) + 1;
            $type = 'period';
            //$period = empty($_REQUEST['period']) ? 30 : intval($_REQUEST['period']);
        //} else if(!empty($_REQUEST['year']) && $_REQUEST['year'] >= date('Y') && empty($_REQUEST['month']) && empty($_REQUEST['date'])) {
        } else if(empty($month) && empty($date)) {
            $startDate = "$year-01-01";
            $endDate = "$year-12-31";
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400) + 1;
            $type = 'year';
        } else if(empty($year) && empty($month)) {
            //date 
            $startDate = date('Y-m-d', strtotime("$cyear-$cmonth-$date"));
            $endDate = $startDate;
            $period = 1;
            $type = 'date';
        } else if(empty($year) && empty($date)) {
            $startDate = date('Y-m-d', strtotime("$cyear-$month-01"));
            if($month == 12) {
                $endDate = "$cyear-12-31";
            } else {
                $nmonth = $month + 1;
                $endDate = date('Y-m-d', strtotime("$cyear-$nmonth-01") - 86400);
            }
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400) + 1;
            $type = 'month';
        } else if(empty($year)) {
            $startDate = date('Y-m-d', strtotime("$cyear-$month-$date"));
            $endDate = $startDate;
            $period = 1;
            $type = 'date';
        } else if(empty($month)) {
            $startDate = date('Y-m-d', strtotime("$year-$cmonth-$date"));
            $endDate = $startDate;
            $period = 1;
            $type = 'date';
        } else if(empty($date)) {
            $startDate = date('Y-m-d', strtotime("$year-$month-01"));
            if($month == 12) {
                $endDate = "$year-12-31";
            } else {
                $nmonth = $month + 1;
                $endDate = date('Y-m-d', strtotime("$year-$nmonth-01") - 86400);
            }
            $period = ceil((strtotime($endDate) - strtotime($startDate))/86400) + 1;
            $type = 'month';
        } else {
            $startDate = "$year-$month-$date";
            $endDate = $startDate;
            $period = 1;
            $type = 'date';
        }
        return array($startDate, $endDate, $period, $type);
    }
}

// PHP END