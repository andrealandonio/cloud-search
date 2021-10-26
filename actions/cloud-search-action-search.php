<?php
/**
 * Search documents (works only in current site)
 */
function acs_search_documents() {
	echo acs_perform_search_documents( ACS::SEARCH_TEMPLATES_DIRECTORY, false );
	exit();
}
add_action( 'wp_ajax_acs_search_documents', 'acs_search_documents' );
add_action( 'wp_ajax_nopriv_acs_search_documents', 'acs_search_documents' );

/**
 * Search documents full (works in all sites)
 */
function acs_search_documents_full() {
    echo acs_perform_search_documents( ACS::SEARCH_TEMPLATES_DIRECTORY, true );
    exit();
}
add_action( 'wp_ajax_acs_search_documents_full', 'acs_search_documents_full' );
add_action( 'wp_ajax_nopriv_acs_search_documents_full', 'acs_search_documents_full' );

/**
 * Perform search documents
 *
 * @param string $search_templates_directory
 * @param bool $global_search
 *
 * @return mixed|string|void
 */
function acs_perform_search_documents( $search_templates_directory, $global_search ) {
  	global $result_search;

  	// Read data
  	$keyword = stripslashes( $_GET[ 'keyword' ] );
	$start = filter_var ( $_GET[ 'start' ], FILTER_SANITIZE_NUMBER_INT );
	$size = filter_var ( $_GET[ 'size' ], FILTER_SANITIZE_NUMBER_INT );

	$filter_query = ( isset( $_GET[ 'filter_query' ] ) ) ? stripslashes( $_GET[ 'filter_query' ] ) : '';
	//$filter_query = stripslashes( $_GET[ 'filter_query' ] );

	$type_field = filter_var ( $_GET[ 'type_field' ], FILTER_SANITIZE_STRING );
  	$sort_field = filter_var ( $_GET[ 'sort_field' ], FILTER_SANITIZE_STRING );
  	$sort_order = filter_var ( $_GET[ 'sort_order' ], FILTER_SANITIZE_STRING );

	$extras = ( isset( $_GET[ 'extras' ] ) ) ?  $_GET[ 'extras' ] : '';
	//$extras = $_GET[ 'extras' ];

	// Sanitize fields
	$type_field = ( ! empty ( $type_field ) ) ? $type_field : ACS::TYPE_FIELD_DEFAULT;
	$sort_field = ( ! empty ( $sort_field ) ) ? $sort_field : ACS::SORT_FIELD_DEFAULT;
	$sort_order = ( ! empty ( $sort_order ) ) ? $sort_order : ACS::SORT_ORDER_DEFAULT;

	try {
		// Search data
		$acs_result = acs_index_documents_search( $keyword, ACS::SEARCH_FIELD_DEFAULT, $start, $size, ACS::QUERY_PARSER, $filter_query, $global_search, $type_field, $sort_field, $sort_order );
		$result_search = $acs_result->get_data();
	}
	catch ( Exception $e ) {
		$result_search = array();
	}

	// Get frontpage setting data
	$acs_frontpage_content_box_type = ACS::get_instance()->get_settings()->acs_frontpage_content_box_type;
	$acs_frontpage_content_box_value = ACS::get_instance()->get_settings()->acs_frontpage_content_box_value;
	$acs_results_no_results_msg = ACS::get_instance()->get_settings()->acs_results_no_results_msg;
	$acs_results_no_results_box_value = ACS::get_instance()->get_settings()->acs_results_no_results_box_value;

	// Turn on output buffering
	ob_start();

	// Check if needs AMP items
	$amp = isset( $_GET[ 'amp' ] );
	$items = [];

	if ( ! empty( $result_search ) && count( $result_search[ 'items' ] ) > 0 ) {
    global $post, $is_post_item, $term, $is_term_item, $result_search_item;

	// Get current theme
	$current_theme = wp_get_theme();

	// Documents founded, loop result items
	foreach ( $result_search[ 'items' ] as $post_item ) {
      	$result_search_item = $post_item;
      	if ( ! empty( $extras ) ) $result_search_item[ 'extras' ] = $extras;

      	// Setup current item
		if ( $post_item[ 'type' ] === ACS::TERM_KEY_SUFFIX ) {
			// Current item like a term
			$term = get_term( ( is_array( $result_search_item[ 'id' ] ) ) ? $result_search_item[ 'id' ][ 0 ] : intval( $result_search_item[ 'id' ] ) );

			// Set post type
			$type = 'category';

			// Set item types
			$is_post_item = false;
			$is_term_item = true;
		}
		else {
			// Current item like a post
			$post = get_post( ( isset( $post_item ) && ! empty( $post_item['id'] ) ) ? $post_item['id'] : $post_item );

			if ( empty( $post ) || $post == null ) {
				// Get post ID
				$id = ( is_array( $result_search_item[ 'id' ] ) ) ? $result_search_item[ 'id' ][ 0 ] : intval( $result_search_item[ 'id' ] );

				// Setup current post
				$post = get_post( $id );
				setup_postdata( $post );
			}
			else {
				setup_postdata( $post );
			}

			// Get post type
			$type = get_post_type();

			// Set item types
			$is_post_item = true;
			$is_term_item = false;
		}

      	if ( $amp ) {
			// Manage items array for AMP
			$post->permalink = get_permalink( $post );
			$post->post_content = substr( strip_tags( $post->post_content ), 0, 200 );
			$post->image  = get_the_post_thumbnail_url();
			$items[] = $post;
      	}
      	else {
        	switch ( $acs_frontpage_content_box_type ) {
          		case 'custom':
					get_template_part( $acs_frontpage_content_box_value );
					break;
          		case 'format':
            		if ( empty ( $acs_frontpage_content_box_value ) ) $acs_frontpage_content_box_value = 'content';

            		// Retrieve post type
            		if ( $type === 'post' ) {
						// Retrieve post type
						$format = get_post_format();
						if ( $format !== false ) {
							// Use format content box
							get_template_part( $acs_frontpage_content_box_value, $format );
						}
						else {
							// Use default content box
							get_template_part( $acs_frontpage_content_box_value );
						}
					}
					else {
						// Use type content box
						get_template_part( $acs_frontpage_content_box_value, $type );
					}
            		break;
				case 'plugin':
					if ( $current_theme == 'Twenty Twelve' ) {
						// Use optimized "twentytwelve" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentytwelve.php', false );
					}
					elseif ( $current_theme == 'Twenty Thirteen' ) {
						// Use optimized "twentythirteen" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentythirteen.php', false );
					}
					elseif ( $current_theme == 'Twenty Fourteen' ) {
						// Use optimized "twentyfourteen" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentyfourteen.php', false );
					}
					elseif ( $current_theme == 'Twenty Fifteen' ) {
						// Use optimized "twentyfifteen" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentyfifteen.php', false );
					}
					elseif ( $current_theme == 'Twenty Sixteen' ) {
						// Use optimized "twentysixteen" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentysixteen.php', false );
					}
					elseif ( $current_theme == 'Twenty Seventeen' ) {
						// Use optimized "twentyseventeen" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentyseventeen.php', false );
					}
					elseif ( $current_theme == 'Twenty Nineteen' ) {
						// Use optimized "twentynineteen" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentynineteen.php', false );
					}
					elseif ( $current_theme == 'Twenty Twenty' ) {
						// Use optimized "twentytwenty" template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-twentytwenty.php', false );
					}
					else {
						// Use default template
						load_template( dirname( __DIR__ ) . '/templates/cloud-search-content-default.php', false );
					}
					break;
				default:
					if ( $current_theme == 'Twenty Sixteen' ) {
						// Use default templates path for 'Twenty Sixteen' theme
						get_template_part( 'template-parts/content', 'search' );
					}
					else {
						// Use default templates path for generic themes
						get_template_part( 'content', 'search' );
					}
					break;
        		}
      		}
		}
	}
	else {
		// No documents founded
		if ( ! empty( $acs_results_no_results_box_value ) ) get_template_part( $acs_results_no_results_box_value );
	}

	// Return the contents of the output buffer
	if ( ! $amp ) {
		$items = ob_get_contents();
	}

	// Clean the output buffer and turn off output buffering
	ob_end_clean();

	// Reset post data
	wp_reset_postdata();

	// Prepare result object
	$acs_result = new ACS_Result();
	$acs_result->set_code( 'ok' );
	$acs_result->add_action( ACS::ACTION_SEARCH_DOCUMENTS );
	$acs_result->set_message( 'ok' );
	$acs_result->set_data( array(
		'items' => $items,
		'start' => ( $start + ( ( ! empty( $result_search[ 'items' ] ) ) ? count( $result_search[ 'items' ] ) : 0 ) ),
		'found' => $result_search[ 'found' ],
		'message' => ( $result_search[ 'found' ] == 0 && empty( $acs_results_no_results_box_value ) ) ? $acs_results_no_results_msg : '',
		'load_more' => ( $result_search[ 'found' ] > ( $start + $size ) ),
    	'extras' => $extras
	) );

	if ( $amp ) {
		echo json_encode( [ 'items' => $items ] );
	} else {
		// Return result object
		echo $acs_result->format_response();
	}

  	return null;
}

