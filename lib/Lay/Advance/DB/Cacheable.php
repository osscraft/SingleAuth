<?php

namespace Lay\Advance\DB;

interface Cacheable
{
    /**
     * encode data
     * @return string
     */
    public function encode($mix);
    /**
     * decode data
     * @return mixed
     */
    public function decode($str);
}
// PHP END
