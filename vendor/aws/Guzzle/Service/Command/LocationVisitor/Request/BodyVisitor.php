<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Request;

use WP_Cloud_Search\Guzzle\Http\EntityBody;
use WP_Cloud_Search\Guzzle\Http\Message\EntityEnclosingRequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\EntityBodyInterface;
use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
/**
 * Visitor used to apply a body to a request
 *
 * This visitor can use a data parameter of 'expect' to control the Expect header. Set the expect data parameter to
 * false to disable the expect header, or set the value to an integer so that the expect 100-continue header is only
 * added if the Content-Length of the entity body is greater than the value.
 */
class BodyVisitor extends \WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Request\AbstractRequestVisitor
{
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, $value)
    {
        $value = $param->filter($value);
        $entityBody = \WP_Cloud_Search\Guzzle\Http\EntityBody::factory($value);
        $request->setBody($entityBody);
        $this->addExpectHeader($request, $entityBody, $param->getData('expect_header'));
        // Add the Content-Encoding header if one is set on the EntityBody
        if ($encoding = $entityBody->getContentEncoding()) {
            $request->setHeader('Content-Encoding', $encoding);
        }
    }
    /**
     * Add the appropriate expect header to a request
     *
     * @param EntityEnclosingRequestInterface $request Request to update
     * @param EntityBodyInterface             $body    Entity body of the request
     * @param string|int                      $expect  Expect header setting
     */
    protected function addExpectHeader(\WP_Cloud_Search\Guzzle\Http\Message\EntityEnclosingRequestInterface $request, \WP_Cloud_Search\Guzzle\Http\EntityBodyInterface $body, $expect)
    {
        // Allow the `expect` data parameter to be set to remove the Expect header from the request
        if ($expect === \false) {
            $request->removeHeader('Expect');
        } elseif ($expect !== \true) {
            // Default to using a MB as the point in which to start using the expect header
            $expect = $expect ?: 1048576;
            // If the expect_header value is numeric then only add if the size is greater than the cutoff
            if (\is_numeric($expect) && $body->getSize()) {
                if ($body->getSize() < $expect) {
                    $request->removeHeader('Expect');
                } else {
                    $request->setHeader('Expect', '100-Continue');
                }
            }
        }
    }
}
