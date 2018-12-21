<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Http\Services;

use App\Entities\ThirdEntity;
use App\Entities\UserEntity;
use App\Repositories\AccessTokenRepository;
use App\Repositories\AuthCodeRepository;
use App\Repositories\ClientRepository;
use App\Repositories\RefreshTokenRepository;
use App\Repositories\ScopeRepository;
use App\Repositories\SessionRepository;
use App\Repositories\UserClientRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ThirdService
{
    private $_identifier;
    private $_appId;
    private $_appSecret;
    private $_appName;

    public function init($type = 'weixin', $appId = '')
    {
        switch($type) {
            case 'weixin':
                $this->_identifier = $type;
                $this->_appId = env('WEIXIN_APP_ID');
                $this->_appSecret = env('WEIXIN_APP_SECRET');
                $this->_appName = env('WEIXIN_APP_NAME');
                break;
        }
    }
    
    public function identifier()
    {
        return $this->_identifier;
    }
    
    public function appId()
    {
        return $this->_appId;
    }

    public function appSecret()
    {
        return $this->_appSecret;
    }

    public function appName()
    {
        return $this->_appName;
    }

    /**
     * @return ThirdEntity
     */
    public function entity()
    {
        $entity = new ThirdEntity();
        $entity->setIdentifier($this->_identifier);
        $entity->setName($this->_appName);
        $entity->setAppId($this->_appId);
        $entity->setAppSecret($this->_appSecret);

        return $entity;
    }
}
