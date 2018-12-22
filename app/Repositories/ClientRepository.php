<?php

namespace App\Repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use App\Entities\ClientEntity;

class ClientRepository implements ClientRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier, $grantType = null, $clientSecret = null, $mustValidateSecret = true)
    {
        $clients = [
            'myawesomeapp' => [
                'secret'          => md5('abc123'), //password_hash('abc123', PASSWORD_BCRYPT),
                'name'            => 'My Awesome App',
                'redirect_uri'    => url('/index'),
                'is_confidential' => true,
            ],
        ];

        // Check if client is registered
        if (array_key_exists($clientIdentifier, $clients) === false) {
            return;
        }

        if (
            $mustValidateSecret === true
            && $clients[$clientIdentifier]['is_confidential'] === true
            && md5($clientSecret) !== $clients[$clientIdentifier]['secret']//password_verify($clientSecret, $clients[$clientIdentifier]['secret']) === false
        ) {
            return;
        }

        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName($clients[$clientIdentifier]['name']);
        $client->setRedirectUri($clients[$clientIdentifier]['redirect_uri']);

        return $client;
    }

    public function getClientSecret($clientIdentifier)
    {
        $clients = [
            'myawesomeapp' => [
                'secret'          => md5('abc123'), //password_hash('abc123', PASSWORD_BCRYPT),
                'name'            => 'My Awesome App',
                'redirect_uri'    => url('/index'),
                'is_confidential' => true,
            ],
        ];

        // Check if client is registered
        if (array_key_exists($clientIdentifier, $clients) === false) {
            return;
        }

        return $clients[$clientIdentifier]['secret'];
    }
}
