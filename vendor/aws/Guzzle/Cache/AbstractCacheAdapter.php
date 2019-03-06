<?php

namespace WP_Cloud_Search\Guzzle\Cache;

/**
 * Abstract cache adapter
 */
abstract class AbstractCacheAdapter implements \WP_Cloud_Search\Guzzle\Cache\CacheAdapterInterface
{
    protected $cache;
    /**
     * Get the object owned by the adapter
     *
     * @return mixed
     */
    public function getCacheObject()
    {
        return $this->cache;
    }
}
