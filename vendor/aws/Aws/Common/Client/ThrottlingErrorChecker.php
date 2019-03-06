<?php

/**
 * Copyright 2010-2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */
namespace WP_Cloud_Search\Aws\Common\Client;

use WP_Cloud_Search\Aws\Common\Exception\Parser\ExceptionParserInterface;
use WP_Cloud_Search\Guzzle\Http\Exception\HttpException;
use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Plugin\Backoff\BackoffStrategyInterface;
use WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractBackoffStrategy;
/**
 * Backoff logic that handles throttling exceptions from services
 */
class ThrottlingErrorChecker extends \WP_Cloud_Search\Guzzle\Plugin\Backoff\AbstractBackoffStrategy
{
    /** @var array Whitelist of exception codes (as indexes) that indicate throttling */
    protected static $throttlingExceptions = array('RequestLimitExceeded' => \true, 'Throttling' => \true, 'ThrottlingException' => \true, 'ProvisionedThroughputExceededException' => \true, 'RequestThrottled' => \true);
    /**
     * @var ExceptionParserInterface Exception parser used to parse exception responses
     */
    protected $exceptionParser;
    public function __construct(\WP_Cloud_Search\Aws\Common\Exception\Parser\ExceptionParserInterface $exceptionParser, \WP_Cloud_Search\Guzzle\Plugin\Backoff\BackoffStrategyInterface $next = null)
    {
        $this->exceptionParser = $exceptionParser;
        if ($next) {
            $this->setNext($next);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function makesDecision()
    {
        return \true;
    }
    /**
     * {@inheritdoc}
     */
    protected function getDelay($retries, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Http\Message\Response $response = null, \WP_Cloud_Search\Guzzle\Http\Exception\HttpException $e = null)
    {
        if ($response && $response->isClientError()) {
            $parts = $this->exceptionParser->parse($request, $response);
            return isset(self::$throttlingExceptions[$parts['code']]) ? \true : null;
        }
    }
}
