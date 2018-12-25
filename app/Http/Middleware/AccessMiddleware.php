<?php

namespace App\Http\Middleware;

use App\Repositories\AccessTokenRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware as LeagueResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

    /**
     * @param Request $request
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function handle(Request $request, callable $next)
    {
        return parent::__invoke(app(ServerRequestInterface::class), app(ResponseInterface::class), $next);
    }
}
