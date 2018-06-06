<?php

namespace Dcux\SSO\Service;

use Lay\Advance\Core\App;
use Lay\Advance\Core\Service;
use Lay\Advance\Util\Logger;
use Lay\Advance\Util\EventEmitter;
use Lay\Advance\Util\Utility;
use Lay\Advance\Core\Action;

use Dcux\SSO\Model\Token;
use Dcux\SSO\OAuth2\OAuth2;

class OAuth2TokenService extends Service
{
    private $oauth2Token;
    public function model()
    {
        return Token::getInstance();
    }
    protected function __construct()
    {
        parent::__construct();
        $this->oauth2Token = Token::getInstance();
    }

    /**
     * 生成访问令牌
     * @param array $user
     * @param array $client
     * @param array|string $scope
     * @return array
     */
    public function gen($user, $client, $scope = '')
    {
        global $CFG;
        $username = $user['uid']; //
        $client_id = $client['clientId'];
        $lifetime = empty($client['tokenLifetime']) ? $CFG['access_token_lifetime'] : $client['tokenLifetime'];
        $scope = empty($scope) ? $client['scope'] : $scope;
        $delay = empty($CFG['mysql_token_delay']) ? 60 : $CFG['mysql_token_delay'];
        $auth2token = $this->checkTokenByClientAndUser($client_id, $username);
        $token = array(
                "uid" => $username,
                "access_token" => OAuth2::generateCode(),
                "token_type" => 'access',
                "expires_in" => $lifetime
            );
        $tokenArray = array(
                'oauthToken' => $token["access_token"],
                'clientId' => $client_id,
                'expires' => time() + $lifetime,
                'username' => $username,
                'scope' => $scope,
                'type' => $CFG['access_token_type']
            );
        /*if($auth2token){
            $token = array (
                "uid" => $username,
                "access_token" => $auth2token['oauthToken'],
                "token_type" => 'access',
                "expires_in" => $auth2token['expires'] - time()
            );
        }else{
            $token = array (
                "uid" => $username,
                "access_token" => OAuth2::generateCode(),
                "token_type" => 'access',
                "expires_in" => $lifetime
            );
            $tokenArray = array (
                'oauthToken' => $token["access_token"],
                'clientId' => $client_id,
                'expires' => time() + $lifetime,
                'username' => $username,
                'scope' => $scope,
                'type' => $CFG['access_token_type']
            );
        }*/

        // 没有任务计划时执行删除已过期令牌
        if (empty($CFG['cron_open'])) {
            $this->clean();
        }
        if (!empty($username) && !empty($client_id)) {
            $type = $this->model()->type;
            //设置类型
            $this->model()->type = $CFG['access_token_type'];
            if (empty($auth2token)) {
                // 不同服务器间存在时间差，add有时会报错，使用replace
                $ret = $this->replace($tokenArray); // 存入数据库
            }/*else if($auth2token && intval($auth2token['expires']) - time() < $delay){
                //$tokenArray = array (
                   // 'oauthToken' => $auth2token['oauthToken'],
                    //'expires' => time() + $lifetime
                //);
                $auth2token['expires'] = time() + $lifetime;
                $ret = $this->replace($auth2token); // 存入数据库
                //$ret = $this->upd($auth2token['oauthToken'],$tokenArray);
            }else{
                $ret = true;
            }*/else {
                $return = $this->del($auth2token['oauthToken']);
                if ($return) {
                    $ret = $this->replace($tokenArray);
                } else {
                    $ret = false;
                }
            }
            
            //还原类型
            $this->model()->type = $type;
        }
        return empty($ret) ? false : $token;
    }
    /**
     * 生成刷新令牌
     * @param array $user
     * @param array $client
     * @param array|string $scope
     * @return array
     */
    public function genRefresh($user, $client, $scope = '')
    {
        global $CFG;
        $username = $user['uid']; //
        $client_id = $client['clientId'];
        $scope = empty($scope) ? $client['scope'] : $scope;
        $delay = empty($CFG['mysql_token_delay']) ? 60 : $CFG['mysql_token_delay'];
        $token = array(
                "refresh_token" => OAuth2::generateCode(),
                "refresh_expires_in" => $CFG['refresh_token_lifetime']
        );
        $tokenArray = array(
                'oauthToken' => $token["refresh_token"],
                'clientId' => $client_id,
                'expires' => time() + $CFG['refresh_token_lifetime'],
                'username' => $username,
                'scope' => $scope,
                'type' => $CFG['refresh_token_type']
        );
        // 没有任务计划时执行删除已过期刷新令牌
        if (empty($CFG['cron_open'])) {
            $this->clean();
        }
        if (!empty($username) && !empty($client_id)) {
            $type = $this->model()->type;
            //设置类型
            $this->model()->type = $CFG['refresh_token_type'];
            $ret = $this->add($tokenArray); // 存入数据库
            //还原类型
            $this->model()->type = $type;
        }
        return empty($ret) ? false : $token;
    }
    /**
     * 验证令牌是否合法
     */
    public function validToken($token, $clientId = '', $uid = '')
    {
        $tokenArr = $this->get($token);
        if (empty($tokenArr)) {
            return false;
        }
        if (empty($client) && empty($uid)) {
            return empty($tokenArr) ? false : $tokenArr;
        } else {
            // TODO
            return false;
        }
    }

    /**
     * 清除过期令牌
     */
    public function clean()
    {
        $field1 = $this->model()->toField('expires');
        $ret = $this->model()->db()->delete($field1 . ' < UNIX_TIMESTAMP()');
        return empty($ret) ? false : true;
    }


    /**
     * 获取所有客户端列表
     * @param array $condition
     * @param array $limit
     * @return array
     */
    public function getTokenListAll($condition = array(), $order = array())
    {
        $ret = $this->model()->db()->select(array(), $condition, $order, array(), false);
        return empty($ret) ? array() : $ret;
    }
    
    public function checkTokenByClientAndUser($client_id, $username)
    {
        $oauth2TokenArr = $this->model()->getByUnique(array('client_id'=>$client_id,'username'=>$username));
        return empty($oauth2TokenArr) ? false : $oauth2TokenArr;
    }
}
// PHP END
