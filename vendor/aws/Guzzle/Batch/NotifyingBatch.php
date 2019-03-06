<?php

namespace WP_Cloud_Search\Guzzle\Batch;

use WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException;
/**
 * BatchInterface decorator used to call a method each time flush is called
 */
class NotifyingBatch extends \WP_Cloud_Search\Guzzle\Batch\AbstractBatchDecorator
{
    /** @var mixed Callable to call */
    protected $callable;
    /**
     * @param BatchInterface $decoratedBatch Batch object to decorate
     * @param mixed          $callable       Callable to call
     *
     * @throws InvalidArgumentException
     */
    public function __construct(\WP_Cloud_Search\Guzzle\Batch\BatchInterface $decoratedBatch, $callable)
    {
        if (!\is_callable($callable)) {
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\InvalidArgumentException('The passed argument is not callable');
        }
        $this->callable = $callable;
        parent::__construct($decoratedBatch);
    }
    public function flush()
    {
        $items = $this->decoratedBatch->flush();
        \call_user_func($this->callable, $items);
        return $items;
    }
}
