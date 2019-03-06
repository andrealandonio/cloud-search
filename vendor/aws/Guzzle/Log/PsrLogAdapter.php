<?php

namespace WP_Cloud_Search\Guzzle\Log;

use WP_Cloud_Search\Psr\Log\LogLevel;
use WP_Cloud_Search\Psr\Log\LoggerInterface;
/**
 * PSR-3 log adapter
 *
 * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 */
class PsrLogAdapter extends \WP_Cloud_Search\Guzzle\Log\AbstractLogAdapter
{
    /**
     * syslog to PSR-3 mappings
     */
    private static $mapping = array(\LOG_DEBUG => \WP_Cloud_Search\Psr\Log\LogLevel::DEBUG, \LOG_INFO => \WP_Cloud_Search\Psr\Log\LogLevel::INFO, \LOG_WARNING => \WP_Cloud_Search\Psr\Log\LogLevel::WARNING, \LOG_ERR => \WP_Cloud_Search\Psr\Log\LogLevel::ERROR, \LOG_CRIT => \WP_Cloud_Search\Psr\Log\LogLevel::CRITICAL, \LOG_ALERT => \WP_Cloud_Search\Psr\Log\LogLevel::ALERT);
    public function __construct(\WP_Cloud_Search\Psr\Log\LoggerInterface $logObject)
    {
        $this->log = $logObject;
    }
    public function log($message, $priority = \LOG_INFO, $extras = array())
    {
        $this->log->log(self::$mapping[$priority], $message, $extras);
    }
}
