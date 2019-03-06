<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Backoff;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Http\Exception\HttpException;
use WP_Cloud_Search\Guzzle\Http\Exception\CurlException;
/**
 * Strategy used to retry when certain cURL error codes are encountered.
 */
class CurlBackoffStrategy extends \WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractErrorCodeBackoffStrategy
{
    /** @var array Default cURL errors to retry */
    protected static $defaultErrorCodes = array(\CURLE_COULDNT_RESOLVE_HOST, \CURLE_COULDNT_CONNECT, \CURLE_PARTIAL_FILE, \CURLE_WRITE_ERROR, \CURLE_READ_ERROR, \CURLE_OPERATION_TIMEOUTED, \CURLE_SSL_CONNECT_ERROR, \CURLE_HTTP_PORT_FAILED, \CURLE_GOT_NOTHING, \CURLE_SEND_ERROR, \CURLE_RECV_ERROR);
    protected function getDelay($retries, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response = null, \WP_Cloud_Search\Guzzle\Http\Exception\HttpException $e = null)
    {
        if ($e && $e instanceof \WP_Cloud_Search\Guzzle\Http\Exception\CurlException) {
            return isset($this->errorCodes[$e->getErrorNo()]) ? \true : null;
        }
    }
}
