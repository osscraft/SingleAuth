<?php

namespace App\Http\Middleware;

use League\OAuth2\Server\Middleware\ResourceServerMiddleware as LeagueResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;

class AccessMiddleware extends LeagueResourceServerMiddleware
{
    public function __construct(AccessTokenRepository $accessTokenRepository)
    {
        $publicKeyPath = env('APP_PUBLIC_KEY');
        parent::__construct(new ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        ));
    }
}
