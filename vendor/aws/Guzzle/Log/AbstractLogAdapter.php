<?php

namespace WP_Cloud_Search\Guzzle\Log;

/**
 * Adapter class that allows Guzzle to log data using various logging implementations
 */
abstract class AbstractLogAdapter implements \WP_Cloud_Search\Guzzle\Log\LogAdapterInterface
{
    protected $log;
    public function getLogObject()
    {
        return $this->log;
    }
}
