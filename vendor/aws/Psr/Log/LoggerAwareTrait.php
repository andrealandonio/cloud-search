<?php

namespace WP_Cloud_Search\Psr\Log;

/**
 * Basic Implementation of LoggerAwareInterface.
 */
trait LoggerAwareTrait
{
    /** @var LoggerInterface */
    protected $logger;
    /**
     * Sets a logger.
     * 
     * @param LoggerInterface $logger
     */
    public function setLogger(\WP_Cloud_Search\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
