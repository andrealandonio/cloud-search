<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WP_Cloud_Search\Monolog\Handler;

use WP_Cloud_Search\Aws\Common\Aws;
use WP_Cloud_Search\Aws\DynamoDb\DynamoDbClient;
use WP_Cloud_Search\Monolog\Formatter\ScalarFormatter;
use WP_Cloud_Search\Monolog\Logger;
/**
 * Amazon DynamoDB handler (http://aws.amazon.com/dynamodb/)
 *
 * @link https://github.com/aws/aws-sdk-php/
 * @author Andrew Lawson <adlawson@gmail.com>
 */
class DynamoDbHandler extends \WP_Cloud_Search\Monolog\Handler\AbstractProcessingHandler
{
    const DATE_FORMAT = 'Y-m-d\\TH:i:s.uO';
    /**
     * @var DynamoDbClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $table;
    /**
     * @param DynamoDbClient $client
     * @param string         $table
     * @param int            $level
     * @param bool           $bubble
     */
    public function __construct(\WP_Cloud_Search\Aws\DynamoDb\DynamoDbClient $client, $table, $level = \WP_Cloud_Search\Monolog\Logger::DEBUG, $bubble = \true)
    {
        if (!\defined('Aws\\Common\\Aws::VERSION') || \version_compare('3.0', \WP_Cloud_Search\Aws\Common\Aws::VERSION, '<=')) {
            throw new \RuntimeException('The DynamoDbHandler is only known to work with the AWS SDK 2.x releases');
        }
        $this->client = $client;
        $this->table = $table;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        $filtered = $this->filterEmptyFields($record['formatted']);
        $formatted = $this->client->formatAttributes($filtered);
        $this->client->putItem(array('TableName' => $this->table, 'Item' => $formatted));
    }
    /**
     * @param  array $record
     * @return array
     */
    protected function filterEmptyFields(array $record)
    {
        return \array_filter($record, function ($value) {
            return !empty($value) || \false === $value || 0 === $value;
        });
    }
    /**
     * {@inheritdoc}
     */
    protected function getDefaultFormatter()
    {
        return new \WP_Cloud_Search\Monolog\Formatter\ScalarFormatter(self::DATE_FORMAT);
    }
}
