<?php

namespace App\OAuth2;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LogoutServer extends AuthorizationServer
{

    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;
    
    /**
     * New server instance.
     *
     * @param ClientRepositoryInterface      $clientRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    
    /**
     * Retrieve query string parameter.
     *
     * @param string                 $parameter
     * @param ServerRequestInterface $request
     * @param mixed                  $default
     *
     * @return null|string
     */
    protected function getQueryStringParameter($parameter, ServerRequestInterface $request, $default = null)
    {
        return isset($request->getQueryParams()[$parameter]) ? $request->getQueryParams()[$parameter] : $default;
    }
    
    /**
     * Retrieve server parameter.
     *
     * @param string                 $parameter
     * @param ServerRequestInterface $request
     * @param mixed                  $default
     *
     * @return null|string
     */
    protected function getServerParameter($parameter, ServerRequestInterface $request, $default = null)
    {
        return isset($request->getServerParams()[$parameter]) ? $request->getServerParams()[$parameter] : $default;
    }

    /**
     * Return the grant identifier that can be used in matching up requests.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return 'authorization_code';
    }
    
    /**
     * {@inheritdoc}
     */
    public function canRespondToLogoutRequest(ServerRequestInterface $request)
    {
        return (
            array_key_exists('response_type', $request->getQueryParams())
            && $request->getQueryParams()['response_type'] === 'code'
            && isset($request->getQueryParams()['client_id'])
        );
    }

    /**
     * Validate an authorization request
     *
     * @param ServerRequestInterface $request
     *
     * @throws OAuthServerException
     *
     * @return LogoutRequest
     */
    public function validateLogoutRequest(ServerRequestInterface $request)
    {
        $clientId = $this->getQueryStringParameter(
            'client_id',
            $request,
            $this->getServerParameter('PHP_AUTH_USER', $request)
        );

        if (is_null($clientId)) {
            throw OAuthServerException::invalidRequest('client_id');
        }

        $client = $this->clientRepository->getClientEntity(
            $clientId,
            $this->getIdentifier(),
            null,
            false
        );

        if ($client instanceof ClientEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidClient();
        }

        $redirectUri = $this->getQueryStringParameter('redirect_uri', $request);

        if ($redirectUri !== null) {
            $this->validateRedirectUri($redirectUri, $client, $request);
        } elseif (is_array($client->getRedirectUri()) && count($client->getRedirectUri()) !== 1
            || empty($client->getRedirectUri())) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::CLIENT_AUTHENTICATION_FAILED, $request));
            throw OAuthServerException::invalidClient();
        } else {
            $redirectUri = is_array($client->getRedirectUri())
                ? $client->getRedirectUri()[0]
                : $client->getRedirectUri();
        }

        $scopes = $this->validateScopes(
            $this->getQueryStringParameter('scope', $request, $this->defaultScope),
            $redirectUri
        );

        $stateParameter = $this->getQueryStringParameter('state', $request);

        $authorizationRequest = new AuthorizationRequest();
        $authorizationRequest->setGrantTypeId($this->getIdentifier());
        $authorizationRequest->setClient($client);
        $authorizationRequest->setRedirectUri($redirectUri);

        if ($stateParameter !== null) {
            $authorizationRequest->setState($stateParameter);
        }

        $authorizationRequest->setScopes($scopes);

        return $authorizationRequest;
    }
}