<?php

namespace App\OAuth2\Server\Repositories;

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
    public function loginTimes();

    /**
     * 记录增涨登录输入次数
     */
    public function incLoginTimes();

    /**
     * 清除输入次数
     */
    public function revokeLoginTimes();
}
