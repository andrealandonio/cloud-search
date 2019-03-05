<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Backoff;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Http\Exception\HttpException;
/**
 * Strategy used to retry HTTP requests based on the response code.
 *
 * Retries 500 and 503 error by default.
 */
class HttpBackoffStrategy extends \WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractErrorCodeBackoffStrategy
{
    /** @var array Default cURL errors to retry */
    protected static $defaultErrorCodes = array(500, 503);
    protected function getDelay($retries, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response = null, \WP_Cloud_Search\Guzzle\Http\Exception\HttpException $e = null)
    {
        if ($response) {
            //Short circuit the rest of the checks if it was successful
            if ($response->isSuccessful()) {
                return \false;
            } else {
                return isset($this->errorCodes[$response->getStatusCode()]) ? \true : null;
            }
        }
    }
}
