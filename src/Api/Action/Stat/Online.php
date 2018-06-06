<?php
namespace Dcux\Api\Action\Stat;

use Lay\Advance\Core\Component;
use Lay\Advance\Core\Errode;
use Lay\Advance\Util\Utility;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VStatOnline;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\SSO\Service\StatService;

use Respect\Validation\Validator;

class Online extends TokenApi
{
    public function onCreate()
    {
        parent::onCreate();
        $this->statService = StatService::getInstance();
    }
    public function onGet()
    {
        $condition = array();
        $order = $this->getOrderDetail(array('id' => 'DESC'));
        $limit = $this->getLimit();
        $sincer = $this->getSincer();
        if (!empty($sincer)) {
            $condition['time'] = array($sincer, '<=');
        }
        $arr = $this->statService->getListOnline($condition, $order, $limit);
        $total = $this->statService->countOnlines($condition);
        $list = $arr;
        $this->setDefaultSincer($this->firstTime($list));// before genSince;
        $vscl = VStatOnline::parseListSimple($list, $total, $this->genHasNext($total), $this->genSince());
        $this->success($vscl);
    }
    public function onPost()
    {
        $this->onGet();
    }
    protected function firstTime($list)
    {
        reset($list);
        $first = current($list);
        return empty($first) ? false : $first['time'];
    }
    protected function params()
    {
        return array(
            'type' => array('default' => 1),
            'num' => array(
                'validator' => array(Validator::notEmpty(), Validator::max($this->max_num, true)),
                'default' => $this->def_num
            )
        );
    }
}
// PHP END
