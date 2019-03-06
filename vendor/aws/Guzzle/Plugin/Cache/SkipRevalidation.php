<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Cache;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Never performs cache revalidation and just assumes the request is still ok
 */
class SkipRevalidation extends \WP_Cloud_Search\Guzzle\Plugin\Cache\DefaultRevalidation
{
    public function __construct()
    {
    }
    public function revalidate(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response)
    {
        return \true;
    }
}
