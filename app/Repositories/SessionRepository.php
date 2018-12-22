<?php

namespace App\Repositories;

use App\Entities\ThirdEntityInterface;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use League\OAuth2\Server\Entities\UserEntityInterface;

class SessionRepository implements SessionRepositoryInterface
{
    /**
     * @var Store
     */
    private $session;

    public function __construct(Request $request)
    {
        $this->session = $request->session();
    }
    /**
     * {@inheritdoc}
     */
    public function persistUser(UserEntityInterface $userEntity)
    {
        $this->session->put('user', $userEntity);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasUser()
    {
        $user = $this->session->get('user', null);

        return empty($user) ? false : true;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->session->get('user', null);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeUser()
    {
        $this->session->forget('user');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginCount()
    {
        return $this->session->get('loginCount', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function incLoginCount()
    {
        $times = $this->getLoginCount();
        $this->session->put('loginCount', ++$times);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revokeLoginCount()
    {
        $this->session->forget('loginCount');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastAttemptTime()
    {
        return $this->session->get('lastAttemptTime', null);
    }

    /**
     * {@inheritdoc}
     */
    public function persistLastAttemptTime()
    {
        $this->session->put('lastAttemptTime', time());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revokeLastAttemptTime()
    {
        $this->session->forget('lastAttemptTime');

        return $this;
    }

    /**
     * @param ThirdEntityInterface $third
     */
    public function getThirdUser($third)
    {
        $user = $this->session->get("third.{$third->getIdentifier()}.user", null);

        return empty($user) ? null : json_decode($user);
    }

    /**
     * @param ThirdEntityInterface $third
     */
    public function pesistThirdUser($third, $user)
    {
        $this->session->put("third.{$third->getIdentifier()}.user", json_encode($user));

        return $this;
    }

    /**
     * @param ThirdEntityInterface $third
     */
    public function revokeThirdUser($third)
    {
        $this->session->forget("third.{$third->getIdentifier()}.user");

        return $this;
    }

    public function getToken()
    {
        return $this->session->get('token', null);
    }

    public function pesistToken($token)
    {
        $this->session->put('token', $token);

        return $this;
    }

    public function revokeToken($token)
    {
        $this->session->forget('token');

        return $this;
    }
}
