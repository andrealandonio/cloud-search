<?php

namespace WP_Cloud_Search\Guzzle\Plugin\ErrorResponse;

use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Interface used to create an exception from an error response
 */
interface ErrorResponseExceptionInterface
{
    /**
     * Create an exception for a command based on a command and an error response definition
     *
     * @param CommandInterface $command  Command that was sent
     * @param Response         $response The error response
     *
     * @return self
     */
    public static function fromCommand(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\Response $response);
}
