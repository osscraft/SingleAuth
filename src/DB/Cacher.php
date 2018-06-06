<?php

namespace Dcux\DB;

use Dcux\DB\Cacheable;

abstract class Cacher extends DataBase implements Cacheable
{
    public function encode($mix)
    {
        return json_encode($mix);
    }
    public function decode($str)
    {
        return json_decode($str, true);
    }
    final public function count(array $info = array())
    {
        return false;
    }
    final public function replace(array $info = array())
    {
        return false;
    }
}
// PHP END
