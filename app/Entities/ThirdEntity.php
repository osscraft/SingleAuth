<?php

namespace App\Entities;

use App\Entities\Traits\ThirdTrait;
use App\Entities\ThirdEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ThirdEntity implements ThirdEntityInterface
{
    use EntityTrait, ThirdTrait;
}
