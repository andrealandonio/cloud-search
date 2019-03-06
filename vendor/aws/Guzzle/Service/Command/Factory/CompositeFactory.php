<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\Factory;

use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Service\ClientInterface;
/**
 * Composite factory used by a client object to create command objects utilizing multiple factories
 */
class CompositeFactory implements \IteratorAggregate, \Countable, \WP_Cloud_Search\Guzzle\Service\Command\Factory\FactoryInterface
{
    /** @var array Array of command factories */
    protected $factories;
    /**
     * Get the default chain to use with clients
     *
     * @param ClientInterface $client Client to base the chain on
     *
     * @return self
     */
    public static function getDefaultChain(\WP_Cloud_Search\Guzzle\Service\ClientInterface $client)
    {
        $factories = array();
        if ($description = $client->getDescription()) {
            $factories[] = new \WP_Cloud_Search\Guzzle\Service\Command\Factory\ServiceDescriptionFactory($description);
        }
        $factories[] = new \WP_Cloud_Search\Guzzle\Service\Command\Factory\ConcreteClassFactory($client);
        return new self($factories);
    }
    /**
     * @param array $factories Array of command factories
     */
    public function __construct(array $factories = array())
    {
        $this->factories = $factories;
    }
    /**
     * Add a command factory to the chain
     *
     * @param FactoryInterface        $factory Factory to add
     * @param string|FactoryInterface $before  Insert the new command factory before a command factory class or object
     *                                         matching a class name.
     * @return CompositeFactory
     */
    public function add(\WP_Cloud_Search\Guzzle\Service\Command\Factory\FactoryInterface $factory, $before = null)
    {
        $pos = null;
        if ($before) {
            foreach ($this->factories as $i => $f) {
                if ($before instanceof \WP_Cloud_Search\Guzzle\Service\Command\Factory\FactoryInterface) {
                    if ($f === $before) {
                        $pos = $i;
                        break;
                    }
                } elseif (\is_string($before)) {
                    if ($f instanceof $before) {
                        $pos = $i;
                        break;
                    }
                }
            }
        }
        if ($pos === null) {
            $this->factories[] = $factory;
        } else {
            \array_splice($this->factories, $i, 0, array($factory));
        }
        return $this;
    }
    /**
     * Check if the chain contains a specific command factory
     *
     * @param FactoryInterface|string $factory Factory to check
     *
     * @return bool
     */
    public function has($factory)
    {
        return (bool) $this->find($factory);
    }
    /**
     * Remove a specific command factory from the chain
     *
     * @param string|FactoryInterface $factory Factory to remove by name or instance
     *
     * @return CompositeFactory
     */
    public function remove($factory = null)
    {
        if (!$factory instanceof \WP_Cloud_Search\Guzzle\Service\Command\Factory\FactoryInterface) {
            $factory = $this->find($factory);
        }
        $this->factories = \array_values(\array_filter($this->factories, function ($f) use($factory) {
            return $f !== $factory;
        }));
        return $this;
    }
    /**
     * Get a command factory by class name
     *
     * @param string|FactoryInterface $factory Command factory class or instance
     *
     * @return null|FactoryInterface
     */
    public function find($factory)
    {
        foreach ($this->factories as $f) {
            if ($factory === $f || \is_string($factory) && $f instanceof $factory) {
                return $f;
            }
        }
    }
    /**
     * Create a command using the associated command factories
     *
     * @param string $name Name of the command
     * @param array  $args Command arguments
     *
     * @return CommandInterface
     */
    public function factory($name, array $args = array())
    {
        foreach ($this->factories as $factory) {
            $command = $factory->factory($name, $args);
            if ($command) {
                return $command;
            }
        }
    }
    public function count()
    {
        return \count($this->factories);
    }
    public function getIterator()
    {
        return new \ArrayIterator($this->factories);
    }
}