/**
 * Search documents in the index
 *
 * @param string $key
 * @param string $key_type
 * @param int $start
 * @param int $size
 * @param string $query_parser
 * @param string $filter_query
 * @param bool $global_search
 * @param string $type_field
 * @param string|array $sort_field
 * @param string|array $sort_order
 * @param bool $preserve_text
 * @param array $facets
 *
 * @return ACS_Result
 */
function acs_index_documents_search( $key, $key_type, $start = 0, $size = ACS::SEARCH_RETURN_FULL_ITEMS, $query_parser = ACS::QUERY_PARSER, $filter_query = '', $global_search = false, $type_field = ACS::TYPE_FIELD_DEFAULT, $sort_field = ACS::SORT_FIELD_DEFAULT, $sort_order = ACS::SORT_ORDER_DEFAULT, $preserve_text = false, $facets = null ) {
	// Get client
	$client = acs_get_domain_client();

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Prepare query options
	$query_options = acs_decode_search_query_options( $settings );

	// Prepare query
	$query = acs_decode_search_query( $key, $key_type, $query_parser );

	// Prepare sort
	$sort = acs_decode_search_sort( $sort_field, $sort_order );

	// Save search keyword
	ACS::get_instance()->set_key( $key );

	// Prepare search args
	$search_args = array(
		'query' => $query,
		'start' => intval( $start ),
		'size' => intval( $size ),
		'filterQuery' => acs_get_filter_query( $global_search, $type_field, $filter_query ),
		'queryParser' => $query_parser,
		'return' => '_all_fields,_score',
		'sort' => $sort
	);
	if ( ! empty( $query_options ) ) $search_args[ 'queryOptions' ] = json_encode( $query_options );
	if ( ! empty( $facets ) ) $search_args[ 'facet' ] = json_encode( $facets );

	// Apply 'acs_search_args' filter hook
	$search_args = apply_filters( 'acs_search_args', $search_args );

	// Search documents
	$result = $client->search( $search_args );

	// Read result
	$hit_found = $result->getPath( 'hits/found' ); // Total items
	$items = $result->getPath( 'hits' ); // Items array
	$result_facets = $result->getPath( 'facets' ); // Facets

	$post_items = null;
	$post_ids = array();
	$post_fields = array();
	if ( count( $items[ 'hit' ] ) > 0 ) {
		foreach ( $items[ 'hit' ] as $item )  {
			// Prepare IDs array (adding a type attribute)
			$post_ids[] = array(
				'id' => intval( $item[ 'fields' ][ 'id' ][ 0 ] ),
				'type' => ( strpos( $item[ 'id' ], ACS::TERM_KEY_SUFFIX ) !== false ) ? ACS::TERM_KEY_SUFFIX : ACS::DEFAULT_TYPE
			);

			// Save post original content
			$item[ 'fields' ][ 'post_content_original' ][ 0 ] = $item[ 'fields' ][ 'post_content' ][ 0 ];

			// Preserve text if you don't want to alter title/content/excerpt (typically when use "acs_index_documents_search" not in searches)
			if ( ! $preserve_text ) {
				// Strip item texts shortcodes
				if ( isset( $item[ 'fields' ][ 'post_content' ] ) && isset( $item[ 'fields' ][ 'post_content' ][ 0 ] ) ) $item[ 'fields' ][ 'post_content' ][ 0 ] = strip_shortcodes( $item[ 'fields' ][ 'post_content' ][ 0 ] );
				if ( isset( $item[ 'fields' ][ 'post_excerpt' ] ) && isset( $item[ 'fields' ][ 'post_excerpt' ][ 0 ] ) ) $item[ 'fields' ][ 'post_excerpt' ][ 0 ] = strip_shortcodes( $item[ 'fields' ][ 'post_excerpt' ][ 0 ] );

				// Truncate item text
				if ( isset( $item[ 'fields' ][ 'post_content' ] ) && isset( $item[ 'fields' ][ 'post_content' ][ 0 ] ) ) $item[ 'fields' ][ 'post_content' ][ 0 ] = acs_truncate( $item[ 'fields' ][ 'post_content' ][ 0 ] );

				// Highlight item text and title
				if ( $key != '*') {
					if ( isset( $item[ 'fields' ][ 'post_content' ] ) && isset( $item[ 'fields' ][ 'post_content' ][ 0 ] ) ) $item[ 'fields' ][ 'post_content' ][ 0 ] = acs_highlight_text( $item[ 'fields' ][ 'post_content' ][ 0 ], $key );
					if ( isset( $item[ 'fields' ][ 'post_title' ] ) && isset( $item[ 'fields' ][ 'post_title' ][ 0 ] ) ) $item[ 'fields' ][ 'post_title' ][ 0 ] = acs_highlight_title( $item[ 'fields' ][ 'post_title' ][ 0 ], $key );
				}
			}

			$post_fields[] = $item[ 'fields' ];
		}
	}

	if ( $global_search ) {
		// Get full results
		//TODO: which data can I expose with terms
		$post_items = $post_fields;
	}
	else {
		// Get only IDs results
		$post_items = $post_ids;
	}

	// Prepare result object
	$acs_result = new ACS_Result();
	$acs_result->set_data( array(
		'status' => $result->getPath( 'status' ),
		'start' => $start,
		'size' => $size,
		'found' => $hit_found,
		'items' => $post_items,
		'facets' => $result_facets
	) );

	// Return result object
	return $acs_result;
}
