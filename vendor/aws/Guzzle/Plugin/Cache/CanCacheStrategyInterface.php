<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Cache;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Strategy used to determine if a request can be cached
 */
interface CanCacheStrategyInterface
{
    /**
     * Determine if a request can be cached
     *
     * @param RequestInterface $request Request to determine
     *
     * @return bool
     */
    public function canCacheRequest(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request);
    /**
     * Determine if a response can be cached
     *
     * @param Response $response Response to determine
     *
     * @return bool
     */
    public function canCacheResponse(\WP_Cloud_Search\Guzzle\Http\Message\Response $response);
}
