<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Http\Services;

use App\Http\Services\ThirdService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use EasyWeChat\Factory;
use EasyWeChat\OfficialAccount\Application as OfficialAccountApplication;
use EasyWeChat\Kernel\Messages\Message;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\Kernel\Messages\Article;

class WeixinService
{
    /**
     * @var Request
     */
    private $_request;
    /**
     * @var ThirdService
     */
    private $_third;

    public function __construct(Request $request, ThirdService $third)
    {
        $this->_request = $request;
        $this->_third = $third;
    }

    /**
     * @return OfficialAccountApplication
     */
    public function easyApp()
    {
        $config = [
            'app_id' => $this->_third->appId(),
            'secret' => $this->_third->appSecret(),
            'response_type' => 'object',
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
            ],
        ];
        return Factory::officialAccount($config);
    }

    /**
     * 检测微信错误信息
     */
    private function ifWeixinError($res)
    {
        if(!empty($res) && is_object($res) && !empty($res->errcode)) {
            $errcode = $res->errcode;
            $errmsg = $res->errmsg;
            throw new \Exception("2000|微信业务失败($errcode,$errmsg)");
        }

        return $res;
    }

    //// 公众号部分
    /**
     * 获取微信网页授权地址
     */
    public function snsAuthorize($redirectUri, $state = 'STATE', $scope = ['snsapi_userinfo'])
    {
        // 自定义回调地址
        $res = $this->easyApp()->oauth->scopes($scope)->redirect(url($redirectUri));

        return $res->getTargetUrl();
    }

    /**
     * 通过oauth协议获取用户信息
     */
    public function snsUserInfo($valid = true)
    {
        $res = $this->easyApp()->oauth->setRequest($this->_request)->user();

        return $valid ? $this->ifWeixinError($res) : $res;
    }
    
    ////////////////////
    //////// 业务部分
    ////////////////////
    /**
     * 微信授权地址
     */
    public function authorize($form)
    {
        $backurl = urlencode($form->backurl);
        $state = $form->state;
        $redirect = $this->snsAuthorize(url("/third/weixin/callback") . "?backurl=".$backurl, $state);

        return $redirect;
    }

    /**
     * 微信回调统一处理
     */
    public function callback($form)
    {
        if(!empty($form->name)) {
            // 设置回调名称指定的真实回调地址
            switch($form->name) {
                case 'article':
                    // TODO 特殊业务特殊处理
                    $url = url($form->backurl);
                    break;
                default:
                    $url = Str::startsWith($form->backurl, url()) ? $form->backurl : url($form->backurl);
                    break;
            }
        } else {
            // 设置真实的回调地址
            $url = Str::startsWith($form->backurl, url()) ? $form->backurl : url($form->backurl);
        }

        $url = append_url_query($url, [
            'code' => $form->code,
            'state' => $form->state
        ]);

        return $url;
    }

    /**
     * 示例回调地址
     */
    public function backurl($form)
    {
        // TODO
        // return $this->weixinUserInfo();

        return $form;
    }
}
