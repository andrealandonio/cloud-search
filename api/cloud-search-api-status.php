<?php
/**
 * Index searchable documents API
 */
function acs_api_index_searchable_documents() {
	// Read data
	$format = filter_var( $_GET[ 'format' ], FILTER_SANITIZE_STRING );

	// Get client
	$client = acs_get_domain_client();

	// Prepare query
	$query = acs_decode_search_query( '', ACS::TYPE_FIELD_DEFAULT );

	try {
		// Search documents
		$result = $client->search( array(
			'query' => $query,
			'start' => 0,
			'size' => ACS::SEARCH_RETURN_TEST_ITEMS,
			'filterQuery' => acs_get_filter_query( true ),
			'queryParser' => ACS::QUERY_PARSER
		) );

		// Prepare result object
		$response = new stdClass();
		$response->found = $result->getPath( 'hits/found' );
	}
	catch ( Exception $e ) {
		// Prepare result object
		$response = new stdClass();
		$response->errors = boolval( true );
	}

	// Return result object
	echo acs_format_response( $response, $format );

	die();
}
add_action( 'wp_ajax_acs_api_index_searchable_documents', 'acs_api_index_searchable_documents' );
add_action( 'wp_ajax_nopriv_acs_api_index_searchable_documents', 'acs_api_index_searchable_documents' );

/**
 * Index site documents API
 */
function acs_api_index_site_documents() {
    // Read data
    $format = filter_var( $_GET[ 'format' ], FILTER_SANITIZE_STRING );

    // Get client
    $client = acs_get_domain_client();

    // Prepare query
    $query = acs_decode_search_query( '', ACS::TYPE_FIELD_DEFAULT );

	try {
		// Search documents
		$result = $client->search( array(
			'query' => $query,
			'start' => 0,
			'size' => ACS::SEARCH_RETURN_TEST_ITEMS,
			'filterQuery' => acs_get_filter_query(),
			'queryParser' => ACS::QUERY_PARSER
		) );

		// Prepare result object
		$response = new stdClass();
		$response->found = $result->getPath( 'hits/found' );
	}
	catch ( Exception $e ) {
		// Prepare result object
		$response = new stdClass();
		$response->errors = boolval( true );
	}

    // Return result object
    echo acs_format_response( $response, $format );

    die();
}
add_action( 'wp_ajax_acs_api_index_site_documents', 'acs_api_index_site_documents' );
add_action( 'wp_ajax_nopriv_acs_api_index_site_documents', 'acs_api_index_site_documents' );

/**
 * Index fields API
 */
function acs_api_index_fields() {
	// Read data
	$format = filter_var( $_GET[ 'format' ], FILTER_SANITIZE_STRING );

	// Get client
	$client = acs_get_client();

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	try {
		// Get index fields (only deployed)
		$result = $client->describeIndexFields( array(
			'DomainName' => ( defined ('WP_ACS_SEARCH_DOMAIN') ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name,
			'Deployed' => true
		) );

		// Prepare result object
		$response = new stdClass();
		$response->found = count( $result->getPath( 'IndexFields' ) );
		$response->errors = boolval( false );
	}
	catch ( Exception $e ) {
		// Prepare result object
		$response = new stdClass();
		$response->errors = boolval( true );
	}

	// Return result object
	echo acs_format_response( $response, $format );

	die();
}
add_action( 'wp_ajax_acs_api_index_fields', 'acs_api_index_fields' );
add_action( 'wp_ajax_nopriv_acs_api_index_fields', 'acs_api_index_fields' );

/**
 * Index status API
 */
function acs_api_index_status() {
	// Read data
	$format = filter_var( $_GET[ 'format' ], FILTER_SANITIZE_STRING );

	// Get client
	$client = acs_get_client();

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Gets information about the search domains
	$status_requires_index_documents = false;
	$status_processing = false;
	$errors = false;
	$describe_domains_result = $client->describeDomains( array(
		'DomainNames' => array( ( defined ( 'WP_ACS_SEARCH_DOMAIN' ) ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name )
	));

	if ( ! empty( $describe_domains_result ) && ! empty( $describe_domains_result[ 'DomainStatusList' ][ 0 ] ) ) {
		$status_requires_index_documents = $describe_domains_result[ 'DomainStatusList' ][ 0 ][ 'RequiresIndexDocuments' ];
		$status_processing = $describe_domains_result[ 'DomainStatusList' ][ 0 ][ 'Processing' ];
	}
	else {
		$errors = true;
	}

	// Prepare result object
	$response = new stdClass();
	$response->requires_index_documents = boolval( $status_requires_index_documents );
	$response->processing = boolval( $status_processing );
	$response->errors = boolval( $errors );

	// Return result object
	echo acs_format_response( $response, $format );

	die();
}
add_action( 'wp_ajax_acs_api_index_status', 'acs_api_index_status' );
add_action( 'wp_ajax_nopriv_acs_api_index_status', 'acs_api_index_status' );
