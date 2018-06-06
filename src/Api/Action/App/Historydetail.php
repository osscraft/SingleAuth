<?php
namespace Dcux\Api\Action\App;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VStatClient;
use Dcux\Api\Data\VStatUserDetail;
use Dcux\Api\Kernel\SApi;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\SSO\Service\ClientService;
use Dcux\SSO\Service\StatClientService;
use Dcux\SSO\Service\StatUserDetailService;

use Respect\Validation\Validator;

// @see http://sso.project.dcux.com/api/app/history?token=7083f0dd3550aaf73e0c027357b48400&cid=ufsso_dcux_portal

class Historydetail extends TokenApi
{
    protected $clientService;
    protected $statClientService;
    protected $statUserDetailService;
    public function onCreate()
    {
        parent::onCreate();
        $this->clientService = ClientService::getInstance();
        $this->statClientService = StatClientService::getInstance();
        $this->statUserDetailService = StatUserDetailService::getInstance();
    }
    public function onGet()
    {
        $arr = array();
        $order = $this->getOrderDetail(array('id' => 'DESC'));
        $limit = $this->getLimit();
        $sincer = $this->getSincer();
        $cid = $this->params['cid'];
        $success = $this->params['success'];
        $condition  = array();
        $condition['clientId'] = $cid;
        //
        if ($success >= 0) {
            if (empty($success)) {
                $condition['success'] = 0;
            } else {
                $condition['success'] = 1;
            }
        }
        //
        if (!empty($sincer)) {
            $condition['time'] = array(date('Y-m-d H:i:s', intval($sincer)), '<=');
        }

        $exsits = $this->clientService->getByUnique($cid);
        if (!empty($exsits)) {
            $total = $this->statUserDetailService->count($condition);
            $list = $this->statUserDetailService->getConditionList($condition, $order, $limit);
            $this->setDefaultSincer($this->firstTime($list));// before genSince;
            $vscl = VStatUserDetail::parseList($list, $total, $this->genHasNext($total), $this->genSince());
            $this->success($vscl);
        } else {
            $this->failure(Errode::client_not_exists());
        }
    }
    protected function firstTime($list)
    {
        reset($list);
        $first = current($list);
        return empty($first) ? false : strtotime($first['time']);
    }
    public function onPost()
    {
        $this->onGet();
    }
    protected function params()
    {
        return array(
            'type' => array('default' => 1),
            'cid' => array(
                'validator' => array(Validator::notEmpty(), Validator::equals($this->getClientId()))
            ),
            'num' => array(
                'validator' => array(Validator::notEmpty(), Validator::max($this->max_num, true)),
                'default' => $this->def_num
            ),
            'success' => array(
                'default' => -1
            )
        );
    }
}
// PHP END
