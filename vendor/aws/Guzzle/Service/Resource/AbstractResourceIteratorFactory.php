<?php

namespace WP_Cloud_Search\Guzzle\Service\Resource;

use WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException;
use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
/**
 * Abstract resource iterator factory implementation
 */
abstract class AbstractResourceIteratorFactory implements \WP_Cloud_Search\Guzzle\Service\Resource\ResourceIteratorFactoryInterface
{
    public function build(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, array $options = array())
    {
        if (!$this->canBuild($command)) {
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException('Iterator was not found for ' . $command->getName());
        }
        $className = $this->getClassName($command);
        return new $className($command, $options);
    }
    public function canBuild(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command)
    {
        return (bool) $this->getClassName($command);
    }
    /**
     * Get the name of the class to instantiate for the command
     *
     * @param CommandInterface $command Command that is associated with the iterator
     *
     * @return string
     */
    protected abstract function getClassName(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command);
}
