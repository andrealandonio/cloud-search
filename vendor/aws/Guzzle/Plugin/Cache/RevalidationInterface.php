<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Cache;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Cache revalidation interface
 */
interface RevalidationInterface
{
    /**
     * Performs a cache revalidation
     *
     * @param RequestInterface $request    Request to revalidate
     * @param Response         $response   Response that was received
     *
     * @return bool Returns true if the request can be cached
     */
    public function revalidate(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response);
    /**
     * Returns true if the response should be revalidated
     *
     * @param RequestInterface $request  Request to check
     * @param Response         $response Response to check
     *
     * @return bool
     */
    public function shouldRevalidate(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response);
}
