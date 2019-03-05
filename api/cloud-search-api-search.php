<?php
/**
 * Search API (works only in current site)
 */
function acs_api_search() {
	// Read data
	$keyword = stripslashes( $_GET[ 'keyword' ] );
	$start = filter_var( $_GET[ 'start' ], FILTER_SANITIZE_NUMBER_INT );
	$size = filter_var( $_GET[ 'size' ], FILTER_SANITIZE_NUMBER_INT );
    $filter_query = stripslashes( $_GET[ 'filter_query' ] );
    $format = filter_var( $_GET[ 'format' ], FILTER_SANITIZE_STRING );
	$facets = acs_read_facet_parameters();

    // Check defaults and prepare data
    if ( empty( $start ) ) $start = 0;
    if ( empty( $size ) ) $size = ACS::SEARCH_RETURN_FULL_ITEMS;
	$result_facets = array();

    // Save search keyword
    ACS::get_instance()->set_key($keyword);

	try {
		// Search data
		$acs_result = acs_index_documents_search( $keyword, ACS::SEARCH_FIELD_DEFAULT, $start, $size, ACS::QUERY_PARSER, $filter_query, false, ACS::TYPE_FIELD_DEFAULT, ACS::SORT_FIELD_DEFAULT, ACS::SORT_ORDER_DEFAULT, false, $facets );
		$result_search = $acs_result->get_data();
		$result_facets = $result_search[ 'facets' ];
	}
	catch ( Exception $e ) {
		$result_search = array();
	}

	$items = array();
	if ( !empty( $result_search ) && count( $result_search[ 'items' ] ) > 0 ) {
		// Documents founded
		foreach ( $result_search[ 'items' ] as $post_item ) {
			// Add post ID to response array
			$items[] = $post_item;
		}
	}

	// Prepare result object
	$response = new stdClass();
	$response->items = $items;
	$response->facets = $result_facets;

	// Return result object
	echo acs_format_response( $response, $format );

	die();
}
add_action( 'wp_ajax_acs_api_search', 'acs_api_search' );
add_action( 'wp_ajax_nopriv_acs_api_search', 'acs_api_search' );

/**
 * Search API full (works in all sites)
 */
function acs_api_search_full() {
    // Read data
    $keyword = stripslashes( $_GET[ 'keyword' ] );
    $start = filter_var( $_GET[ 'start' ], FILTER_SANITIZE_NUMBER_INT );
    $size = filter_var( $_GET[ 'size' ], FILTER_SANITIZE_NUMBER_INT );
    $filter_query = stripslashes( $_GET[ 'filter_query' ] );
    $format = filter_var( $_GET[ 'format' ], FILTER_SANITIZE_STRING );
	$facets = acs_read_facet_parameters();

    // Check defaults and prepare data
    if ( empty( $start ) ) $start = 0;
    if ( empty( $size ) ) $size = ACS::SEARCH_RETURN_FULL_ITEMS;
	$result_facets = array();

	// Save search keyword
    ACS::get_instance()->set_key($keyword);

    try {
        // Search full data
        $acs_result = acs_index_documents_search( $keyword, ACS::SEARCH_FIELD_DEFAULT, $start, $size, ACS::QUERY_PARSER, $filter_query, true, ACS::TYPE_FIELD_DEFAULT, ACS::SORT_FIELD_DEFAULT, ACS::SORT_ORDER_DEFAULT, false, $facets );
        $result_search = $acs_result->get_data();
	    $result_facets = $result_search[ 'facets' ];
    }
    catch ( Exception $e ) {
        $result_search = array();
    }

    $items = array();
    if ( !empty( $result_search ) && count( $result_search[ 'items' ] ) > 0 ) {
        // Documents founded
        foreach ( $result_search[ 'items' ] as $post_item ) {
	        if ( $post_item[ 'post_type' ][ 0 ] === 'category' || $post_item[ 'post_type' ][ 0 ] === 'post_tag' ) {
				// Unset unwanted fields for terms
        		unset( $post_item[ 'post_date' ] );
		        unset( $post_item[ 'post_date_gmt' ] );
		        unset( $post_item[ 'post_modified' ] );
		        unset( $post_item[ 'post_modified_gmt' ] );
	        }

            // Add post fields to response array
            $items[] = $post_item;
        }
    }

    // Prepare result object
    $response = new stdClass();
    $response->items = $items;
	$response->facets = $result_facets;

    // Return result object
    echo acs_format_response( $response, $format );

    die();
}
add_action( 'wp_ajax_acs_api_search_full', 'acs_api_search_full' );
add_action( 'wp_ajax_nopriv_acs_api_search_full', 'acs_api_search_full' );
