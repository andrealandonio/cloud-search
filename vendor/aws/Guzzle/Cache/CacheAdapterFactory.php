<?php

namespace WP_Cloud_Search\Guzzle\Cache;

use WP_Cloud_Search\Doctrine\Common\Cache\Cache;
use WP_Cloud_Search\Guzzle\Common\Version;
use WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException;
use WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException;
use WP_Cloud_Search\Guzzle\Common\FromConfigInterface;
use WP_Cloud_Search\Zend\Cache\Storage\StorageInterface;
/**
 * Generates cache adapters from any number of known cache implementations
 */
class CacheAdapterFactory implements \WP_Cloud_Search\Guzzle\Common\FromConfigInterface
{
    /**
     * Create a Guzzle cache adapter based on an array of options
     *
     * @param mixed $cache Cache value
     *
     * @return CacheAdapterInterface
     * @throws InvalidArgumentException
     */
    public static function fromCache($cache)
    {
        if (!\is_object($cache)) {
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException('Cache must be one of the known cache objects');
        }
        if ($cache instanceof \WP_Cloud_Search\Guzzle\Cache\CacheAdapterInterface) {
            return $cache;
        } elseif ($cache instanceof \WP_Cloud_Search\Doctrine\Common\Cache\Cache) {
            return new \WP_Cloud_Search\Guzzle\Cache\DoctrineCacheAdapter($cache);
        } elseif ($cache instanceof \WP_Cloud_Search\Zend\Cache\Storage\StorageInterface) {
            return new \WP_Cloud_Search\Guzzle\Cache\Zf2CacheAdapter($cache);
        } else {
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException('Unknown cache type: ' . \get_class($cache));
        }
    }
    /**
     * Create a Guzzle cache adapter based on an array of options
     *
     * @param array $config Array of configuration options
     *
     * @return CacheAdapterInterface
     * @throws InvalidArgumentException
     * @deprecated This will be removed in a future version
     * @codeCoverageIgnore
     */
    public static function factory($config = array())
    {
        \WP_Cloud_Search\Guzzle\Common\Version::warn(__METHOD__ . ' is deprecated');
        if (!\is_array($config)) {
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException('$config must be an array');
        }
        if (!isset($config['cache.adapter']) && !isset($config['cache.provider'])) {
            $config['cache.adapter'] = 'WP_Cloud_Search\\Guzzle\\Cache\\NullCacheAdapter';
            $config['cache.provider'] = null;
        } else {
            // Validate that the options are valid
            foreach (array('cache.adapter', 'cache.provider') as $required) {
                if (!isset($config[$required])) {
                    throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException("{$required} is a required CacheAdapterFactory option");
                }
                if (\is_string($config[$required])) {
                    // Convert dot notation to namespaces
                    $config[$required] = \str_replace('.', '\\', $config[$required]);
                    if (!\class_exists($config[$required])) {
                        throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException("{$config[$required]} is not a valid class for {$required}");
                    }
                }
            }
            // Instantiate the cache provider
            if (\is_string($config['cache.provider'])) {
                $args = isset($config['cache.provider.args']) ? $config['cache.provider.args'] : null;
                $config['cache.provider'] = self::createObject($config['cache.provider'], $args);
            }
        }
        // Instantiate the cache adapter using the provider and options
        if (\is_string($config['cache.adapter'])) {
            $args = isset($config['cache.adapter.args']) ? $config['cache.adapter.args'] : array();
            \array_unshift($args, $config['cache.provider']);
            $config['cache.adapter'] = self::createObject($config['cache.adapter'], $args);
        }
        return $config['cache.adapter'];
    }
    /**
     * Create a class using an array of constructor arguments
     *
     * @param string $className Class name
     * @param array  $args      Arguments for the class constructor
     *
     * @return mixed
     * @throws RuntimeException
     * @deprecated
     * @codeCoverageIgnore
     */
    private static function createObject($className, array $args = null)
    {
        try {
            if (!$args) {
                return new $className();
            } else {
                $c = new \ReflectionClass($className);
                return $c->newInstanceArgs($args);
            }
        } catch (\Exception $e) {
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
