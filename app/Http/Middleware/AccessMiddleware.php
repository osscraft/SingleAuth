<?php

namespace App\Http\Middleware;

use App\Repositories\AccessTokenRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use League\OAuth2\Server\Middleware\ResourceServerMiddleware as LeagueResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class AccessMiddleware extends LeagueResourceServerMiddleware
{
    public function __construct(AccessTokenRepository $accessTokenRepository)
    {
        $publicKeyPath = env('APP_PUBLIC_KEY');
        // $this->_bearerTokenValidator  = new BearerTokenValidator($accessTokenRepository);

        parent::__construct(new ResourceServer(
            $accessTokenRepository,
            $publicKeyPath
        ));
    }

    /**
     * @param Request $request
     * @param callable $next
     */
    public function handle(Request $request, callable $next)
    {
        return parent::__invoke(app(ServerRequestInterface::class), app(ResponseInterface::class), function($req, $res) use ($request, $next) {
            // 设置验证后的attributes
            $request->attributes->add($req->getAttributes());
            
            return $next($request);
        });
    }
}
