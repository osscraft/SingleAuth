<?php

return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => 3600,
    'expire_on_close' => false,
    'files' => storage_path('session'),
    // 'connection' => 'mysql',
    // 'table' => 'sessions',
    'cookie' => 'SASESSION',
    'path' => '/',
    'lottery' => [2, 200],
    'domain' => null,
    'encrypt' => false,
];
