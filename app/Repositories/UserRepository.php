<?php

namespace App\Repositories;

use App\Entities\ThirdEntityInterface;
use App\Entities\UserEntity;
use App\Entities\UserEntityInterface;
use App\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

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
        if ($username === 'lay' && $password === '111111') {
            $user = new UserEntity();
            $user->setIdentifier(1);
            $user->setUsername($username);
            $user->setName($username);
            
            return $user;
        }

        return;
    }

    /**
     * {@inheritdoc}
     * 
     * @return UserEntity
     */
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
     * {@inheritdoc}
     * 
     * @return UserEntity
     */
    public function getUserEntityByIdentifier($userId)
    {
        if ($userId == 1) {
            $user = new UserEntity();
            $user->setIdentifier(1);
            $user->setUsername('lay');
            $user->setName('lay');
            
            return $user;
        }

        return;
    }

    /**
     * {@inheritdoc}
     * 
     * @return UserEntity
     */
    public function getBoundUser(ThirdEntityInterface $third, $thirdUser)
    {
        $thirdId = $third->getIdentifier();
        if($thirdId == 'weixin') {
            $thirdUserId = $thirdUser->id;
            $jsonfile = storage_path("app/user-third-{$thirdId}-{$thirdUserId}.json");
        } else {
            throw new \Exception(QRCODE_ERR_104);
        }

        $json = file_exists($jsonfile) ? json_decode(file_get_contents($jsonfile)) : null;

        if(empty($json) || empty($json->userId)) {
            return null;
        }

        return $this->getUserEntityByIdentifier($json->userId);
    }

    /**
     * {@inheritdoc}
     * 
     * @return boolean
     */
    public function bind(UserEntityInterface $user, ThirdEntityInterface $third, $thirdUser)
    {
        $thirdId = $third->getIdentifier();
        if($thirdId == 'weixin') {
            $thirdUserId = $thirdUser->id;
            $jsonfile = storage_path("app/user-third-{$thirdId}-{$thirdUserId}.json");
            $userJsonfile = storage_path("app/user-third-{$user->getIdentifier()}.json");
        } else {
            throw new \Exception(QRCODE_ERR_104);
        }
        $json = file_exists($jsonfile) ? json_decode(file_get_contents($jsonfile)) : new \stdClass;
        $json->userId = $user->getIdentifier();
        $json->thirdUserId = $thirdUserId;
        $json->isBound = true;

        $ret = file_put_contents($jsonfile, json_encode($json));
        if($ret) {
            $userJsons = file_exists($userJsonfile) ? json_decode(file_get_contents($userJsonfile)) : [];
            $newUserJsons = [];
            $found = false;
            foreach($userJsons as $userJson) {
                $newUserJsons[] = $userJson;
                if($userJson->thirdId == $thirdId) {
                    $found = true;
                }
            }
            if(empty($found)) {
                $newUserJson = new \stdClass;
                $newUserJson->thirdId = $thirdId;
                $newUserJson->userId = $user->getIdentifier();
                $newUserJson->thirdUserId = $thirdUserId;
                $newUserJson->isBound = true;
                $newUserJsons[] = $newUserJson;
            }
            file_put_contents($userJsonfile, json_encode($newUserJsons));
        }
        // TODO 其他数据形式

        return $ret;
    }
    /**
     * {@inheritdoc}
     * 
     * @return boolean
     */
    public function unbind(UserEntityInterface $user, ThirdEntityInterface $third, $thirdUser)
    {
        $thirdId = $third->getIdentifier();
        if($thirdId == 'weixin') {
            $thirdUserId = $thirdUser->id;
            $jsonfile = storage_path("app/user-third-{$thirdId}-{$thirdUserId}.json");
            $userJsonfile = storage_path("app/user-third-{$user->getIdentifier()}.json");
        } else {
            throw new \Exception(QRCODE_ERR_104);
        }
        $json = file_exists($jsonfile) ? json_decode(file_get_contents($jsonfile)) : new \stdClass;
        $json->userId = 0;
        $json->thirdUserId = 0;
        $json->isBound = false;

        $ret = file_put_contents($jsonfile, json_encode($json));
        if($ret) {
            $userJsons = file_exists($userJsonfile) ? json_decode(file_get_contents($userJsonfile)) : [];
            $newUserJsons = [];
            foreach($userJsons as $userJson) {
                if($userJson->thirdId != $thirdId) {
                    $newUserJsons[] = $userJson;
                }
            }
            file_put_contents($userJsonfile, json_encode($newUserJsons));
        }
        // TODO 其他数据形式

        return $ret;
    }
    /**
     * {@inheritdoc}
     * 
     * @return boolean
     */
    public function isBound(ThirdEntityInterface $third, $thirdUser)
    {
        $thirdId = $third->getIdentifier();
        if($thirdId == 'weixin') {
            $thirdUserId = $thirdUser->id;
            $jsonfile = storage_path("app/user-third-{$thirdId}-{$thirdUserId}.json");
        } else {
            throw new \Exception(QRCODE_ERR_104);
        }
        $json = file_exists($jsonfile) ? json_decode(file_get_contents($jsonfile)) : false;

        return empty($json) || empty($json->isBound) ? false : true;
    }
}
