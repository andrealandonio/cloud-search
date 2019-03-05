<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Cache;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Interface used to cache HTTP requests
 */
interface CacheStorageInterface
{
    /**
     * Get a Response from the cache for a request
     *
     * @param RequestInterface $request
     *
     * @return null|Response
     */
    public function fetch(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request);
    /**
     * Cache an HTTP request
     *
     * @param RequestInterface $request  Request being cached
     * @param Response         $response Response to cache
     */
    public function cache(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response);
    /**
     * Deletes cache entries that match a request
     *
     * @param RequestInterface $request Request to delete from cache
     */
    public function delete(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request);
    /**
     * Purge all cache entries for a given URL
     *
     * @param string $url
     */
    public function purge($url);
}
