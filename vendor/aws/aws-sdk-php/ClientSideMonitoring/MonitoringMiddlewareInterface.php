<?php

namespace WP_Cloud_Search\Aws\ClientSideMonitoring;

use WP_Cloud_Search\Aws\CommandInterface;
use WP_Cloud_Search\Aws\Exception\AwsException;
use WP_Cloud_Search\Aws\ResultInterface;
use WP_Cloud_Search\GuzzleHttp\Psr7\Request;
use WP_Cloud_Search\Psr\Http\Message\RequestInterface;
/**
 * @internal
 */
interface MonitoringMiddlewareInterface
{
    /**
     * Data for event properties to be sent to the monitoring agent.
     *
     * @param RequestInterface $request
     * @return array
     */
    public static function getRequestData(RequestInterface $request);
    /**
     * Data for event properties to be sent to the monitoring agent.
     *
     * @param ResultInterface|AwsException|\Exception $klass
     * @return array
     */
    public static function getResponseData($klass);
    public function __invoke(CommandInterface $cmd, RequestInterface $request);
}
