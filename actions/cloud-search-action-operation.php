<?php
/**
 * Update documents to index
 *
 * @param string $update_id
 * @param string $update_key
 * @param string $update_value
 *
 * @return ACS_Result
 */
function acs_index_documents_update( $update_id, $update_key, $update_value ) {
	$acs_result = new ACS_Result();
	$count = 0;

	// Set defaults
	$global_search = true;
	$start = 0;
	$size = 1;
	$filter_query = '';
	$type_field = ACS::TYPE_FIELD_DEFAULT;
	$sort_field = ACS::SORT_FIELD_DEFAULT;
	$sort_order = ACS::SORT_ORDER_DEFAULT;

	// Search document to update by the unique document ID field
	try {
		// Search data
		$acs_result = acs_index_documents_search( $update_id, '_id', $start, $size, ACS::QUERY_PARSER, $filter_query, $global_search, $type_field, $sort_field, $sort_order );
		$result_search = $acs_result->get_data();
	}
	catch ( Exception $e ) {
		$result_search = array();
	}

	// Loop results
	if ( ! empty( $result_search ) && $result_search[ 'found' ] == 1 )  {
		// Update document field
		$document_array = $result_search[ 'items' ][ 0 ];

		// Search if key to update exists in the document
		if ( array_key_exists( $update_key, $document_array ) ) {
			// Update the value
			$document_array[ $update_key ] = array( $update_value );

			// Prepare array fields (remove values array for scalar value type)
			$document_fields = array();
			foreach ( $document_array as $document_array_key => $document_array_value ) {
				if ( is_array( $document_array_value ) && count( $document_array_value ) == 1) $document_fields[ $document_array_key ] = $document_array_value[ 0 ];
				else $document_fields[ $document_array_key ] = $document_array_value;
			}

			// User '_score' field from update
			unset( $document_fields[ '_score' ] );

			// Prepare doc object
			$doc = array(
				'type' => 'add',
				'id' => $update_id,
				'fields' => $document_fields
			);
			$docs[] = $doc;

			// Put document to index
			if ( acs_put_documents( $docs ) ) $count = $count + 1;
		}
	}

	// Prepare result object
	if ( $count > 0 ) {
		$acs_result->set_code( 'ok' );
		$acs_result->set_message( 'ok' );
	}
	else {
		$acs_result->set_code( 'error' );
		$acs_result->set_message( 'error' );
	}
	$acs_result->add_action( ACS::ACTION_UPDATE_DOCUMENTS );
	$acs_result->set_data( array(
		'found' => $count
	) );

	// Return result object
	return $acs_result;
}
add_action('wp_ajax_acs_index_documents_update', 'acs_index_documents_update' );
add_action('wp_ajax_nopriv_acs_index_documents_update', 'acs_index_documents_update' );

/**
 * Sync documents to index
 *
 * @param int $key
 * @param string $key_type
 * @param string $entity_type
 *
 * @return ACS_Result
 */
