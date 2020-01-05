<?php
/**
 * Sync (add) a document to the index
 *
 * @param \WP_Post|\WP_Term $item
 * @param bool $from_save_transaction
 *
 * @return bool
 */
function acs_index_document( $item, $from_save_transaction = false ) {
	return acs_put_documents( array( acs_prepare_document( $item, $from_save_transaction ) ) );
}

/**
 * Delete a document from the index
 *
 * @param \WP_Post|\WP_Term $item
 *
 * @return bool
 */
function acs_delete_document( $item ) {
	return acs_put_documents( array( array(
		'type' => 'delete',
		'id' => ( $item instanceof \WP_Term ) ? acs_get_document_key( $item->term_id ) : acs_get_document_key( $item->ID )
	) ) );
}

/**
 * Prepare document from post/term, with all index fields
 *
 * @param \WP_Post|\WP_Term $item
 * @param bool $from_save_transaction
 *
 * @return array
 */
function acs_prepare_document( $item, $from_save_transaction = false ) {
    try {
        // Get settings option
        $settings = ACS::get_instance()->get_settings();

	    if ( $item instanceof \WP_Term ) {
		    // Current item is a term
		    $item_id = $item->term_id;

		    // Prepare term fields array
		    $fields = array(
			    'site_id' => acs_get_site_id(),
			    'blog_id'=> acs_get_blog_id(),
			    'id' => $item_id,
			    'post_type' => $item->taxonomy,
			    'post_status' => ACS::DEFAULT_STATUS,
			    'post_title' => $item->name,
			    'post_content' => $item->description,
			    'post_url' => get_term_link( $item_id, $item->taxonomy )
		    );
	    }
	    else {
	    	// Current item is a post
		    $item_id = $item->ID;

		    // Get author
		    $item_author = $item->post_author;
		    $item_author_info = get_userdata( $item_author );
		    $item_author_name = $item_author_info->display_name;
		    if ( empty( $post_author_name ) ) $item_author_name = '-';

		    // Get default taxonomies
		    $taxonomy_category = acs_get_term_list( $item_id, 'category' );
		    $taxonomy_tag = acs_get_term_list( $item_id, 'post_tag' );

		    // Get custom taxonomies
		    $taxonomies_custom = array();
		    $acs_schema_taxonomies = $settings->acs_schema_taxonomies;

		    if ( ! empty ( $acs_schema_taxonomies ) ) {
			    // If there are some custom taxonomies
			    $acs_schema_taxonomies = explode( ACS::SEPARATOR, $acs_schema_taxonomies );

			    // Loop custom taxonomies
			    foreach ( $acs_schema_taxonomies as $acs_schema_taxonomy ) {
				    // Get current post custom taxonomy values
				    $taxonomy_custom = acs_get_term_list( $item_id, $acs_schema_taxonomy );

				    // Replace taxonomy slug "-" with "_" due to Amazon CloudSearch valid pattern rule
				    $acs_schema_taxonomy_clean = str_replace( '-', '_', $acs_schema_taxonomy );

				    $taxonomies_custom[ ACS::CUSTOM_TAXONOMY_PREFIX . $acs_schema_taxonomy_clean ] = $taxonomy_custom;
			    }
		    }

		    // Get custom fields
		    $fields_custom = array();
		    $acs_schema_fields = $settings->acs_schema_fields;

		    // Get custom field parameters
		    $acs_int_fields = ( ! empty( $settings->acs_schema_fields_int ) ) ? array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_int ) ) ) : array();
		    $acs_double_fields = ( ! empty( $settings->acs_schema_fields_double ) ) ? array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_double ) ) ) : array();
	
		    if ( ! empty ( $acs_schema_fields ) ) {
			    // If there are some custom fields
			    $acs_schema_fields = explode( ACS::SEPARATOR, $acs_schema_fields );

			    // Loop custom fields
			    foreach ( $acs_schema_fields as $acs_schema_field ) {
				    // Get current post custom field values
				    if ( $from_save_transaction && isset( $_POST[ $acs_schema_field ] ) ) {
					    // Read from request POST
					    $field_custom = $_POST[ $acs_schema_field ];
				    }
				    else {
					    // Read from post meta
					    $field_custom = get_post_meta( $item_id, $acs_schema_field, true );
				    }

				    // Replace field slug "-" with "_" due to Amazon CloudSearch valid pattern rule
				    $acs_schema_field_clean = str_replace( '-', '_', $acs_schema_field );

				    // Lowercase field due to Amazon CloudSearch valid strings rule
				    $acs_schema_field_clean = strtolower( $acs_schema_field_clean );

				    // Verify and convert Int and Double fields
				    if ( isset( $acs_int_fields ) && in_array( $acs_schema_field_clean, $acs_int_fields ) ) {
					    $field_custom = intval( $field_custom );
				    }
				    else if ( isset( $acs_double_fields ) && in_array( $acs_schema_field_clean, $acs_double_fields ) ) {
					    $field_custom = doubleval( $field_custom );
				    }

				    if ( ! empty( $field_custom ) ) $fields_custom[ ACS::CUSTOM_FIELD_PREFIX . $acs_schema_field_clean ] = $field_custom;
			    }
		    }

		    $item_image = '';
		    if ( ! empty( $settings->acs_schema_fields_custom_image_id ) && is_plugin_active( 'multiple-post-thumbnails/multi-post-thumbnails.php' ) ) {
			    // Retrieve post image from "Multiple Post Thumbnails" plugin if a image id is provided and plugin is active
			    $item_image = MultiPostThumbnails::get_post_thumbnail_url( $item->post_type, $settings->acs_schema_fields_custom_image_id, $item_id );
		    }
		    else if ( ! empty( $settings->acs_schema_fields_image_size ) ) {
			    // Retrieve post image if a image size name is provided
			    $image_object = wp_get_attachment_image_src( get_post_thumbnail_id( $item_id ), $settings->acs_schema_fields_image_size );

			    if ( ! empty( $image_object ) && ! empty( $image_object[0] ) && $image_object[0] != '' ) {
				    // Found image, use it
				    $item_image = $image_object[0];
			    }
		    }

		    // Prepare post fields array (merging default fields and custom fields/taxonomies)
		    $fields = array(
			    'site_id' => acs_get_site_id(),
			    'blog_id'=> acs_get_blog_id(),
			    'id' => $item_id,
			    'post_type' => $item->post_type,
			    'post_status' => $item->post_status,
			    'post_format' => get_post_format( $item_id ),
			    'post_title' => $item->post_title,
			    'post_content' => $item->post_content,
			    'post_excerpt' => $item->post_excerpt,
			    'post_url' => get_permalink( $item_id ),
			    'post_image' => $item_image,
			    'post_date' => strtotime( $item->post_date ),
			    'post_date_gmt' => strtotime( $item->post_date_gmt ),
			    'post_modified' => strtotime( $item->post_modified ),
			    'post_modified_gmt' => strtotime( $item->post_modified_gmt ),
			    'post_author' => $item_author,
			    'post_author_name' => $item_author_name,
			    'category' => $taxonomy_category,
			    'tag' => $taxonomy_tag
		    );
		    if ( ! empty( $taxonomies_custom ) ) $fields = array_merge( $fields, $taxonomies_custom );
		    if ( ! empty( $fields_custom ) ) $fields = array_merge( $fields, $fields_custom );

		    // Manipulate standard fields (in your sub-theme add a filter "cloud_search_<POST_TYPE>_fields" that adds all necessary fields of your theme)
		    $fields = apply_filters( "cloud_search_{$item->post_type}_fields", $fields, $item, $from_save_transaction );
	    }

        // Prepare doc object
        $doc = array(
            'type' => 'add',
            'id' => acs_get_document_key( $item_id, ( $item instanceof \WP_Term ) ? ACS::TERM_KEY_SUFFIX : '' ),
            'fields' => $fields
        );

        return $doc;
    }
    catch ( \Exception $e ) {
        return null;
    }
}

