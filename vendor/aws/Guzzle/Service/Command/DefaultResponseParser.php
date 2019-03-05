<?php

namespace WP_Cloud_Search\Guzzle\Service\Command;

use WP_Cloud_Search\Guzzle\Http\Message\Response;
/**
 * Default HTTP response parser used to marshal JSON responses into arrays and XML responses into SimpleXMLElement
 */
class DefaultResponseParser implements \WP_Cloud_Search\Guzzle\Service\Command\ResponseParserInterface
{
    /** @var self */
    protected static $instance;
    /**
     * @return self
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function parse(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command)
    {
        $response = $command->getRequest()->getResponse();
        // Account for hard coded content-type values specified in service descriptions
        if ($contentType = $command['command.expects']) {
            $response->setHeader('Content-Type', $contentType);
        } else {
            $contentType = (string) $response->getHeader('Content-Type');
        }
        return $this->handleParsing($command, $response, $contentType);
    }
    protected function handleParsing(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\Response $response, $contentType)
    {
        $result = $response;
        if ($result->getBody()) {
            if (\stripos($contentType, 'json') !== \false) {
                $result = $result->json();
            } elseif (\stripos($contentType, 'xml') !== \false) {
                $result = $result->xml();
            }
        }
        return $result;
    }
}
