<?php

namespace App\Entities;

use App\Entities\Traits\UserTrait;
use App\Entities\UserEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class UserEntity implements UserEntityInterface
{
    use EntityTrait, UserTrait;
}