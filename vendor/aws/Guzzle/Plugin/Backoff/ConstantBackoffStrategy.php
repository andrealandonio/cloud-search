<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Backoff;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Http\Exception\HttpException;
/**
 * Will retry the request using the same amount of delay for each retry.
 *
 * Warning: If no decision making strategies precede this strategy in the the chain, then all requests will be retried
 */
class ConstantBackoffStrategy extends \WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractBackoffStrategy
{
    /** @var int Amount of time for each delay */
    protected $delay;
    /** @param int $delay Amount of time to delay between each additional backoff */
    public function __construct($delay)
    {
        $this->delay = $delay;
    }
    public function makesDecision()
    {
        return \false;
    }
    protected function getDelay($retries, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response = null, \WP_Cloud_Search\Guzzle\Http\Exception\HttpException $e = null)
    {
        return $this->delay;
    }
}
