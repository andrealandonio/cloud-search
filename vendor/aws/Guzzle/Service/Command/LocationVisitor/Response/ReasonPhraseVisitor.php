<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response;

use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
/**
 * Location visitor used to add the reason phrase of a response to a key in the result
 */
class ReasonPhraseVisitor extends \WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response\AbstractResponseVisitor
{
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\Response $response, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, &$value, $context = null)
    {
        $value[$param->getName()] = $response->getReasonPhrase();
    }
}
