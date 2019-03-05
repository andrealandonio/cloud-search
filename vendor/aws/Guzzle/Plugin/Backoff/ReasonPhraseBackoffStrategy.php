<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Backoff;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Http\Exception\HttpException;
/**
 * Strategy used to retry HTTP requests when the response's reason phrase matches one of the registered phrases.
 */
class ReasonPhraseBackoffStrategy extends \WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractErrorCodeBackoffStrategy
{
    public function makesDecision()
    {
        return \true;
    }
    protected function getDelay($retries, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response = null, \WP_Cloud_Search\Guzzle\Http\Exception\HttpException $e = null)
    {
        if ($response) {
            return isset($this->errorCodes[$response->getReasonPhrase()]) ? \true : null;
        }
    }
}
