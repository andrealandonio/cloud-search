<?php

namespace WP_Cloud_Search\Guzzle\Cache;

use WP_Cloud_Search\Guzzle\Common\Version;
/**
 * Zend Framework 1 cache adapter
 *
 * @link http://framework.zend.com/manual/en/zend.cache.html
 * @deprecated
 * @codeCoverageIgnore
 */
class Zf1CacheAdapter extends \WP_Cloud_Search\Guzzle\Cache\AbstractCacheAdapter
{
    /**
     * @param \Zend_Cache_Backend $cache Cache object to wrap
     */
    public function __construct(\WP_Cloud_Search\Zend_Cache_Backend $cache)
    {
        \WP_Cloud_Search\Guzzle\Common\Version::warn(__CLASS__ . ' is deprecated. Upgrade to ZF2 or use PsrCacheAdapter');
        $this->cache = $cache;
    }
    public function contains($id, array $options = null)
    {
        return $this->cache->test($id);
    }
    public function delete($id, array $options = null)
    {
        return $this->cache->remove($id);
    }
    public function fetch($id, array $options = null)
    {
        return $this->cache->load($id);
    }
    public function save($id, $data, $lifeTime = \false, array $options = null)
    {
        return $this->cache->save($data, $id, array(), $lifeTime);
    }
}
