<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response;

use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
/**
 * Visitor used to add the body of a response to a particular key
 */
class BodyVisitor extends \WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response\AbstractResponseVisitor
{
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\Response $response, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, &$value, $context = null)
    {
        $value[$param->getName()] = $param->filter($response->getBody());
    }
}
