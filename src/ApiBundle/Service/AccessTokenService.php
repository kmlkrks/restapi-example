<?php

namespace ApiBundle\Service;

use Doctrine\Common\Cache\Cache;

/**
 * Class AccessTokenService
 * @package ApiBundle\Service
 */
class AccessTokenService
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(
        Cache $cache
    ) {
        $this->cache = $cache;
    }

    /**
     * @return string
     */
    public function generateAccessToken()
    {
        return md5(uniqid());
    }

    /**
     * @param $accessToken
     * @param $username
     * @return bool
     */
    public function insertAccessToken($accessToken, $username)
    {
        return $this->cache->save($accessToken, $username, 3600);
    }

    /**
     * @param $accessToken
     * @return mixed
     */
    public function getUser($accessToken)
    {
        return $this->cache->fetch($accessToken);
    }
} 