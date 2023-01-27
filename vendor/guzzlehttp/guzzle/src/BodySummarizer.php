<?php

namespace WP_Cloud_Search\GuzzleHttp;

use WP_Cloud_Search\Psr\Http\Message\MessageInterface;
final class BodySummarizer implements BodySummarizerInterface
{
    /**
     * @var int|null
     */
    private $truncateAt;
    public function __construct(int $truncateAt = null)
    {
        $this->truncateAt = $truncateAt;
    }
    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message) : ?string
    {
        return $this->truncateAt === null ? \WP_Cloud_Search\GuzzleHttp\Psr7\Message::bodySummary($message) : \WP_Cloud_Search\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
