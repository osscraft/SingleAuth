<?php

namespace Dcux\Cli\Action\Client;

use Dcux\Cli\Kernel\CronAction;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatClientService;

class Weekorder extends CronAction
{
    protected $statService;
    protected $clientService;
    protected $statClientService;
    public function onCreate()
    {
        parent::onCreate();
        $this->clientService = ClientService::getInstance();
        $this->statClientService = StatClientService::getInstance();
    }
    public function on()
    {
        // 一周一次
        $clientStats = $this->getClientStat();
        $clientIds = array();
        foreach ($clientStats as $clientStat) {
            $clientIds[] = $clientStat['client_id'];
        }
        $clients = $this->clientService->getClientListByUnique($clientIds);
        $ret = '';
        foreach ($clientStats as $clientStat) {
            $clientId = $clientStat['client_id'];
            $count = $clientStat['count'];
            $id = $clients[$clientId]['id'];
            $ret = $this->clientService->upd($id, array('clientOrderNum'=>$count));
            if (empty($ret)) {
                break;
            }
        }
        if ($ret) {
            $this->template->push("code", 0);
            $this->template->push("data", "done month client clientOrderNum");
        } else {
            $this->template->push("code", 900001);
            $this->template->push("data", "error month client clientOrderNum");
        }
    }
    protected function getClientStat()
    {
        $date = array();
        $date['endDate'] = date('Y-m-d', time() - 86400);
        $date['startDate'] = date('Y-m-d', time() - 8 * 86400);
        $count = $this->statClientService->counts(array());
        $ret = $this->statClientService->getClientTop($count, $date);
        return $ret;
    }
}
