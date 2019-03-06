<?php

namespace WP_Cloud_Search\Guzzle\Http\Curl;

use WP_Cloud_Search\Guzzle\Common\HasDispatcherInterface;
use WP_Cloud_Search\Guzzle\Common\Exception\ExceptionCollection;
use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
/**
 * Interface for sending a pool of {@see RequestInterface} objects in parallel
 */
interface CurlMultiInterface extends \Countable, \WP_Cloud_Search\Guzzle\Common\HasDispatcherInterface
{
    const POLLING_REQUEST = 'curl_multi.polling_request';
    const ADD_REQUEST = 'curl_multi.add_request';
    const REMOVE_REQUEST = 'curl_multi.remove_request';
    const MULTI_EXCEPTION = 'curl_multi.exception';
    const BLOCKING = 'curl_multi.blocking';
    /**
     * Add a request to the pool.
     *
     * @param RequestInterface $request Request to add
     *
     * @return CurlMultiInterface
     */
    public function add(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request);
    /**
     * Get an array of attached {@see RequestInterface} objects
     *
     * @return array
     */
    public function all();
    /**
     * Remove a request from the pool.
     *
     * @param RequestInterface $request Request to remove
     *
     * @return bool Returns true on success or false on failure
     */
    public function remove(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request);
    /**
     * Reset the state and remove any attached RequestInterface objects
     *
     * @param bool $hard Set to true to close and reopen any open multi handles
     */
    public function reset($hard = \false);
    /**
     * Send a pool of {@see RequestInterface} requests.
     *
     * @throws ExceptionCollection if any requests threw exceptions during the transfer.
     */
    public function send();
}
