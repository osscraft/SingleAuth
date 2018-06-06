<?php
namespace Dcux\Api\Action\User;

use Lay\Advance\Core\Component;
use Lay\Advance\Util\Logger;
use Lay\Advance\Core\Errode;
use Lay\Advance\Util\Utility;

use Dcux\Api\Data\VList;
use Dcux\Api\Data\VObject;
use Dcux\Api\Data\VStatUserList;
use Dcux\Api\Data\VStatUser;
use Dcux\Api\Kernel\SApi;
use Dcux\Api\Kernel\TApi;
use Dcux\Api\Kernel\TokenApi;
use Dcux\SSO\Service\StatUserDetailService;
use Dcux\SSO\Service\StatUserService;

use Respect\Validation\Validator;

// @see http://sso.project.dcux.com/api/user/history?sid=mPWsTPVEh75pDQai93VLOh9tRtPJyiYGxw==&uid=liaiyong&since=2015-7-30.2

class History extends TokenApi
{
    public function onCreate()
    {
        parent::onCreate();
        $this->statUserDetailService = StatUserDetailService::getInstance();
        $this->statUserService = StatUserService::getInstance();
    }
    public function onGet()
    {
        $this->onPost();
    }
    public function onPost()
    {
        //$type = $this->params['type'];

        $arr = array();
        $order = $this->getOrderDetail(array('id' => 'DESC'));
        $limit = $this->getLimit();
        $sincer = $this->getSincer();
        $uid = $this->params['uid'];
        $condition  = array();
        $condition['uid'] = $uid;
        $condition['username'] = $uid;
        if (!empty($sincer)) {
            $condition['date'] = array($sincer, '<=');
        }

        $total = $this->statUserService->count($condition);
        $list = $this->statUserService->getConditionList($condition, $order, $limit);
        $this->setDefaultSincer($this->firstDate($list));// before genSince;
        $ret = VStatUser::parseList($list, $total, $this->genHasNext($total), $this->genSince());

        $this->success($ret);
    }
    protected function firstDate($list)
    {
        reset($list);
        $first = current($list);
        return empty($first) ? false : $first['date'];
    }

    protected function params()
    {
        return array(
            'type' => array('default' => 1),
            'uid' => array(
                'validator' => array(Validator::notEmpty(), Validator::equals($this->getUid()))
            ),
            'num' => array(
                'validator' => array(Validator::notEmpty(), Validator::max($this->max_num, true)),
                'default' => $this->def_num
            )
        );
    }
}
// PHP END
