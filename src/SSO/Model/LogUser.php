<?php

namespace Dcux\SSO\Model;

use Dcux\SSO\Core\TBean;

/**
 * 用户验证日志类
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
class LogUser extends TBean
{
    /**
     * 构造方法
     *
     * @return LogUser
     */
    public function __construct($uid = 0, $timeReported = '', $facilityHost = '', $clientId = '', $username = '', $success = 0, $ip = 0, $os = 0, $browser = 0)
    {
        $this->config['uid'] = 0 + $id; // uid
        $this->config['timeReported'] = $timeReported; // 日志记录时间
        $this->config['facilityHost'] = $facilityHost; // facilityHost
        $this->config['clientId'] = $clientId; // 客户端标识符，授权服务器提供给客户端的标识符
        $this->config['username'] = $username; // 用户名，对应于User中的userid,ldap中的userid
        $this->config['success'] = $success; // 验证成功与否
        $this->config['ip'] = $ip; // IP
        $this->config['os'] = $os; // 操作系统
        $this->config['browser'] = $browser; // 浏览器
    }
    public function table()
    {
        return 'log_user';
    }
    public function pk()
    {
        return 'uid';
    }
    public function columns()
    {
        return array(
                'uid' => 'uid',
                'timeReported' => 'timereported',
                'facilityHost' => 'facilityhost',
                'clientId' => 'client_id',
                'username' => 'username',
                'success' => 'success',
                'ip' => 'ip',
                'os' => 'os',
                'browser' => 'browser'
        );
    }
}
