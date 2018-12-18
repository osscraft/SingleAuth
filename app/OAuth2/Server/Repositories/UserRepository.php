<?php

namespace App\OAuth2\Server\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\OAuth2\Server\Entities\UserEntity;

class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        if ($username === 'alex' && $password === 'whisky') {
            return new UserEntity();
        }

        return;
    }
}
