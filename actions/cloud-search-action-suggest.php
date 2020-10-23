<?php
/**
 * Suggest AJAX callback
 */
function wp_ajax_acs_suggest_callback() {
	// Read prompted keyword
	$keyword = sanitize_text_field( $_GET[ 'keyword' ] );
	list( $settings, $results ) = acs_retrieve_suggestions( $keyword );

	// Return suggest result
	echo json_encode(
		array(
			'results' => array_slice( $results, 0, $settings->acs_suggest_results )
		)
	);

	// Resets
	wp_reset_postdata();
	wp_reset_query();

	die();
}

/**
 * Retrieve suggestions
 *
 * @param string $keyword
 *
 * @return array
 */
function acs_retrieve_suggestions( $keyword ) {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Set default search values
	$start = 0;
	$size = $settings->acs_suggest_results;
	$filter_query = '';

	try {
		// Search data
		$acs_result = acs_index_documents_suggest( $keyword, $start, $size, ACS::QUERY_PARSER, $filter_query );
		$result_search = $acs_result->get_data();

		//TODO: add loading message
	}
	catch ( Exception $e ) {
		$result_search = array();
	}

	$results = array();
	if ( ! empty( $result_search ) && $result_search[ 'found' ] > 0 && count( $result_search[ 'items' ] ) > 0 ) {
		// Documents founded
		foreach ( $result_search[ 'items' ] as $post_item ) {
			// Add post ID to result array
			$results[] = array(
				'title' => html_entity_decode( $post_item[ 'title' ] ),
				'url' => $post_item[ 'url' ]
			);
		}
	}

	return array( $settings, $results );
}

/**
 * Get suggestions route
 *
 * @param WP_REST_Request $request
 *
 * @return array
 */
function acs_route_get_suggestions( $request ) {
	$keyword = $request->get_param( 'keyword' );
	list( $settings, $results ) = acs_retrieve_suggestions( $keyword );

  // Check if needs AMP items
  if ( isset( $_GET[ 'amp' ] ) ) {
    return array(
      'items' => array_slice( $results, 0, $settings->acs_suggest_results )
    );
  } 
  else {
    return array(
      'results' => array_slice( $results, 0, $settings->acs_suggest_results )
    );
  }
}

/**
 * Suggest documents in the index
 *
 * @param string $key
 * @param int $start
 * @param int $size
 * @param string $query_parser
 * @param string $filter_query
 *
 * @return ACS_Result
 */
function acs_index_documents_suggest( $key, $start = 0, $size = ACS::SUGGEST_DEFAULT_RESULTS, $query_parser = ACS::QUERY_PARSER, $filter_query = '' ) {
  // Get client
  $client = acs_get_domain_client();

  // Get settings option
  $settings = ACS::get_instance()->get_settings();

  // Prepare query
	if ( $settings->acs_suggest_only_title == 1 ) {
		// Search only in post title
		$query = 'post_title' . ':' . $key . '*';
	}
	else {
		// Search in all fields
		$query = $key . '*';
	}

  // Prepare sort (search only in post title)
  switch ( $settings->acs_suggest_order ) {
    case ACS::SUGGEST_ORDER_TYPE_3: {
      // Alphabetically reverse
      $sort = 'post_title desc';
      break;
    }
    case ACS::SUGGEST_ORDER_TYPE_2: {
      // Alphabetically
      $sort = 'post_title asc';
      break;
    }
    default: {
      // Score
      $sort = ACS::SORT_FIELD_DEFAULT . ' ' . ACS::SORT_ORDER_DEFAULT;
      break;
    }
  }

  // Search documents
  $result = $client->search( array(
    'query' => $query,
    'start' => intval( $start ),
    'size' => intval( $size ),
    'filterQuery' => acs_get_filter_query( false, ACS::TYPE_FIELD_DEFAULT, $filter_query ),
    'queryParser' => $query_parser,
    'sort' => $sort
  ) );

  // Read result
  $hit_found = $result->getPath( 'hits/found' ); // Total items
  $items = $result->getPath( 'hits' ); // Items array

  $post_items = null;
  if ( count( $items[ 'hit' ] ) > 0 ) {
    foreach ( $items[ 'hit' ] as $item)  {
      $post_items[ $item[ 'fields' ][ 'id' ][ 0 ] ] = array(
        'title' => $item[ 'fields' ][ 'post_title' ][ 0 ],
        'url' => $item[ 'fields' ][ 'post_url' ][ 0 ]
      );
    }
  }

  // Prepare result object
  $acs_result = new ACS_Result();
  $acs_result->set_data( array(
    'start' => intval( $start ),
    'size' => intval( $size ),
    'found' => $hit_found,
    'items' => $post_items
  ) );

  // Return result object
  return $acs_result;
}