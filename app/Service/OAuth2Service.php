<?php

namespace App\Service;

use App\OAuth2\Entities\UserEntity;

class OAuth2Service
{
    public function login($form)
    {
        // TODO 验证用户名密码
        $form->loginTimes = 0;
        if(empty($form->username) || empty($form->password)) {
            $_SESSION['loginTimes'] += 1;
            $form->loginTimes = $_SESSION['loginTimes'];
            return [false];
        }

        $seesionUser = new UserEntity();
        $seesionUser->getIdentifier();
        $_SESSION['user'] = $seesionUser;

        return [true, $seesionUser];
    }
}