<?php

namespace Dcux\SSO\Model;

use Dcux\SSO\Core\TBean;

/**
 * 客户端验证日志类
 *
 * @category
 *
 * @package classes
 * @author liaiyong <liaiyong@dcux.com>
 * @version 1.0
 * @copyright 2005-2012 dcux Inc.
 * @link http://www.dcux.com
 *      
 */
class LogClient extends TBean {
    /**
     * 构造方法
     *
     * @return LogClient
     */
    public function __construct($uid = 0, $timeReported = '', $facilityHost = '', $clientId = '', $clientType = '', $reponseType = '', $grantType = '', $redirectURI = '', $success = 0) {
        $this->config['uid'] = 0 + $uid; // uid
        $this->config['timeReported'] = $timeReported; // 日志记录时间
        $this->config['facilityHost'] = $facilityHost; // facilityHost
        $this->config['clientId'] = $clientId; // 客户端标识符，授权服务器提供给客户端的标识符
        $this->config['clientType'] = $clientType; // 客户端类型，有三种：web应用（webApp）,js应用（jsApp）,桌面应用（desktopApp）
        $this->config['reponseType'] = $reponseType; // 请求授权响应类型
        $this->config['grantType'] = $grantType; // 授权验证类型
        $this->config['redirectURI'] = $redirectURI; // 重定向URI
        $this->config['success'] = $success; // 验证成功与否
        $this->config['ip'] = $ip; // IP
        $this->config['os'] = $os; // 操作系统
        $this->config['browser'] = $browser; // 浏览器
    }
    public function table() {
        return 'log_client';
    }
    public function pk() {
        return 'uid';
    }
    public function columns() {
        return array (
                'uid' => 'uid',
                'timeReported' => 'timereported',
                'facilityHost' => 'facilityhost',
                'clientId' => 'client_id',
                'clientType' => 'client_type',
                'reponseType' => 'reponse_type',
                'grantType' => 'grant_type',
                'redirectURI' => 'redirect_uri',
                'success' => 'success',
                'ip' => 'ip',
                'os' => 'os',
                'browser' => 'browser' 
        );
    }
}
?>
