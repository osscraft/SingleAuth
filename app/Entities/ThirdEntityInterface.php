<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Entities;

interface ThirdEntityInterface
{
    /**
     * Set the third's identifier.
     */
    public function setIdentifier($identifier);

    /**
     * Get the third's identifier.
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Set the third's name.
     */
    public function setName($name);

    /**
     * Get the third's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set the third App ID.
     */
    public function setAppId($appId);

    /**
     * Get the third App ID.
     *
     * @return string
     */
    public function getAppId();

    /**
     * Set the third App Secret.
     */
    public function setAppSecret($appSecret);

    /**
     * Get the third App Secret.
     * 
     * @return string
     */
    public function getAppSecret();
}
