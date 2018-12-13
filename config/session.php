<?php
return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => 3600,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => storage_path('session'),
    'connection' => null,  
    'table' => 'sessions',
    'lottery' => [2, 200],
    'cookie' => 'SASESSION',
    'path' => '/',
    'domain' => null,
    'secure' => false,
];
