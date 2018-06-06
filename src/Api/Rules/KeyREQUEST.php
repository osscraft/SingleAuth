<?php
namespace Dcux\Api\Rules;

use Respect\Validation\Rules\Key;

class KeyREQUEST extends Key
{
    public function validate($input)
    {
        return parent::validate($_REQUEST);
    }
}

// PHP END