function acs_index_documents_sync( $key = 0, $key_type = ACS::SEARCH_KEY_TYPE_DEFAULT, $entity_type = ACS::SEARCH_ENTITY_TYPE_DEFAULT ) {
    $acs_result = new ACS_Result();
	$change_items_type = false;
    $docs = array();
    $count = 0;

    // Detect if action is sync or async ('all' means async)
    $acs_is_sync_action = ( $key_type != ACS::SEARCH_KEY_TYPE_DEFAULT );

    // Verify nonce
    if ( ! acs_verify_nonce() && ! $acs_is_sync_action ) {
        $acs_result->set_code( 'error' );
        $acs_result->set_message( 'bad nonce' );
        echo $acs_result->format_response();
        die();
    }

    // Read parameters
    $start = filter_var( $_REQUEST[ 'start' ], FILTER_VALIDATE_BOOLEAN );

    // Read options
    $option_status = get_option( ACS::OPTION_STATUS, ACS::STATUS_NO_OPERATION );
    $option_items = get_option( ACS::OPTION_ITEMS, 0 );
	$option_switch = (bool) get_option( ACS::OPTION_SWITCH, false );

    // Calculate data
    if ( $option_status == ACS::STATUS_NO_OPERATION && $option_items == -1 && ! $start && ! $acs_is_sync_action ) {
        // No operation (last execution is the last one)
    }
    else {
        if ( $option_status == ACS::STATUS_NO_OPERATION || $acs_is_sync_action ) {
            // No operation in progress or sync action, reset data
            $option_items = 0;
	        $option_switch = false;
            $page = 0;
        }
        else {
            // Sync already in progress
            $page = ( $option_items / ACS::SYNC_CHUNK ) + 1;
        }

	    // Get settings option
	    $settings = ACS::get_instance()->get_settings();

        // Get posts to sync
        switch ( $key_type ) {
            case 'id':
            	if ( $entity_type != ACS::SEARCH_ENTITY_TYPE_DEFAULT ) {
		            // Get term by id
		            $items[] = get_term_by( 'id', $key, $entity_type );
	            }
	            else {
		            // Get post by id
		            $items[] = get_post( $key );
	            }

                break;
            default:
            	// All items
	            $items = array();

                // Get chunk posts (if switch not already done)
				if ( $option_switch === false ) {
					// Retrieve allowed post statutes to manage
					$allowed_statuses = apply_filters( 'acs_post_transition_allowed_statuses', array( 'publish' ), null );

					$items = get_posts( array(
						'post_status' => $allowed_statuses,
						'post_type' => acs_get_schema_types(),
						'posts_per_page' => ACS::SYNC_CHUNK,
						'paged' => $page
					) );
				}

	            // Check if posts are less then chunk limit to change items query type
	            if ( count( $items ) < ACS::SYNC_CHUNK ) $change_items_type = true;
	            else $change_items_type = false;

                // Switch to query terms
                if ( $change_items_type && count( $items ) === 0 ) {
	                // Get chunk terms
	                $items = get_terms( array(
		                'taxonomy' => $settings->acs_schema_taxonomies ? array_merge( array( 'category' ) , explode( ACS::SEPARATOR, $settings->acs_schema_taxonomies ) ) : array( 'category' ),
		                'hide_empty' => false,
		                'number' => ACS::SYNC_CHUNK,
		                'offset' => $page
	                ) );

	                // Check if terms are less then chunk limit to stop loops
	                if ( count( $items ) < ACS::SYNC_CHUNK ) $change_items_type = false;
                }

                break;
        }

        // Loop items
        if ( ! empty( $items) )  {
            foreach ( $items as $item ) {
	            // Read post exclusion field
	            if ( $entity_type != ACS::SEARCH_ENTITY_TYPE_DEFAULT ) {
		            $excluded = get_term_meta( $item->ID, ACS::EXCLUDE_FIELD, true );
	            }
	            else {
		            $excluded = get_post_meta( $item->ID, ACS::EXCLUDE_FIELD, true );
	            }

	            if ( ! isset( $excluded ) || empty( $excluded ) || $excluded != 1 ) {
		            // Prepare and add document
		            $prepared_doc_array = acs_prepare_document( $item );
		            if ( ! empty( $prepared_doc_array ) ) {
			            $docs[] = $prepared_doc_array;
		            }
	            }

	            // Increase counter anyway (also for excluded items)
	            $count = $count + 1;
            }
        }

        // Put documents to index
        if ( $count > 0 ) {
            acs_put_documents( $docs );
        }

        // Set items as current items count plus managed items
        $option_items = $option_items + $count;

        // Set status
        if ( $count < ACS::SYNC_CHUNK && ! $change_items_type ) {
            // No other items
            $option_status = ACS::STATUS_NO_OPERATION;
            $option_items = -1; // To detect finish operation
	        $option_switch = false;
        }
        else if ( $count < ACS::SYNC_CHUNK && $change_items_type ) {
	        // Switch to terms items
	        $option_status = ACS::STATUS_SYNC_ACTIVE;
	        $option_items = 0;
	        $option_switch = true;
        }
        else {
            // Could have other items
            $option_status = ACS::STATUS_SYNC_ACTIVE;
        }

        // Update options
        update_option( ACS::OPTION_STATUS, $option_status );
	    update_option( ACS::OPTION_SWITCH, $option_switch );
        update_option( ACS::OPTION_ITEMS, $option_items );
    }

    // Prepare result object
    $acs_result->set_code( 'ok' );
    $acs_result->add_action( ACS::ACTION_SYNC_DOCUMENTS );
    $acs_result->set_message( 'ok' );
    $acs_result->set_data( array(
        'found' => ( $acs_is_sync_action ) ? $count : $option_items,
        'status' => $option_status
    ) );

    // Return result object
    if ( $key_type != 'all' ) {
        // Single document result (sync call)
        return $acs_result;
    }
    else {
        // Multiple document result (async call)
        echo $acs_result->format_response();
        die();
    }
}
add_action('wp_ajax_acs_index_documents_sync', 'acs_index_documents_sync' );
add_action('wp_ajax_nopriv_acs_index_documents_sync', 'acs_index_documents_sync' );

/**
 * Delete documents in the index
 *
 * @param string $key
 * @param string $key_type
 *
 * @return ACS_Result
 */