/**
 * Put to index a list of documents
 *
 * @param array $docs
 *
 * @return bool
 */
function acs_put_documents( $docs ) {
    try {
        // Get client
        $client = acs_get_domain_client();

	    // Get settings option
	    $settings = ACS::get_instance()->get_settings();

	    // Encode docs
	    $docs = json_encode( $docs );

		// Try to remove invalid chars
	    if ( ! empty( $settings->acs_schema_fields_invalid_chars ) ) {
	    	$invalid_chars = explode( '|', $settings->acs_schema_fields_invalid_chars );
			if ( ! empty( $invalid_chars ) ) {
				foreach ( $invalid_chars as $invalid_char ) {
					// Escape existing backslashes first to prevent double-escaping
					$invalid_char = str_replace( '\\', '\\\\', $invalid_char );

					// Remove invalid char
					$docs = str_replace( $invalid_char, '', $docs );
				}
			}
	    }

        // Upload documents
        $result = $client->uploadDocuments( array(
            'documents' => $docs,
            'contentType' => 'application/json'
        ) );

        // Return result
        return $result->getPath( 'status' ) == 'success';
    }
    catch ( \Exception $e ) {
	    error_log( __( 'Error uploading documents', ACS::PREFIX ) . ': ' . $e->getMessage() );

        return false;
    }
}

/**
 * Return taxonomy terms by post id
 *
 * @param $post_id
 * @param $taxonomy
 *
 * @return array
 */
function acs_get_term_list( $post_id, $taxonomy ) {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();

	$terms = get_the_terms( $post_id, $taxonomy );
	$term_list = array();
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$term_list[] = $term->term_id . $settings->acs_schema_fields_separator . $term->name;
		}
	}
	return $term_list;
}
