<?php

namespace WP_Cloud_Search\Aws\Endpoint\UseFipsEndpoint\Exception;

use WP_Cloud_Search\Aws\HasMonitoringEventsTrait;
use WP_Cloud_Search\Aws\MonitoringEventsInterface;
/**
 * Represents an error interacting with configuration for useFipsRegion
 */
class ConfigurationException extends \RuntimeException implements MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
