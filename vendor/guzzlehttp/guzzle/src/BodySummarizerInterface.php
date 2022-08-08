<?php

namespace WP_Cloud_Search\GuzzleHttp;

use WP_Cloud_Search\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message) : ?string;
}
