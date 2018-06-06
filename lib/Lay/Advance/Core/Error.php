<?php
namespace Lay\Advance\Core;

use Lay\Advance\Core\Errode;
use Exception;

class Error extends Exception
{
    /**
     * @param string|Errode $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if ($message instanceof Errode) {
            parent::__construct($message->message, $message->code, $previous);
        } else {
            parent::__construct($message, $code, $previous);
            // set Errode;
            Errode::__error($this);
        }
    }
}

// PHP END
