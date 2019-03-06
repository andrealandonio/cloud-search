<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Request;

use WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException;
use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
/**
 * Visitor used to apply a parameter to a header value
 */
class HeaderVisitor extends \WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Request\AbstractRequestVisitor
{
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, $value)
    {
        $value = $param->filter($value);
        if ($param->getType() == 'object' && $param->getAdditionalProperties() instanceof \WP_Cloud_Search\Guzzle\Service\Description\Parameter) {
            $this->addPrefixedHeaders($request, $param, $value);
        } else {
            $request->setHeader($param->getWireName(), $value);
        }
    }
    /**
     * Add a prefixed array of headers to the request
     *
     * @param RequestInterface $request Request to update
     * @param Parameter        $param   Parameter object
     * @param array            $value   Header array to add
     *
     * @throws InvalidArgumentException
     */
    protected function addPrefixedHeaders(\WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, $value)
    {
        if (!\is_array($value)) {
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException('An array of mapped headers expected, but received a single value');
        }
        $prefix = $param->getSentAs();
        foreach ($value as $headerName => $headerValue) {
            $request->setHeader($prefix . $headerName, $headerValue);
        }
    }
}
