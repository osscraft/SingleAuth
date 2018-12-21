<?php

namespace App\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Entities\UserEntity;

class UserClientRepository implements UserClientRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function persistUserClient(UserEntityInterface $userEntity, ClientEntityInterface $clientEntity)
    {
        // TODO
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthorized(UserEntityInterface $userEntity, ClientEntityInterface $clientEntity)
    {

        return true;
    }
}
