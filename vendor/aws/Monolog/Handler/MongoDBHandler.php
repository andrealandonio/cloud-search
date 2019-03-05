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

use WP_Cloud_Search\Monolog\Logger;
use WP_Cloud_Search\Monolog\Formatter\NormalizerFormatter;
/**
 * Logs to a MongoDB database.
 *
 * usage example:
 *
 *   $log = new Logger('application');
 *   $mongodb = new MongoDBHandler(new \Mongo("mongodb://localhost:27017"), "logs", "prod");
 *   $log->pushHandler($mongodb);
 *
 * @author Thomas Tourlourat <thomas@tourlourat.com>
 */
class MongoDBHandler extends \WP_Cloud_Search\Monolog\Handler\AbstractProcessingHandler
{
    protected $mongoCollection;
    public function __construct($mongo, $database, $collection, $level = \WP_Cloud_Search\Monolog\Logger::DEBUG, $bubble = \true)
    {
        if (!($mongo instanceof \WP_Cloud_Search\MongoClient || $mongo instanceof \WP_Cloud_Search\Mongo || $mongo instanceof \WP_Cloud_Search\MongoDB\Client)) {
            throw new \InvalidArgumentException('MongoClient, Mongo or MongoDB\\Client instance required');
        }
        $this->mongoCollection = $mongo->selectCollection($database, $collection);
        parent::__construct($level, $bubble);
    }
    protected function write(array $record)
    {
        if ($this->mongoCollection instanceof \WP_Cloud_Search\MongoDB\Collection) {
            $this->mongoCollection->insertOne($record["formatted"]);
        } else {
            $this->mongoCollection->save($record["formatted"]);
        }
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new \WP_Cloud_Search\Monolog\Formatter\NormalizerFormatter();
    }
}
