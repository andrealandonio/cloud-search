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
namespace WP_Cloud_Search\Aws\Common\Command;

use WP_Cloud_Search\Guzzle\Service\Command\OperationCommand;
/**
 * Adds AWS Query service serialization
 */
class QueryCommand extends \WP_Cloud_Search\Guzzle\Service\Command\OperationCommand
{
    /**
     * @var AwsQueryVisitor
     */
    protected static $queryVisitor;
    /**
     * @var XmlResponseLocationVisitor
     */
    protected static $xmlVisitor;
    /**
     * Register the aws.query visitor
     */
    protected function init()
    {
        // @codeCoverageIgnoreStart
        if (!self::$queryVisitor) {
            self::$queryVisitor = new \WP_Cloud_Search\Aws\Common\Command\AwsQueryVisitor();
        }
        if (!self::$xmlVisitor) {
            self::$xmlVisitor = new \WP_Cloud_Search\Aws\Common\Command\XmlResponseLocationVisitor();
        }
        // @codeCoverageIgnoreEnd
        $this->getRequestSerializer()->addVisitor('aws.query', self::$queryVisitor);
        $this->getResponseParser()->addVisitor('xml', self::$xmlVisitor);
    }
}
