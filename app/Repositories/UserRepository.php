<?php

namespace App\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use App\Entities\ThirdEntityInterface;
use App\Entities\UserEntity;
use App\Entities\UserEntityInterface;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     * 
     * @return UserEntity
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        // dd([$username,$password]);
        if ($username === 'lay' && $password === '111111') {
            $user = new UserEntity();
            $user->setIdentifier(1);
            $user->setUsername($username);
            $user->setName($username);
            
            return $user;
        }

        return;
    }

    public function getUserEntityByUsername($username)
    {
        if ($username === 'lay') {
            $user = new UserEntity();
            $user->setIdentifier(1);
            $user->setUsername($username);
            $user->setName($username);
            
            return $user;
        }

        return;
    }

    /**
     * Bind the third application
     * 
     * @return boolean
     */
    public function bind(UserEntityInterface $user, ThirdEntityInterface $third)
    {

    }
    /**
     * Unbind the third application
     * 
     * @return boolean
     */
    public function unbind(UserEntityInterface $user, ThirdEntityInterface $third)
    {

    }
    /**
     * If bind the third application
     * 
     * @return boolean
     */
    public function isBound(UserEntityInterface $user, ThirdEntityInterface $third)
    {

    }
}
