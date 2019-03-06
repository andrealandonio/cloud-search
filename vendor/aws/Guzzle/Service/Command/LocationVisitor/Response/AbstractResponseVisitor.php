<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response;

use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
/**
 * {@inheritdoc}
 * @codeCoverageIgnore
 */
abstract class AbstractResponseVisitor implements \WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response\ResponseVisitorInterface
{
    public function before(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, array &$result)
    {
    }
    public function after(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command)
    {
    }
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\Response $response, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, &$value, $context = null)
    {
    }
}
