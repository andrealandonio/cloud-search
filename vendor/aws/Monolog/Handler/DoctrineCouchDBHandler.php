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
use WP_Cloud_Search\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \WP_Cloud_Search\Monolog\Handler\AbstractProcessingHandler
{
    private $client;
    public function __construct(\WP_Cloud_Search\Doctrine\CouchDB\CouchDBClient $client, $level = \WP_Cloud_Search\Monolog\Logger::DEBUG, $bubble = \true)
    {
        $this->client = $client;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        $this->client->postDocument($record['formatted']);
    }
    protected function getDefaultFormatter()
    {
        return new \WP_Cloud_Search\Monolog\Formatter\NormalizerFormatter();
    }
}
