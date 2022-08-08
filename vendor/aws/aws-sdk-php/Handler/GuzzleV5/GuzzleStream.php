<?php

namespace WP_Cloud_Search\Aws\Handler\GuzzleV5;

use WP_Cloud_Search\GuzzleHttp\Stream\StreamDecoratorTrait;
use WP_Cloud_Search\GuzzleHttp\Stream\StreamInterface as GuzzleStreamInterface;
use WP_Cloud_Search\Psr\Http\Message\StreamInterface as Psr7StreamInterface;
/**
 * Adapts a PSR-7 Stream to a Guzzle 5 Stream.
 *
 * @codeCoverageIgnore
 */
class GuzzleStream implements GuzzleStreamInterface
{
    use StreamDecoratorTrait;
    /** @var Psr7StreamInterface */
    private $stream;
    public function __construct(Psr7StreamInterface $stream)
    {
        $this->stream = $stream;
    }
}
