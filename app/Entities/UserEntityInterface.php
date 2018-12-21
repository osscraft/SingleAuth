<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Entities;

use League\OAuth2\Server\Entities\UserEntityInterface as LeagueUserEntityInterface;

interface UserEntityInterface extends LeagueUserEntityInterface
{
    /**
     * Set the third's identifier.
     */
    public function setIdentifier($identifier);

    /**
     * Set the user's name.
     */
    public function setName($name);

    /**
     * Get the user's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set the username.
     */
    public function setUsername($username);

    /**
     * Get the username.
     *
     * @return string
     */
    public function getUsername();
}
