<?php

namespace App\OAuth2\Server\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\OAuth2\Server\Entities\UserEntity;

interface UserClientRepositoryInterface
{
    /**
     * 用户授权某应用
     */
    public function persistUserClient(UserEntityInterface $userEntity, ClientEntityInterface $clientEntity);

    /**
     * 用户是否授权过某应用
     */
    public function isAuthorized(UserEntityInterface $userEntity, ClientEntityInterface $clientEntity);
}
