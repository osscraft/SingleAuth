<?php

namespace Dcux\Api\Kernel;

use Lay\Advance\Core\Configuration;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Errode;

use Dcux\Api\Kernel\App;
use Dcux\Api\Kernel\Action;
use Dcux\Api\Data\VResponse;

use Respect\Validation\Validator;

abstract class Api extends Action
{
    protected $log = '';
    protected $params = array();
    protected $max_num = 100;
    protected $def_num = 20;
    protected $def_offset = null;
    protected $def_sincer = '';
    /**
     * @var VResponse
     */
    protected $vresponse;
    public function getLogInfo($has_result = false)
    {
        $extension = App::$_app->getExtension();
        $type = empty($extension) ? 'JSON' : strtoupper($extension);
        $prelog = implode(" ", array($type, App::$_app->getApiname()));
        $params = array();

        // 日志中params参数，非托管Action类存在时优先
        if (!empty(App::$_action)) {
            $params = json_encode(App::$_action->params);
        } elseif (!empty(App::$_trustee)) {
            $params = json_encode(App::$_trustee->params);
        }

        // 日志错误结果参数，托管Action类存在时优先
        if (!empty($has_result) || (!empty(App::$_trustee) && App::$_trustee->vresponse->rsp == 0)) {
            $result = json_encode(App::$_trustee->vresponse->toStandard());
            $log = implode(" ", array($prelog, $params, $result));
        } elseif (!empty($has_result) || (!empty(App::$_action) && App::$_action->vresponse->rsp == 0)) {
            $result = json_encode(App::$_action->vresponse->toStandard());
            $log = implode(" ", array($prelog, $params, $result));
        } else {
            $log = implode(" ", array($prelog, $params));
        }
        return $log;
    }
    public function onCreate()
    {
        parent::onCreate();
        //
        $this->vresponse = new VResponse();
        //
        Validator::with('\\Dcux\\Api\\Rules\\');
    }
    /**
     * 验证不通过，不执行onGet,onPost等方法，需要跳过时请重写此方法
     */
    public function onValidate()
    {
        $ps = $this->params();
        if (empty($ps)) {
            return true;
        } else {
            foreach ($ps as $key => $opts) {
                $opts = empty($opts) && !is_array($opts) ? array() : $opts;
                // 默认值
                if (array_key_exists('default', $opts)) {
                    $this->params[$key] = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $opts['default'];
                } elseif (isset($_REQUEST[$key])) {
                    // 存在
                    $this->params[$key] = $_REQUEST[$key];
                }
            }
            // 后验证
            foreach ($ps as $key => $opts) {
                if (array_key_exists('validator', $opts)) {
                    if (!$this->validate($key, $opts['validator'])) {
                        $this->failure(Errode::invalid_param($key));
                        return false;
                    }
                } elseif (array_key_exists('filter', $opts)) {
                    // TODO filter
                }
            }
            return true;
        }
    }

    protected function validate($key, $vs)
    {
        $vs = empty($vs) && !is_array($vs) ? array() : $vs;
        foreach ($vs as $validator) {
            if ($validator instanceof Validator) {
                if (!$validator->validate($this->params[$key])) {
                    //echo '<pre>';print_r($validator->reportError($key));exit;
                    return false;
                }
            }
        }
        return true;
    }
    private function filter($key, $fs)
    {
    }

    // 参数验证数组
    protected function params()
    {
        // cid => clientId
        // uid => userid, username(有些时候)
        // sid => security id,加密后的uid
        // tid, token => oauth2 token,OAuth2认证后获取的令牌码
        // since
        // offset
        // num
        // ...
        return array();
    }

    // 设置默认since中的sincer值
    protected function setDefaultSincer($sincer = null)
    {
        $this->def_sincer = is_null($sincer) ? $this->def_sincer : $sincer;
    }
    // 设置默认offset值
    protected function setDefaultOffset($offset = null)
    {
        $this->def_offset = is_null($offset) ? $this->def_offset : $offset;
    }


