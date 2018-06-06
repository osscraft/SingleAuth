<?php

namespace Lay\Advance\DB;

interface Uniqueness
{
    /**
     * @param array|string $unique
     */
    public function getByUnique($unique);
    /**
     * @param array|string $unique
     * @param array
     */
    public function updByUnique($unique, array $info);
    /**
     * @param array|string $unique
     */
    public function delByUnique($unique);
}
