<?php

namespace WP_Cloud_Search\Aws\Exception;

use WP_Cloud_Search\Aws\HasMonitoringEventsTrait;
use WP_Cloud_Search\Aws\MonitoringEventsInterface;
class UnresolvedApiException extends \RuntimeException implements MonitoringEventsInterface
{
    use HasMonitoringEventsTrait;
}
