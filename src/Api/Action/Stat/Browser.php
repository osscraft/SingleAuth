<?php

namespace Dcux\Api\Action\Stat;

use Lay\Advance\Core\Errode;

use Dcux\Api\Data\VList;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\Api\Kernel\Api;
use Dcux\Api\Data\VStatBrowser;
use Dcux\SSO\Service\StatService;

use Respect\Validation\Validator;

class Browser extends TokenApi
{
    protected $def_num = -1;
    public function onCreate()
    {
        parent::onCreate();
        $this->statService = StatService::getInstance();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        // 无需分页
        $vStatBrowser = new VStatBrowser();
        $arr = array();
        $condition = array();
        $order = $this->getOrderDetail(array('count' => 'DESC'));
        $limit = $this->getLimit();
        $sincer = $this->getSincer();
        $list = $this->statService->getBrowsers($condition, $order, $limit);
        $total = $this->statService->countBrowsers();
        $ret = VStatBrowser::parseListSimple($list, $total, $this->genHasNext($total), '');

        $this->success($ret);
    }
    protected function params()
    {
        return array(
            'type' => array('default' => 1)//,
            /*'num' => array(
                'validator' => array(Validator::notEmpty())//, Validator::max($this->max_num, true),
                //'default' => $this->def_num
            )*/
        );
    }
}
// PHP END
