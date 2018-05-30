<?php

namespace Dcux\Portal\Action;

use Lay\Advance\Core\App;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Configuration;

use Dcux\Portal\Kernel\PAction;
use Dcux\SSO\Service\UserElectionService;
use Dcux\SSO\Service\ClientService;

class Election extends PAction {
    protected $userElectionService;
    protected $clientService;
    public function onCreate() {
        parent::onCreate();
        $this->userElectionService = UserElectionService::getInstance();
        $this->clientService = ClientService::getInstance();
    }
    public function onGet() {
        $this->onPost();
    }
    public function onPost() {
        if (! empty($_SESSION['uid'])) {
            $key = empty($_REQUEST['key']) ? 'list' : $_REQUEST['key'];
            switch ($key) {
                case 'add':
                    $this->doAdd();
                    break;
                case 'del':
                    $this->doDel();
                    break;
                case 'list':
                default:
                    $this->doList();
                    break;
            }
        } else {
            $this->template->push('code', 1);
            $this->template->push('error', 'no_permission');
        }
    }
    protected function doList() {
        $out = array();
        $ids = array();
        $uid = $_SESSION['uid'];
        $elections = $this->userElectionService->getUserElectionListAllByUser($uid);
        foreach ($elections as $el) {
            $ids[] = $el['clientId'];
        }
        $out["list"] = $this->clientService->getClientListByUnique($ids, false);
        $out["total"] = count($out["list"]);
        $out["since"] = '0';

        $rsp = array();
        $rsp['code'] = 0;
        $rsp['data'] = $out;
        $this->template->push($rsp);
    }
    protected function doAdd() {
        $uid = $_SESSION['uid'];
        $client_id = empty($_REQUEST['client_id']) ? false : $_REQUEST['client_id'];
        if(!empty($client_id)) {
            $valid = $this->clientService->getByUnique($client_id);
            if(!empty($valid)) {
                $info = array();
                $info['uid'] = $uid;
                $info['clientId'] = $client_id;
                $info['time'] = date('Y-m-d H:i:s');
                $ret = $this->userElectionService->replace($info);
                if(!empty($ret)) {
                    $this->template->push('code', 0);
                    $this->template->push('data', $ret);
                } else {
                    $this->template->push('code', 3);
                    $this->template->push('data', 'add_failure');
                }
            } else {
                $this->template->push('code', 2);
                $this->template->push('error', 'invalid_client');
            }
        } else {
            $this->template->push('code', 1);
            $this->template->push('error', 'empty_client_id');
        }
    }
    protected function doDel() {
        $uid = $_SESSION['uid'];
        $client_id = empty($_REQUEST['client_id']) ? false : $_REQUEST['client_id'];
        if(!empty($client_id)) {
            $valid = $this->clientService->getByUnique($client_id);
            if(!empty($valid)) {
                $condtion = array();
                $condtion['uid'] = $uid;
                $condtion['clientId'] = $client_id;
                $ret = $this->userElectionService->getByUnique($condtion);
                if(!empty($ret)) {
                    $id = $ret['id'];
                    $ret = $this->userElectionService->del($id);
                    $this->template->push('code', 0);
                    $this->template->push('data', $ret);
                } else {
                    $this->template->push('code', 0);
                    $this->template->push('data', 'has_delete');
                }
            } else {
                $this->template->push('code', 2);
                $this->template->push('error', 'invalid_client');
            }
        } else {
            $this->template->push('code', 2);
            $this->template->push('error', 'invalid_client');
        }
    }

}
// PHP END