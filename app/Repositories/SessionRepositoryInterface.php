<?php

namespace App\Repositories;

use League\OAuth2\Server\Entities\UserEntityInterface;

interface SessionRepositoryInterface
{
    /**
     * 持久化用户
     */
    public function persistUser(UserEntityInterface $userEntity);

    /**
     * 是否有登录用户
     */
    public function hasUser();

    /**
     * 获取登录用户
     */
    public function getUser();

    /**
     * 清除会话用户
     */
    public function revokeUser();

    /**
     * 登录输入次数
     */
    public function getLoginCount();

    /**
     * 记录增涨登录输入次数
     */
    public function incLoginCount();

    /**
     * 清除输入次数
     */
    public function revokeLoginCount();
    
    /**
     * 最后一次尝试登录时间
     */
    public function getLastAttemptTime();

    /**
     * 保存最后一次尝试登录时间
     */
    public function persistLastAttemptTime();

    /**
     * 清除最后一次尝试登录时间
     */
    public function revokeLastAttemptTime();
}
