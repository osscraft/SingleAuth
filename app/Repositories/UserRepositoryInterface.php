<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Repositories;

use App\Entities\ThirdEntityInterface;
use App\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface as LeagueUserRepositoryInterface;

interface UserRepositoryInterface extends LeagueUserRepositoryInterface
{
    /**
     * 通过登录用户名获取用户实例
     * 
     * @return UserEntityInterface
     */
    public function getUserEntityByUsername($username);
    /**
     * 通过用户ID获取用户实例
     * 
     * @return UserEntityInterface
     */
    public function getUserEntityByIdentifier($userId);
    /**
     * 通过绑定的第三方用户信息获取用户实例
     * 
     * @return UserEntityInterface
     */
    public function getBoundUser(ThirdEntityInterface $third, $thirdUser);
    /**
     * 绑定第三方用户
     * 
     * @return boolean
     */
    public function bind(UserEntityInterface $user, ThirdEntityInterface $third, $thirdUser);
    /**
     * 解绑第三方用户
     * 
     * @return boolean
     */
    public function unbind(UserEntityInterface $user, ThirdEntityInterface $third, $thirdUser);
    /**
     * 是否绑定第三方用户
     * 
     * @return boolean
     */
    public function isBound(ThirdEntityInterface $third, $thirdUser);
}
