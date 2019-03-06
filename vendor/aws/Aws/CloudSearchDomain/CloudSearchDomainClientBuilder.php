<?php

namespace WP_Cloud_Search\Aws\CloudSearchDomain;

use WP_Cloud_Search\Aws\Common\Client\ClientBuilder;
use WP_Cloud_Search\Aws\Common\Client\ThrottlingErrorChecker;
use WP_Cloud_Search\Aws\Common\Client\UserAgentListener;
use WP_Cloud_Search\Aws\Common\Enum\ClientOptions as Options;
use WP_Cloud_Search\Aws\Common\Exception\ExceptionListener;
use WP_Cloud_Search\Aws\Common\Exception\InvalidArgumentException;
use WP_Cloud_Search\Aws\Common\Exception\NamespaceExceptionFactory;
use WP_Cloud_Search\Aws\Common\Exception\Parser\JsonQueryExceptionParser;
use WP_Cloud_Search\Guzzle\Common\Collection;
use WP_Cloud_Search\Guzzle\Http\Url;
use WP_Cloud_Search\Guzzle\Plugin\Backoff\BackoffPlugin;
use WP_Cloud_Search\Guzzle\Plugin\Backoff\CurlBackoffStrategy;
use WP_Cloud_Search\Guzzle\Plugin\Backoff\ExponentialBackoffStrategy;
use WP_Cloud_Search\Guzzle\Plugin\Backoff\HttpBackoffStrategy;
use WP_Cloud_Search\Guzzle\Plugin\Backoff\TruncatedBackoffStrategy;
use WP_Cloud_Search\Guzzle\Service\Description\ServiceDescription;
/**
 * Builder for creating CloudSearchDomain clients
 *
 * @internal
 */
class CloudSearchDomainClientBuilder extends \WP_Cloud_Search\Aws\Common\Client\ClientBuilder
{
    protected static $commonConfigDefaults = array(\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::SCHEME => 'https');
    public function build()
    {
        // Resolve configuration
        $config = \WP_Cloud_Search\Guzzle\Common\Collection::fromConfig($this->config, \array_merge(self::$commonConfigDefaults, $this->configDefaults), $this->configRequirements);
        $endpoint = $config['endpoint'] ?: $config[\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::BASE_URL];
        // Make sure endpoint is correctly set
        if (!$endpoint) {
            throw new \WP_Cloud_Search\Aws\Common\Exception\InvalidArgumentException('You must provide the endpoint for the CloudSearch domain.');
        }
        if (\strpos($endpoint, 'http') !== 0) {
            $endpoint = $config[\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::SCHEME] . '://' . $endpoint;
            $config['endpoint'] = $endpoint;
            $config[\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::BASE_URL] = $endpoint;
        }
        // Determine the region from the endpoint
        $endpoint = \WP_Cloud_Search\Guzzle\Http\Url::factory($endpoint);
        list(, $region) = \explode('.', $endpoint->getHost());
        $config[\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::REGION] = $config[\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::SIGNATURE_REGION] = $region;
        // Create dependencies
        $exceptionParser = new \WP_Cloud_Search\Aws\Common\Exception\Parser\JsonQueryExceptionParser();
        $description = \WP_Cloud_Search\Guzzle\Service\Description\ServiceDescription::factory(\sprintf($config->get(\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::SERVICE_DESCRIPTION), $config->get(\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::VERSION)));
        $signature = $this->getSignature($description, $config);
        $credentials = $this->getCredentials($config);
        // Resolve backoff strategy
        $backoff = $config->get(\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::BACKOFF);
        if ($backoff === null) {
            $retries = isset($config[\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::BACKOFF_RETRIES]) ? $config[\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::BACKOFF_RETRIES] : 3;
            $backoff = new \WP_Cloud_Search\Guzzle\Plugin\Backoff\BackoffPlugin(
                // Retry failed requests up to 3 (or configured) times if it is determined that the request can be retried
                new \WP_Cloud_Search\Guzzle\Plugin\Backoff\TruncatedBackoffStrategy(
                    $retries,
                    // Retry failed requests with 400-level responses due to throttling
                    new \WP_Cloud_Search\Aws\Common\Client\ThrottlingErrorChecker(
                        $exceptionParser,
                        // Retry failed requests due to transient network or cURL problems
                        new \WP_Cloud_Search\Guzzle\Plugin\Backoff\CurlBackoffStrategy(
                            null,
                            // Retry failed requests with 500-level responses
                            new \WP_Cloud_Search\Guzzle\Plugin\Backoff\HttpBackoffStrategy(array(500, 503, 504, 509), new \WP_Cloud_Search\Guzzle\Plugin\Backoff\ExponentialBackoffStrategy())
                        )
                    )
                )
            );
            $config->set(\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::BACKOFF, $backoff);
        }
        if ($backoff) {
            $this->addBackoffLogger($backoff, $config);
        }
        // Create client
        $client = new \WP_Cloud_Search\Aws\CloudSearchDomain\CloudSearchDomainClient($credentials, $signature, $config);
        $client->setDescription($description);
        // Add exception marshaling so that more descriptive exception are thrown
        $client->addSubscriber(new \WP_Cloud_Search\Aws\Common\Exception\ExceptionListener(new \WP_Cloud_Search\Aws\Common\Exception\NamespaceExceptionFactory($exceptionParser, __NAMESPACE__ . '\\Exception', __NAMESPACE__ . '\\Exception\\CloudSearchDomainException')));
        // Add the UserAgentPlugin to append to the User-Agent header of requests
        $client->addSubscriber(new \WP_Cloud_Search\Aws\Common\Client\UserAgentListener());
        // Filters used for the cache plugin
        $client->getConfig()->set('params.cache.key_filter', 'header=date,x-amz-date,x-amz-security-token,x-amzn-authorization');
        // Disable parameter validation if needed
        if ($config->get(\WP_Cloud_Search\Aws\Common\Enum\ClientOptions::VALIDATION) === \false) {
            $params = $config->get('command.params') ?: array();
            $params['command.disable_validation'] = \true;
            $config->set('command.params', $params);
        }
        return $client;
    }
}
