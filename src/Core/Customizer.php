<?php

namespace Dcux\Core;

use Dcux\Core\Customizable;

abstract class Customizer extends Singleton
{
    /**
     * @return Epiboly
     */
    abstract public function epiboly();
    public function customize(Customizable $customizable)
    {
        $epiboly = $this->epiboly();
        $epiboly->receive($customizable);
        $epiboly->produce();
        return $epiboly->delivery();
    }
}

// PHP END
