<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Request;

use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
/**
 * Location visitor used to add values to different locations in a request with different behaviors as needed
 */
interface RequestVisitorInterface
{
    /**
     * Called after visiting all parameters
     *
     * @param CommandInterface $command Command being visited
     * @param RequestInterface $request Request being visited
     */
    public function after(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request);
    /**
     * Called once for each parameter being visited that matches the location type
     *
     * @param CommandInterface $command Command being visited
     * @param RequestInterface $request Request being visited
     * @param Parameter        $param   Parameter being visited
     * @param mixed            $value   Value to set
     */
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, $value);
}
