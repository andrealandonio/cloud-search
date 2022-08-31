<?php
/**
 * Get index searchable documents for logged users
 */
function acs_index_searchable_documents() {
    $acs_result = new ACS_Result();

    // Verify nonce
    if ( ! acs_verify_nonce() ) {
        $acs_result->set_code( 'error' );
        $acs_result->set_message( 'bad nonce' );
        echo $acs_result->format_response();
        die();
    }

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
        $acs_result->set_code( 'ok' );
        $acs_result->add_action( ACS::ACTION_GET_INDEX_SEARCHABLE_DOCUMENTS );
        $acs_result->set_message( 'ok' );
        $acs_result->set_data( array(
            'found' => $result->getPath( 'hits/found' )
        ) );
    }
    catch ( Exception $e ) {
        // Prepare error result object
        $acs_result->set_code( 'error' );
        $acs_result->add_action( ACS::ACTION_GET_INDEX_SEARCHABLE_DOCUMENTS );
        $acs_result->set_message( 'error reading searchable documents' );
    }

    // Return result object
    echo $acs_result->format_response();

    die();
}
add_action( 'wp_ajax_acs_index_searchable_documents', 'acs_index_searchable_documents' );

/**
 * Get index searchable documents for guest users
 */
function nopriv_acs_index_searchable_documents() {
    die();
}
add_action( 'wp_ajax_nopriv_acs_index_searchable_documents', 'nopriv_acs_index_searchable_documents' );

/**
 * Get index site documents for logged users
 */
function acs_index_site_documents() {
    $acs_result = new ACS_Result();

    // Verify nonce
    if ( ! acs_verify_nonce() ) {
        $acs_result->set_code( 'error' );
        $acs_result->set_message( 'bad nonce' );
        echo $acs_result->format_response();
        die();
    }

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
        $acs_result->set_code( 'ok' );
        $acs_result->add_action( ACS::ACTION_GET_INDEX_SITE_DOCUMENTS );
        $acs_result->set_message( 'ok' );
        $acs_result->set_data( array(
            'found' => $result->getPath( 'hits/found' )
        ) );
    }
    catch ( Exception $e ) {
//    	echo print_r($e->getMessage(), true);

        // Prepare error result object
        $acs_result->set_code( 'error' );
        $acs_result->add_action( ACS::ACTION_GET_INDEX_SITE_DOCUMENTS );
        $acs_result->set_message( 'error reading site documents' );
    }

    // Return result object
    echo $acs_result->format_response();

    die();
}
add_action( 'wp_ajax_acs_index_site_documents', 'acs_index_site_documents' );

/**
 * Get index site documents for guest users
 */
function nopriv_acs_index_site_documents() {
    die();
}
add_action( 'wp_ajax_nopriv_acs_index_site_documents', 'nopriv_acs_index_site_documents' );

/**
 * Get index fields for logged users
 */
function acs_index_fields() {
	$acs_result = new ACS_Result();

	// Verify nonce
	if ( ! acs_verify_nonce() ) {
		$acs_result->set_code( 'error' );
		$acs_result->set_message( 'bad nonce' );
		echo $acs_result->format_response();
		die();
	}

	// Get client
	$client = acs_get_client();

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	try {
		// Get index fields (only deployed)
		$result = $client->describeIndexFields( array(
			'DomainName' => ( defined ( 'WP_ACS_SEARCH_DOMAIN') ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name,
			'Deployed' => true
		) );

		// Prepare result object
		$acs_result->set_code( 'ok' );
		$acs_result->add_action( ACS::ACTION_GET_INDEX_FIELDS );
		$acs_result->set_message( 'ok' );
		$acs_result->set_data( array(
			'found' => count( $result->getPath( 'IndexFields' ) )
		) );
	}
	catch ( Exception $e ) {
		// Prepare error result object
		$acs_result->set_code( 'error' );
		$acs_result->add_action( ACS::ACTION_GET_INDEX_FIELDS );
		$acs_result->set_message( 'error describe index fields' );
	}

	// Return result object
	echo $acs_result->format_response();

	die();
}
add_action( 'wp_ajax_acs_index_fields', 'acs_index_fields' );

/**
 * Get index fields for guest users
 */
function nopriv_acs_index_fields() {
    die();
}
add_action( 'wp_ajax_nopriv_acs_index_fields', 'nopriv_acs_index_fields' );

/**
 * Get index status for logged users
 */
function acs_index_status() {
    $acs_result = new ACS_Result();

    // Verify nonce
    if ( ! acs_verify_nonce() ) {
        $acs_result->set_code( 'error' );
        $acs_result->set_message( 'bad nonce' );
        echo $acs_result->format_response();
        die();
    }
	else {
		// Check index status
		$acs_result = acs_check_index_status();
	}

    // Return result object
    echo $acs_result->format_response();

    die();
}
add_action( 'wp_ajax_acs_index_status', 'acs_index_status' );

/**
 * Get index status for guest users
 */
function nopriv_acs_index_status() {
    die();
}
add_action( 'wp_ajax_nopriv_acs_index_status', 'nopriv_acs_index_status' );

/**
 * Check index status
 *
 * @return ACS_Result
 */
function acs_check_index_status() {
	$acs_result = new ACS_Result();

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

	if ( $errors ) {
		// Prepare error result object
		$acs_result->set_code( 'error' );
		$acs_result->add_action( ACS::ACTION_GET_INDEX_STATUS );
		$acs_result->set_message( 'error describe domains' );
	}
	else {
		// Prepare result object
		$acs_result->set_code( 'ok' );
		$acs_result->add_action( ACS::ACTION_GET_INDEX_STATUS );
		$acs_result->set_message( 'ok' );
		$acs_result->set_data( array(
			'requires_index_documents' => boolval( $status_requires_index_documents ),
			'processing' => boolval( $status_processing )
		) );
	}

	// Return result object
	return $acs_result;
}
