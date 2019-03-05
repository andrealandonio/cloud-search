<?php

namespace WP_Cloud_Search\Guzzle\Log;

use WP_Cloud_Search\Guzzle\Common\Version;
/**
 * Adapts a Zend Framework 1 logger object
 * @deprecated
 * @codeCoverageIgnore
 */
class Zf1LogAdapter extends \WP_Cloud_Search\Guzzle\Log\AbstractLogAdapter
{
    public function __construct(\WP_Cloud_Search\Zend_Log $logObject)
    {
        $this->log = $logObject;
        \WP_Cloud_Search\Guzzle\Common\Version::warn(__CLASS__ . ' is deprecated');
    }
    public function log($message, $priority = \LOG_INFO, $extras = array())
    {
        $this->log->log($message, $priority, $extras);
    }
}
