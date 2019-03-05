<?php

namespace WP_Cloud_Search\Guzzle\Cache;

use WP_Cloud_Search\Zend\Cache\Storage\StorageInterface;
/**
 * Zend Framework 2 cache adapter
 *
 * @link http://packages.zendframework.com/docs/latest/manual/en/zend.cache.html
 */
class Zf2CacheAdapter extends \WP_Cloud_Search\Guzzle\Cache\AbstractCacheAdapter
{
    /**
     * @param StorageInterface $cache Zend Framework 2 cache adapter
     */
    public function __construct(\WP_Cloud_Search\Zend\Cache\Storage\StorageInterface $cache)
    {
        $this->cache = $cache;
    }
    public function contains($id, array $options = null)
    {
        return $this->cache->hasItem($id);
    }
    public function delete($id, array $options = null)
    {
        return $this->cache->removeItem($id);
    }
    public function fetch($id, array $options = null)
    {
        return $this->cache->getItem($id);
    }
    public function save($id, $data, $lifeTime = \false, array $options = null)
    {
        return $this->cache->setItem($id, $data);
    }
}
