<?php

namespace App\OAuth2\Server\Repositories;

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
    public function loginTimes()
    {
        return $this->session->get('loginTimes', 0);
    }

    /**
     * {@inheritdoc}
     */
    public function incLoginTimes()
    {
        $times = $this->loginTimes();
        $this->session->put('loginTimes', ++$times);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revokeUser()
    {
        $this->session->forget('user');

        return $this;
    }
}
