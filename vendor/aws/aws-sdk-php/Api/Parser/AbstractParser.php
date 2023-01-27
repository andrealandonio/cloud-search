<?php

namespace WP_Cloud_Search\Aws\Api\Parser;

use WP_Cloud_Search\Aws\Api\Service;
use WP_Cloud_Search\Aws\Api\StructureShape;
use WP_Cloud_Search\Aws\CommandInterface;
use WP_Cloud_Search\Aws\ResultInterface;
use WP_Cloud_Search\Psr\Http\Message\ResponseInterface;
use WP_Cloud_Search\Psr\Http\Message\StreamInterface;
/**
 * @internal
 */
abstract class AbstractParser
{
    /** @var \Aws\Api\Service Representation of the service API*/
    protected $api;
    /** @var callable */
    protected $parser;
    /**
     * @param Service $api Service description.
     */
    public function __construct(Service $api)
    {
        $this->api = $api;
    }
    /**
     * @param CommandInterface  $command  Command that was executed.
     * @param ResponseInterface $response Response that was received.
     *
     * @return ResultInterface
     */
    public abstract function __invoke(CommandInterface $command, ResponseInterface $response);
    public abstract function parseMemberFromStream(StreamInterface $stream, StructureShape $member, $response);
}
