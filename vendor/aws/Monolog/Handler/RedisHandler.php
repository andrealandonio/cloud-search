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

use WP_Cloud_Search\Monolog\Formatter\LineFormatter;
use WP_Cloud_Search\Monolog\Logger;
/**
 * Logs to a Redis key using rpush
 *
 * usage example:
 *
 *   $log = new Logger('application');
 *   $redis = new RedisHandler(new Predis\Client("tcp://localhost:6379"), "logs", "prod");
 *   $log->pushHandler($redis);
 *
 * @author Thomas Tourlourat <thomas@tourlourat.com>
 */
class RedisHandler extends \WP_Cloud_Search\Monolog\Handler\AbstractProcessingHandler
{
    private $redisClient;
    private $redisKey;
    protected $capSize;
    /**
     * @param \Predis\Client|\Redis $redis   The redis instance
     * @param string                $key     The key name to push records to
     * @param int                   $level   The minimum logging level at which this handler will be triggered
     * @param bool                  $bubble  Whether the messages that are handled can bubble up the stack or not
     * @param int                   $capSize Number of entries to limit list size to
     */
    public function __construct($redis, $key, $level = \WP_Cloud_Search\Monolog\Logger::DEBUG, $bubble = \true, $capSize = \false)
    {
        if (!($redis instanceof \WP_Cloud_Search\Predis\Client || $redis instanceof \WP_Cloud_Search\Redis)) {
            throw new \InvalidArgumentException('Predis\\Client or Redis instance required');
        }
        $this->redisClient = $redis;
        $this->redisKey = $key;
        $this->capSize = $capSize;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        if ($this->capSize) {
            $this->writeCapped($record);
        } else {
            $this->redisClient->rpush($this->redisKey, $record["formatted"]);
        }
    }
    /**
     * Write and cap the collection
     * Writes the record to the redis list and caps its
     *
     * @param  array $record associative record array
     * @return void
     */
    protected function writeCapped(array $record)
    {
        if ($this->redisClient instanceof \WP_Cloud_Search\Redis) {
            $this->redisClient->multi()->rpush($this->redisKey, $record["formatted"])->ltrim($this->redisKey, -$this->capSize, -1)->exec();
        } else {
            $redisKey = $this->redisKey;
            $capSize = $this->capSize;
            $this->redisClient->transaction(function ($tx) use($record, $redisKey, $capSize) {
                $tx->rpush($redisKey, $record["formatted"]);
                $tx->ltrim($redisKey, -$capSize, -1);
            });
        }
    }
    /**
     * {@inheritDoc}
     */
    protected function getDefaultFormatter()
    {
        return new \WP_Cloud_Search\Monolog\Formatter\LineFormatter();
    }
}
