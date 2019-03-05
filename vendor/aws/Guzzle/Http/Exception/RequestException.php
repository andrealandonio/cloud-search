<?php

namespace WP_Cloud_Search\Guzzle\Http\Exception;

use WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException;
use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
/**
 * Http request exception
 */
class RequestException extends \WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException implements \WP_Cloud_Search\Guzzle\Http\Exception\HttpException
{
    /** @var RequestInterface */
    protected $request;
    /**
     * Set the request that caused the exception
     *
     * @param RequestInterface $request Request to set
     *
     * @return RequestException
     */
    public function setRequest(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request)
    {
        $this->request = $request;
        return $this;
    }
    /**
     * Get the request that caused the exception
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
