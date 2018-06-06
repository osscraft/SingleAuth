<?php
namespace Dcux\Api\Rules;

use Respect\Validation\Rules\Key;

class KeyGET extends Key
{
    public function validate($input)
    {
        return parent::validate($_GET);
    }
}

// PHP END
