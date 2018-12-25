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
    public function bind(UserEntityInterface $user, ThirdEntityInterface $third, $thirdUser)
    {
        $thirdId = $third->getIdentifier();
        if($thirdId == 'weixin') {
            $jsonfile = storage_path("app/user-third-{$thirdId}-{$thirdUser->openid}.json");
            $userjsonfile = storage_path("app/user-third-{$user->getIdentifier()}.json");
        } else {
            throw new \Exception(QRCODE_ERR_104);
        }
        $json = file_exists($jsonfile) ? json_decode(file_get_contents($jsonfile)) : new \stdClass;
        $json->userId = $user->getIdentifier();
        $json->thirdUserId = $thirdUser->openid;
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
                $newUserJson->userId = $user->getIdentifier();
                $newUserJson->thirdUserId = $thirdUser->openid;
                $newUserJson->isBound = true;
                $newUserJsons[] = $newUserJson;
            }
            file_put_contents($userJsonfile, json_encode($newUserJsons));
        }
        // TODO 其他数据形式

        return $ret;
    }
    /**
     * Unbind the third application
     * 
     * @return boolean
     */
    public function unbind(UserEntityInterface $user, ThirdEntityInterface $third, $thirdUser)
    {
        $thirdId = $third->getIdentifier();
        if($thirdId == 'weixin') {
            $thirdUserId = $thirdUser->openid;
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
     * If bind the third application
     * 
     * @return boolean
     */
    public function isBound(ThirdEntityInterface $third, $thirdUser)
    {
        $thirdId = $third->getIdentifier();
        if($thirdId == 'weixin') {
            $thirdUserId = $thirdUser->openid;
            $jsonfile = storage_path("app/user-third-{$thirdId}-{$thirdUserId}.json");
        } else {
            throw new \Exception(QRCODE_ERR_104);
        }
        $json = file_exists($jsonfile) ? json_decode(file_get_contents($jsonfile)) : false;

        return empty($json) || empty($json->isBound) ? false : true;
    }
}
