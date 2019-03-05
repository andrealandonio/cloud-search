<?php

namespace WP_Cloud_Search\Guzzle\Http\Exception;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Http request exception thrown when a bad response is received
 */
class BadResponseException extends \WP_Cloud_Search\Guzzle\Http\Exception\RequestException
{
    /** @var Response */
    private $response;
    /**
     * Factory method to create a new response exception based on the response code.
     *
     * @param RequestInterface $request  Request
     * @param Response         $response Response received
     *
     * @return BadResponseException
     */
    public static function factory(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response)
    {
        if ($response->isClientError()) {
            $label = 'Client error response';
            $class = __NAMESPACE__ . '\\ClientErrorResponseException';
        } elseif ($response->isServerError()) {
            $label = 'Server error response';
            $class = __NAMESPACE__ . '\\ServerErrorResponseException';
        } else {
            $label = 'Unsuccessful response';
            $class = __CLASS__;
        }
        $message = $label . \PHP_EOL . \implode(\PHP_EOL, array('[status code] ' . $response->getStatusCode(), '[reason phrase] ' . $response->getReasonPhrase(), '[url] ' . $request->getUrl()));
        $e = new $class($message);
        $e->setResponse($response);
        $e->setRequest($request);
        return $e;
    }
    /**
     * Set the response that caused the exception
     *
     * @param Response $response Response to set
     */
    public function setResponse(\WP_Cloud_Search\Guzzle\Http\Message\Response $response)
    {
        $this->response = $response;
    }
    /**
     * Get the response that caused the exception
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
