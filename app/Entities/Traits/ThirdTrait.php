<?php
/**
 * @author      Lay Liaiyong <lay@liaiyong.com>
 * @copyright   Copyright (c) Lay Liaiyong
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/layliaiyong/oauth2-server
 */

namespace App\Entities\Traits;

trait ThirdTrait
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $appId;

    /**
     * @var string
     */
    protected $appSecret;

    /**
     * Set the third's name.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the third's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the third App ID.
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * Get the third App ID.
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Set the third App Secret.
     */
    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
    }

    /**
     * Get the third App Secret.
     * 
     * @return string
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }
}
