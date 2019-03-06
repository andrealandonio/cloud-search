<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Backoff;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Http\Exception\HttpException;
/**
 * Strategy that will not retry more than a certain number of times.
 */
class TruncatedBackoffStrategy extends \WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractBackoffStrategy
{
    /** @var int Maximum number of retries per request */
    protected $max;
    /**
     * @param int                      $maxRetries Maximum number of retries per request
     * @param BackoffStrategyInterface $next The optional next strategy
     */
    public function __construct($maxRetries, \WP_Cloud_Search\Guzzle\Plugin\Backoff\BackoffStrategyInterface $next = null)
    {
        $this->max = $maxRetries;
        $this->next = $next;
    }
    public function makesDecision()
    {
        return \true;
    }
    protected function getDelay($retries, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response = null, \WP_Cloud_Search\Guzzle\Http\Exception\HttpException $e = null)
    {
        return $retries < $this->max ? null : \false;
    }
}
