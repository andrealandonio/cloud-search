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
namespace WP_Cloud_Search\Aws\Common\Waiter;

use WP_Cloud_Search\Aws\Common\Client\AwsClientInterface;
use WP_Cloud_Search\Aws\Common\Exception\RuntimeException;
/**
 * Abstract waiter implementation used to wait on resources
 */
abstract class AbstractResourceWaiter extends \WP_Cloud_Search\Aws\Common\Waiter\AbstractWaiter implements \WP_Cloud_Search\Aws\Common\Waiter\ResourceWaiterInterface
{
    /**
     * @var AwsClientInterface
     */
    protected $client;
    /**
     * {@inheritdoc}
     */
    public function setClient(\WP_Cloud_Search\Aws\Common\Client\AwsClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function wait()
    {
        if (!$this->client) {
            throw new \WP_Cloud_Search\Aws\Common\Exception\RuntimeException('No client has been specified on the waiter');
        }
        parent::wait();
    }
}
