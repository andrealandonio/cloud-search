<?php
/**
 * Imports
 */

use \WP_Cloud_Search\Aws\CloudSearch\CloudSearchClient;
use \WP_Cloud_Search\Aws\CloudSearchDomain\CloudSearchDomainClient;

/**
 * Includes
 */
require_once( 'cloud-search-action-search.php' );
require_once( 'cloud-search-action-status.php' );
require_once( 'cloud-search-action-suggest.php' );
require_once( 'cloud-search-action-manage.php' );
require_once( 'cloud-search-action-operation.php' );
require_once( 'cloud-search-action-import.php' );

/**
 * Get SDK CloudSearchClient object
 *
 * @return CloudSearchClient|null
 */
function acs_get_client() {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Get client
	$client = null;
	if ( acs_check_basic_configuration() ) {
		/** @noinspection MissedFieldInspection */
		$client = CloudSearchClient::factory( array(
			'version' => '2013-01-01',
			'region' => ( defined( 'WP_ACS_REGION' ) ) ? WP_ACS_REGION : $settings->acs_aws_region,
			// 'key' => ( defined( 'WP_ACS_ACCESS_KEY' ) ) ? WP_ACS_ACCESS_KEY : $settings->acs_aws_access_key_id,
			// 'secret' => ( defined( 'WP_ACS_SECRET_KEY' ) ) ? WP_ACS_SECRET_KEY : $settings->acs_aws_secret_access_key
            'credentials' => array(
                'key' => ( defined( 'WP_ACS_ACCESS_KEY' ) ) ? WP_ACS_ACCESS_KEY : $settings->acs_aws_access_key_id,
                'secret' => ( defined( 'WP_ACS_SECRET_KEY' ) ) ? WP_ACS_SECRET_KEY : $settings->acs_aws_secret_access_key,
                'token' => ( defined( 'WP_ACS_SESSION_TOKEN' ) ) ? WP_ACS_SESSION_TOKEN : $settings->acs_aws_session_token,
            )
		) );
	}
	else if ( acs_check_mandatory_configuration() ) {
		// Try to use IAM roles to connect to the client
		/** @noinspection MissedFieldInspection */
		$client = CloudSearchClient::factory( array(
			'version' => '2013-01-01',
			'region' => ( defined( 'WP_ACS_REGION' ) ) ? WP_ACS_REGION : $settings->acs_aws_region
		) );
	}

	return $client;
}

/**
 * Get SDK CloudSearchDomainClient object
 *
 * @return CloudSearchDomainClient|null
 */
function acs_get_domain_client() {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Get domain client
	$domain_client = null;
	if ( acs_check_basic_configuration() ) {
		/** @noinspection MissedFieldInspection */
		$args = array(
			'endpoint' => ( defined( 'WP_ACS_SEARCH_ENDPOINT' ) ) ? WP_ACS_SEARCH_ENDPOINT : $settings->acs_search_endpoint,
			'region' => ( defined( 'WP_ACS_REGION' ) ) ? WP_ACS_REGION : $settings->acs_aws_region,
			'credentials' => array(
				'key' => ( defined( 'WP_ACS_ACCESS_KEY' ) ) ? WP_ACS_ACCESS_KEY : $settings->acs_aws_access_key_id,
				'secret' => ( defined( 'WP_ACS_SECRET_KEY' ) ) ? WP_ACS_SECRET_KEY : $settings->acs_aws_secret_access_key,
				'token' => ( defined( 'WP_ACS_SESSION_TOKEN' ) ) ? WP_ACS_SESSION_TOKEN : $settings->acs_aws_session_token
			),
			'retries' => ( defined( 'WP_ACS_DOMAIN_CLIENT_RETRIES' ) ) ? WP_ACS_DOMAIN_CLIENT_RETRIES : 3,
			'http' => array(
				'connect_timeout' => ( defined( 'WP_ACS_DOMAIN_CLIENT_CONNECT_TIMEOUT' ) ) ? WP_ACS_DOMAIN_CLIENT_CONNECT_TIMEOUT : 0,
				'timeout' => ( defined( 'WP_ACS_DOMAIN_CLIENT_TIMEOUT' ) ) ? WP_ACS_DOMAIN_CLIENT_TIMEOUT : 0
			),
			'version' => '2013-01-01',
		);

		$domain_client = CloudSearchDomainClient::factory( $args );
	}
	else if ( acs_check_mandatory_configuration() ) {
		// Try to use IAM roles to connect to the client
		/** @noinspection MissedFieldInspection */
		$domain_client = CloudSearchDomainClient::factory( array(
			'version' => '2013-01-01',
			'endpoint' => ( defined( 'WP_ACS_SEARCH_ENDPOINT' ) ) ? WP_ACS_SEARCH_ENDPOINT : $settings->acs_search_endpoint,
			'retries' => ( defined( 'WP_ACS_DOMAIN_CLIENT_RETRIES' ) ) ? WP_ACS_DOMAIN_CLIENT_RETRIES : 3,
			'http' => array(
				'connect_timeout' => ( defined( 'WP_ACS_DOMAIN_CLIENT_CONNECT_TIMEOUT' ) ) ? WP_ACS_DOMAIN_CLIENT_CONNECT_TIMEOUT : 0,
				'timeout' => ( defined( 'WP_ACS_DOMAIN_CLIENT_TIMEOUT' ) ) ? WP_ACS_DOMAIN_CLIENT_TIMEOUT : 0
			)
		) );
	}

	return $domain_client;
}

/**
 * Get post types that must be indexed
 *
 * @return array|mixed
 */
function acs_get_schema_types() {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();

    // Manage post types
    $acs_schema_types = $settings->acs_schema_types;
    $acs_schema_types = $acs_schema_types ? explode( ACS::SEPARATOR, $acs_schema_types ) : array();

    return $acs_schema_types;
}
