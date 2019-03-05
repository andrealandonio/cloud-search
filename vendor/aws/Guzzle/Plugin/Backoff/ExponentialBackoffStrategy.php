<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Backoff;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Http\Exception\HttpException;
/**
 * Implements an exponential backoff retry strategy.
 *
 * Warning: If no decision making strategies precede this strategy in the the chain, then all requests will be retried
 */
class ExponentialBackoffStrategy extends \WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractBackoffStrategy
{
    public function makesDecision()
    {
        return \false;
    }
    protected function getDelay($retries, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response = null, \WP_Cloud_Search\Guzzle\Http\Exception\HttpException $e = null)
    {
        return (int) \pow(2, $retries);
    }
}
