<?php

namespace WP_Cloud_Search\Psr\Log;

/**
 * Describes a logger-aware instance
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(\WP_Cloud_Search\Psr\Log\LoggerInterface $logger);
}
