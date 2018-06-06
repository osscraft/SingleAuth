<?php
namespace Dcux\Api\Rules;

use Respect\Validation\Rules\Key;

class KeyPOST extends Key
{
    public function validate($input)
    {
        return parent::validate($_POST);
    }
}

// PHP END
