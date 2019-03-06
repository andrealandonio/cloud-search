<?php

namespace WP_Cloud_Search\Guzzle\Log;

use WP_Cloud_Search\Zend\Log\Logger;
/**
 * Adapts a Zend Framework 2 logger object
 */
class Zf2LogAdapter extends \WP_Cloud_Search\Guzzle\Log\AbstractLogAdapter
{
    public function __construct(\WP_Cloud_Search\Zend\Log\Logger $logObject)
    {
        $this->log = $logObject;
    }
    public function log($message, $priority = \LOG_INFO, $extras = array())
    {
        $this->log->log($priority, $message, $extras);
    }
}
