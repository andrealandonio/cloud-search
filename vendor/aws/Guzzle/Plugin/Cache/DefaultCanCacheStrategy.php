<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Cache;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Default strategy used to determine of an HTTP request can be cached
 */
class DefaultCanCacheStrategy implements \WP_Cloud_Search\Guzzle\Plugin\Cache\CanCacheStrategyInterface
{
    public function canCacheRequest(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request)
    {
        // Only GET and HEAD requests can be cached
        if ($request->getMethod() != \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface::GET && $request->getMethod() != \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface::HEAD) {
            return \false;
        }
        // Never cache requests when using no-store
        if ($request->hasHeader('Cache-Control') && $request->getHeader('Cache-Control')->hasDirective('no-store')) {
            return \false;
        }
        return \true;
    }
    public function canCacheResponse(\WP_Cloud_Search\Guzzle\Http\Message\Response $response)
    {
        return $response->isSuccessful() && $response->canCache();
    }
}
