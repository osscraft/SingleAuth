<?php

namespace App\Events;

class ExceptionEvent extends Event
{
    public $exception;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }
}
