<?php

namespace WP_Cloud_Search\Aws\SSOOIDC;

use WP_Cloud_Search\Aws\AwsClient;
/**
 * This client is used to interact with the **AWS SSO OIDC** service.
 * @method \Aws\Result createToken(array $args = [])
 * @method \GuzzleHttp\Promise\Promise createTokenAsync(array $args = [])
 * @method \Aws\Result registerClient(array $args = [])
 * @method \GuzzleHttp\Promise\Promise registerClientAsync(array $args = [])
 * @method \Aws\Result startDeviceAuthorization(array $args = [])
 * @method \GuzzleHttp\Promise\Promise startDeviceAuthorizationAsync(array $args = [])
 */
class SSOOIDCClient extends AwsClient
{
}
