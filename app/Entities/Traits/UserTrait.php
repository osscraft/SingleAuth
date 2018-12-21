<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Entities\Traits;

trait UserTrait
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $username;

    /**
     * Set the user's name.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the username.
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get the username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
