<?php

namespace WP_Cloud_Search\Guzzle\Service\Command;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
/**
 * Translates command options and operation parameters into a request object
 */
interface RequestSerializerInterface
{
    /**
     * Create a request for a command
     *
     * @param CommandInterface $command Command that will own the request
     *
     * @return RequestInterface
     */
    public function prepare(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command);
}