    // 通用获取since参数
    protected function getSince()
    {
        return empty($_REQUEST['since']) ? '' : $_REQUEST['since'];
    }
    // 通用获取since参数的条件部分
    protected function getSincer()
    {
        $sDetail = $this->getSinceDetail();
        if (empty($sDetail)) {
            return false;
        } else {
            list($first, $offset, $remain) = $sDetail;
            return $first;
        }
    }
    // 通用获取since参数的剩余部分
    protected function getRemain()
    {
        $sDetail = $this->getSinceDetail();
        if (empty($sDetail)) {
            return false;
        } else {
            list($first, $offset, $remain) = $sDetail;
            return $remain;
        }
    }
    // 通用获取since参数明细
    protected function getSinceDetail()
    {
        $since = $this->getSince();
        if (empty($since)) {
            return false;
        } else {
            $pieces = explode('.', $since);
            $pieces = empty($pieces) ? array() : $pieces;
            $first = array_shift($pieces);
            $first = empty($first) ? '' : $first;
            $offset = array_pop($pieces);
            $offset = is_null($offset) ? $this->def_offset : intval($offset);
            $remain = empty($pieces) ? array() : $pieces;
            return array($first, $offset, $remain);
        }
    }
    // 通用生成since参数
    protected function genSince($sincer = null)
    {
        $sDetail = $this->getSinceDetail();
        list($offset, $num) = $this->getLimit();
        $this->def_sincer = is_null($sincer) ? $this->def_sincer : $sincer;

        if ($sDetail) {
            list($first, $offset, $remain) = $sDetail;
        } else {
            list($first, $offset, $remain) = array(null, null, array());
        }
        if (empty($first) && is_null($offset) && isset($_REQUEST['offset'])) {
            // 如果不存在since,存在offset参数时since中不添加offset值
            $pieces = array($this->def_sincer);
        } elseif (empty($first) && is_null($offset)) {
            // 如果不存在since,不存在offset参数时since中添加offset值
            $pieces = array($this->def_sincer, $num);
        } elseif (empty($first)) {
            // 如果存在since且不为单时,since中添加offset值
            $pieces = array_merge(array($this->def_sincer), $remain, array($offset + $num));
        } elseif (is_null($offset) && isset($_REQUEST['offset'])) {
            // 如果存在since且为单,存在offset参数时since中不添加offset值
            $pieces = array_merge(array($first));
        } elseif (is_null($offset)) {
            // 如果存在since且为单,不存在offset参数时since中添加offset值
            $pieces = array_merge(array($first, $num));
        } else {
            // 如果存在since且不为单时,since中添加offset值
            $pieces = array_merge(array($first), $remain, array($offset + $num));
        }

        return implode('.', $pieces);
    }
    // 通用获取分页参数方法
    protected function getLimit()
    {
        $offset = empty($_REQUEST['offset']) ? 0 : intval($_REQUEST['offset']);
        $sDetail = $this->getSinceDetail($offset);
        $num = empty($_REQUEST['num']) ? $this->def_num : intval($_REQUEST['num']);
        // since中的offset优先级高
        if (empty($sDetail)) {
            if ($offset < 0) {
                $offset = 0;
            }
        } else {
            list($first, $offset, $remain) = $sDetail;
            $offset = empty($offset) ? 0 : $offset;
        }
        if ($num > $this->max_num) {
            $num = $this->max_num;
        }
        return array($offset, $num);
    }
    // 通用获取分页参数中的num值
    protected function getNum()
    {
        list($offset, $num) = $this->getLimit();
        return $num;
    }
    // 通用获取分页参数中的offset值
    protected function getOffset()
    {
        list($offset, $num) = $this->getLimit();
        return $offset;
    }
    // 通用生成hasNext参数
    protected function genHasNext($total)
    {
        list($offset, $num) = $this->getLimit();
        return Utility::hasNext($total, $offset, $num);
    }
    // 通用获取排序参数
    protected function getOrder()
    {
        return empty($_REQUEST['sort']) ? 0 : intval($_REQUEST['sort']);
    }
    // 通用获取排序参数明细
    protected function getOrderDetail($available = array())
    {
        $sort = $this->getOrder();
        $keys = array();
        // 组织有效的keys
        if (is_array($available) && Utility::isAssocArray($available)) {
            foreach ($available as $key => $desc) {
                $keys[$key] = $this->detectDesc($desc);
            }
        } elseif (is_array($available)) {
            foreach ($available as $key) {
                $keys[$key] = $this->detectDesc();
            }
        }
        // get sort keys with desc or asc
        if (empty($sort) || empty($keys)) {
            return $keys;
        } else {
            $sorts = array();
            $pieces = explode(',', $sort);
            foreach ($pieces as $piece) {
                list($name, $desc) = array_merge(explode('.', $piece), array(false));
                // 在有效的keys中
                if ($name && array_key_exists($name, $keys)) {
                    // false 时使用默认值
                    $desc = $desc === false ? $keys[$name] : $desc;
                    $sorts[$name] = $this->detectDesc($desc);
                }
            }
            // 返回
            return $sorts;
        }
    }


    // 转换排序正反（DESC，ASC）
    protected function detectDesc($desc = 'ASC')
    {
        $desc = empty($desc) ? 'ASC' : strval($desc);
        $desc = strtoupper($desc);
        switch ($desc) {
            case 'DESC':
            case '1':
                $desc = 'DESC';
                break;
            default:
                $desc = 'ASC';
                break;
        }
        return $desc;
    }

    // overide
    public function onRender()
    {
        $this->vresponse = empty($this->vresponse) ? new VResponse() : $this->vresponse;
        $this->template->distinct();// clean template vars
        $this->template->push($this->vresponse->toStandard());
        parent::onRender();
    }
    protected function failure($code, $msg = null)
    {
        $this->vresponse = empty($this->vresponse) ? new VResponse() : $this->vresponse;
        $this->vresponse->rsp = 0;
        $this->vresponse->data = $msg;
        $this->vresponse->code = $code;
    }
    protected function success($data)
    {
        $this->vresponse = empty($this->vresponse) ? new VResponse() : $this->vresponse;
        $this->vresponse->rsp = 1;
        $this->vresponse->code = 0;
        $this->vresponse->data = $data;
    }
}
