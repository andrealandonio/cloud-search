<?php

namespace WP_Cloud_Search\Guzzle\Log;

use WP_Cloud_Search\Monolog\Logger;
/**
 * @deprecated
 * @codeCoverageIgnore
 */
class MonologLogAdapter extends \WP_Cloud_Search\Guzzle\Log\AbstractLogAdapter
{
    /**
     * syslog to Monolog mappings
     */
    private static $mapping = array(\LOG_DEBUG => \WP_Cloud_Search\Monolog\Logger::DEBUG, \LOG_INFO => \WP_Cloud_Search\Monolog\Logger::INFO, \LOG_WARNING => \WP_Cloud_Search\Monolog\Logger::WARNING, \LOG_ERR => \WP_Cloud_Search\Monolog\Logger::ERROR, \LOG_CRIT => \WP_Cloud_Search\Monolog\Logger::CRITICAL, \LOG_ALERT => \WP_Cloud_Search\Monolog\Logger::ALERT);
    public function __construct(\WP_Cloud_Search\Monolog\Logger $logObject)
    {
        $this->log = $logObject;
    }
    public function log($message, $priority = \LOG_INFO, $extras = array())
    {
        $this->log->addRecord(self::$mapping[$priority], $message, $extras);
    }
}
