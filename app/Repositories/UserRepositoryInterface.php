<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace App\Repositories;

use App\Entities\ThirdEntityInterface;
use App\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface as LeagueUserRepositoryInterface;

interface UserRepositoryInterface extends LeagueUserRepositoryInterface
{
    /**
     * Bind the third application
     * 
     * @return boolean
     */
    public function bind(UserEntityInterface $user, ThirdEntityInterface $third);
    /**
     * Unbind the third application
     * 
     * @return boolean
     */
    public function unbind(UserEntityInterface $user, ThirdEntityInterface $third);
    /**
     * If bind the third application
     * 
     * @return boolean
     */
    public function isBound(UserEntityInterface $user, ThirdEntityInterface $third);
}