function acs_index_documents_delete( $key = '', $key_type = ACS::SEARCH_KEY_TYPE_DEFAULT ) {
    $acs_result = new ACS_Result();
    $docs = array();
    $count = $total = 0;

    // Detect if action is sync or async ('all' means async)
    $acs_is_sync_action = ( $key_type != 'all' );

    // Verify nonce
    if ( ! acs_verify_nonce() && ! $acs_is_sync_action ) {
        $acs_result->set_code( 'error' );
        $acs_result->set_message( 'bad nonce' );
        echo $acs_result->format_response();
        die();
    }

    // Read parameters
    $start = filter_var( $_REQUEST[ 'start' ], FILTER_VALIDATE_BOOLEAN );

    // Read options
    $option_status = get_option( ACS::OPTION_STATUS, ACS::STATUS_NO_OPERATION );
    $option_items = get_option( ACS::OPTION_ITEMS, 0 );

    // Calculate data
    if ( $option_status == ACS::STATUS_NO_OPERATION && $option_items == -1 && ! $start && ! $acs_is_sync_action ) {
        // No operation (last execution is the last one)
    }
    else {
        if ( $option_status == ACS::STATUS_NO_OPERATION || $acs_is_sync_action ) {
            // No operation in progress, reset data
        }
        else {
            // Delete already in progress
        }

        // Get client
        $client = acs_get_domain_client();

        // Get posts to delete
        switch ( $key_type ) {
            case 'id':
            	// Single item
	            $result = null;

	            // Prepare array of documents to delete
	            $docs[] = array(
		            'type' => 'delete',
		            'id' => trim( $key )
	            );

	            $count = $total = 1;

                break;
            default:
                // All items
                $result = $client->search( array(
                    'query' => '*:*',
                    'start' => 0,
                    'size' => ACS::DELETE_CHUNK,
                    'filterQuery' => acs_get_filter_query( false, ACS::TYPE_FIELD_DEFAULT, '', false ),
                    'queryParser' => ACS::QUERY_PARSER
                ) );

                break;
        }

        // Read result
	    if ( ! empty( $result ) ) {
		    $hit = $result->getPath( 'hits/found' );
		    $items = $result->getPath( 'hits' );
		    $total = intval( $hit );

		    if ( ! empty( $items ) && count( $items[ 'hit' ] ) > 0 ) {
			    // If items founded, loop items
			    foreach ( $items[ 'hit' ] as $item ) {
				    // Prepare array of documents to delete
				    $docs[] = array(
					    'type' => 'delete',
					    'id' => $item[ 'id' ]
				    );

				    $count = $count + 1;
			    }
		    }
	    }

        if ( count( $docs ) > 0 ) {
            // If there are documents to delete, proceed deleting it
            $client->uploadDocuments( array(
                'documents' => json_encode( $docs ),
                'contentType' => 'application/json'
            ) );
        }

        // Set items as total remaining items
        $option_items = $total;

        // Set status
        if ( $count < ACS::DELETE_CHUNK ) {
            // No other items
            $option_status = ACS::STATUS_NO_OPERATION;
            $option_items = -1; // To detect finish operation
        }
        else {
            // Could have other items
            $option_status = ACS::STATUS_DELETE_ACTIVE;
        }

        // Update options
        update_option( ACS::OPTION_STATUS, $option_status );
        update_option( ACS::OPTION_ITEMS, $option_items );
    }

    // Prepare result object
    $acs_result->set_code( 'ok' );
    $acs_result->add_action( ACS::ACTION_DELETE_DOCUMENTS );
    $acs_result->set_message( 'ok' );
    $acs_result->set_data( array(
        'found' => ( $acs_is_sync_action ) ? $count : $option_items,
        'status' => $option_status
    ) );

    // Return result object
    if ( $key_type != 'all' ) {
        // Single document result (sync call)
        return $acs_result;
    }
    else {
        // Multiple document result (async call)
        echo $acs_result->format_response();
        die();
    }
}
add_action('wp_ajax_acs_index_documents_delete', 'acs_index_documents_delete' );
add_action('wp_ajax_nopriv_acs_index_documents_delete', 'acs_index_documents_delete' );

/**
 * Stop documents sync in the index for logged users
 *
 * @return ACS_Result
 */
function acs_index_documents_stop() {
	$acs_result = new ACS_Result();

	// Verify nonce
	if ( ! acs_verify_nonce() ) {
		$acs_result->set_code( 'error' );
		$acs_result->set_message( 'bad nonce' );
		echo $acs_result->format_response();
		die();
	}

	// Reset options to force to sync no other items
	$option_status = ACS::STATUS_NO_OPERATION;
	$option_items = -1; // To set finish operation

	// Update options
	update_option( ACS::OPTION_STATUS, $option_status );
	update_option( ACS::OPTION_ITEMS, $option_items );

	// Prepare result object
	$acs_result->set_code( 'ok' );
	$acs_result->add_action( ACS::ACTION_STOP_SYNC_DOCUMENTS );
	$acs_result->set_message( 'ok' );

	// Return result object
	return $acs_result;
}
add_action('wp_ajax_acs_index_documents_stop', 'acs_index_documents_stop' );

/**
 * Stop documents sync in the index for guest users
 */
function nopriv_acs_index_documents_stop() {
	die();
}
add_action( 'wp_ajax_nopriv_acs_index_documents_stop', 'nopriv_acs_index_documents_stop' );
