<?php

namespace App\Service;

interface TransactionInterface
{
    /**
     * @return TransactionInterface
     */
    public function trans();
}