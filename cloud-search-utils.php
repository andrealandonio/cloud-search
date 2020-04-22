<?php
/**
 * Get current site ID
 *
 * @return int
 */
function acs_get_site_id() {
	$settings = get_option( ACS::OPTION_SETTINGS );

	// Overrides site info (if provided by the user)
	if ( isset( $settings->acs_network_site_id ) && ! empty( $settings->acs_network_site_id ) && is_numeric( $settings->acs_network_site_id ) ) {
		return intval( $settings->acs_network_site_id );
	}
	else {
		// Get site info from WP
		if ( is_multisite() ) {
			// WordPress multisite
			$current_site = get_current_site();
			return $current_site->id;

		}
		else {
			// No WordPress multisite, use 1 as site ID
			return 1;
		}
	}
}

/**
 * Get current blog ID
 *
 * @return int
 */
function acs_get_blog_id() {
	$settings = get_option( ACS::OPTION_SETTINGS );

	// Overrides blog info (if provided by the user)
	if ( isset( $settings->acs_network_blog_id ) && ! empty( $settings->acs_network_blog_id ) && is_numeric( $settings->acs_network_blog_id ) ) {
		return intval( $settings->acs_network_blog_id );
	}
	else {
		// Get blog info from WP
		if ( is_multisite() ) {
			// WordPress multisite
			return get_current_blog_id();
		}
		else {
			// No WordPress multisite, use 1 as blog ID
			return 1;
		}
	}
}

/**
 * Check plugin capabilities for current user
 *
 * @return bool
 */
function acs_check_user_capabilities() {
	if ( is_multisite() ) {
		if ( ! current_user_can( 'manage_network_plugins' ) ) {
			// Don't allow if the user can't manage network plugins
			return false;
		}
	}
	else {
		if ( ! current_user_can( 'activate_plugins' ) ) {
            // Don't allow if user doesn't have plugin management privileges
            return false;
		}
	}

	return true;
}

/**
 * Check schema basic configuration
 *
 * @return bool
 */
function acs_check_basic_configuration() {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Check if basic settings fields are empty
	return ( ( defined( 'WP_ACS_ACCESS_KEY' ) || ! empty( $settings->acs_aws_access_key_id ) ) &&
             ( defined( 'WP_ACS_SECRET_KEY' ) || ! empty( $settings->acs_aws_secret_access_key ) )  &&
             ( defined( 'WP_ACS_REGION' ) || ! empty( $settings->acs_aws_region ) ) &&
	         ( defined( 'WP_ACS_SEARCH_ENDPOINT') || ! empty( $settings->acs_search_endpoint ) ) &&
	         ( defined( 'WP_ACS_SEARCH_DOMAIN') || ! empty( $settings->acs_search_domain_name ) ) );
}

/**
 * Check schema mandatory configuration
 *
 * @return bool
 */
function acs_check_mandatory_configuration() {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Check if mandatory settings fields are empty
	return ( ( defined ( 'WP_ACS_SEARCH_ENDPOINT' ) || ! empty( $settings->acs_search_endpoint ) ) &&
             ( defined ( 'WP_ACS_SEARCH_DOMAIN' ) || ! empty( $settings->acs_search_domain_name ) ) );
}

/**
 * Replace first occurrence of a string with a replacing value
 *
 * @param string $search
 * @param string $replace
 * @param string $subject
 *
 * @return mixed
 */
function acs_str_replace_first( $search, $replace, $subject ) {
	$pos = strpos( $subject, $search );
	if ( $pos !== false ) {
		$subject = substr_replace( $subject, $replace, $pos, strlen( $search ) );
	}

	return $subject;
}

/**
 * Validate field pattern
 *
 * @param string $field
 *
 * @return mixed
 */
function acs_str_validate_field_pattern( $field ) {
	return str_replace( '-', '_', $field );
}

/**
 * Truncate string
 *
 * @param string $text
 *
 * @return string
 */
function acs_truncate( $text ) {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();

    if ( ! empty( $settings->acs_filter_text_length ) && is_integer( $settings->acs_filter_text_length ) && intval( $settings->acs_filter_text_length ) > 0 ) {
        // Truncate item text
        if ( ! empty( $settings->acs_filter_text_length_type ) && $settings->acs_filter_text_length_type == 'chars' ) {
            // Truncate by chars
            $text = acs_trunc_by_chars( $text, intval( $settings->acs_filter_text_length ) );
        }
        else {
            // Truncate by words
            $text = acs_trunc_by_words( $text, intval( $settings->acs_filter_text_length ) );
        }
    }

    return $text;
}

/**
 * Truncate string by words
 *
 * @param string $phrase
 * @param int $max
 *
 * @return string
 */
function acs_trunc_by_words( $phrase, $max ) {
    $phrase = strip_tags( strip_shortcodes( $phrase ) );
    $phrase_array = explode( ' ', $phrase );
    if ( count( $phrase_array ) > $max && $max > 0 ) $phrase = implode( ' ', array_slice( $phrase_array, 0, $max ) ) . '...';
    return $phrase;
}

/**
 * Truncate string by chars
 *
 * @param string $phrase
 * @param int $max
 *
 * @return string
 */
function acs_trunc_by_chars( $phrase, $max ) {
    $phrase = strip_tags( strip_shortcodes( $phrase ) );
    if ( strlen( $phrase ) > $max ) {
        $phrase = substr( $phrase, 0, $max );
        $i = strrpos( $phrase, ' ' );
        $phrase = substr( $phrase, 0, $i );
        $phrase = $phrase . '...';
    }
    return $phrase;
}

/**
 * Highlight text
 *
 * @param string $text
 * @param string $key
 *
 * @return string
 */
function acs_highlight_text( $text, $key ) {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();

    // Basic wrap
    $wrap = '$0';

    // Add container style
    switch( $settings->acs_highlight_type ) {
        case 'strong':
            $wrap = '<strong>' . $wrap . '</strong>';
            break;
        case 'italic':
            $wrap = '<i>' . $wrap . '</i>';
            break;
        case 'underline':
            $wrap = '<u>' . $wrap . '</u>';
            break;
        default:
            break;
    }

    // Add text color
    $text_color = '';
    if ( ! empty( $settings->acs_highlight_color_text ) ) $text_color = 'color:' . $settings->acs_highlight_color_text . ';';

    // Add background color
    $background_color = '';
    if ( ! empty( $settings->acs_highlight_color_background ) ) $background_color = 'background-color:' . $settings->acs_highlight_color_background . ';';

    // Compose wrap
    if ( ! empty( $text_color ) || ! empty( $background_color )  || ! empty( $settings->acs_highlight_style )  || ! empty( $settings->acs_highlight_class ) ) {
        $wrap = '<span style="' . $text_color . $background_color . $settings->acs_highlight_style . '" class="' . $settings->acs_highlight_class . '">' . $wrap . '</span>';
    }

	// Quote string with slashes
	$key = addcslashes( $key, '/' );

    // Try to highlight text (suppress warnings)
    $text = @preg_replace( "/$key/i", $wrap, $text );

    return $text;
}

/**
 * Highlight title
 *
 * @param string $title
 * @param string $key
 *
 * @return string
 */
function acs_highlight_title( $title, $key ) {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();

    // Try to highlight titles is enabled
    if ( $settings->acs_highlight_titles == 1 ) return acs_highlight_text( $title, $key );
    else return $title;
}

/**
 * Get all defined site types
 *
 * @return array
 */
function acs_get_all_site_types() {
	$site_types = array();

	// Get site post types
	$post_types = get_post_types( '', 'objects' );

	// Loop site post types (alias WP objects)
	foreach ( $post_types as $post_type ) {
		if ( !array_key_exists( $post_type->name, $site_types ) ) {
			$site_types[ $post_type->name ] = $post_type->labels->name;
		}
	}

	return $site_types;
}

/**
 * Get all defined site fields
 *
 * @var wpdb $wpdb
 *
 * @return array
 */
function acs_get_all_site_fields() {
	global $wpdb;

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Get custom field types prefix/suffix
	$acs_schema_fields_prefix = explode( ACS::SEPARATOR, $settings->acs_schema_fields_prefix );

	// Prepare query filter for prefix/suffix
	if ( ! empty( $settings->acs_schema_fields_prefix ) && count( $acs_schema_fields_prefix ) ) {
		$query_where_filter = '1=1 AND (';
		$count = 0;

		// Loop field prefix to compose query where conditions
		foreach ( $acs_schema_fields_prefix as $fields_prefix ) {
			if ( $count != 0 ) $query_where_filter .= ' OR ';
			$query_where_filter .= 'meta_key LIKE "%' . $fields_prefix . '%"';
			$count = $count + 1;
		}
		$query_where_filter .= ')';
	}
	else {
		$query_where_filter = 'meta_key NOT LIKE "\_%"';
	}

	// Query database
	$fields = $wpdb->get_results( 'SELECT DISTINCT meta_key FROM ' . $wpdb->postmeta . ' WHERE ' . $query_where_filter );

	// Loop results
	$site_fields = array();
	foreach ( $fields as $field ) {
		if ( ! empty( $field->meta_key ) && !array_key_exists( $field->meta_key, $site_fields ) ) {
			$site_fields[ $field->meta_key ] = $field->meta_key;
		}
	}

	return $site_fields;
}

/**
 * Get all defined site taxonomies
 *
 * @param bool|false $builtin
 *
 * @return array
 */
function acs_get_all_site_taxonomies( $builtin = false ) {
	// Get site taxonomies
	$site_taxonomies = get_taxonomies( array(
		'_builtin' => $builtin
	) );

	return $site_taxonomies;
}

/**
 * Decode action query (used before a search, sync or delete action)
 *
 * @param string $key
 * @param string $key_type
 * @param string $query_parser
 *
 * @return string
 */
function acs_decode_search_query( $key, $key_type = ACS::TYPE_FIELD_DEFAULT, $query_parser = ACS::QUERY_PARSER ) {
	if ( $query_parser != ACS::QUERY_PARSER ) {
		// Other query parser, do nothing
		$result = $key;
	}
	else {
		// Lucene query parser
		if ( $key_type == ACS::TYPE_FIELD_DEFAULT ) {
			// Generic search
			if ( empty( $key ) ) {
				// Use default search if key is not provided
				// $result = '*:*';
				$result = '*';
			}
			else {
				// Search in all fields the key provided (append in '*' for strings)
				// if ( is_numeric( $key ) ) $result = $key;
				// else $result = $key . '*';
				$result = $key;
			}
		}
		else {
			// Specific search
			if ( empty( $key ) ) {
				// Use default search if key is not provided
				$result = $key_type . ':*';
			}
			else {
				// Search in specified fields the key provided
				$result = $key_type . ':' . $key;
			}
		}
	}

	return $result;
}

/**
 * Decode sort clauses
 *
 * @param string|array $sort_fields
 * @param string|array $sort_orders
 *
 * @return string
 */
function acs_decode_search_sort( $sort_fields, $sort_orders ) {
	$result = '';

	if ( is_array( $sort_fields ) && is_array( $sort_orders ) && count( $sort_fields ) == count( $sort_orders ) ) {
	    // Sort fields are both arrays with the same number of items, compose multiple sort query
        $position = 0;
        foreach ( $sort_fields as $sort_field ) {
            if ( $position != 0 ) $result .= ', ';
	        $result .= $sort_field . ' ' . $sort_orders[ $position++ ];
        }
    }
    elseif ( ! is_array( $sort_fields ) && ! is_array( $sort_orders ) ) {
	    // Sort fields are not arrays, compose single sort query
	    $result = $sort_fields . ' ' . $sort_orders;
    }
    else {
	    // Use defaults
	    $result = ACS::SORT_FIELD_DEFAULT . ' ' . ACS::SORT_ORDER_DEFAULT;
    }

	return $result;
}

/**
 * Decode action query options
 *
 * @param stdClass $settings
 *
 * @return string
 */
function acs_decode_search_query_options( $settings ) {
	$query_options = '';
	if ( ! empty( $settings->acs_results_field_weights ) ) {
		$fields_weights = htmlspecialchars( stripslashes( $settings->acs_results_field_weights ) );
		$query_options = array( 'fields' => explode( ',', $fields_weights ) );
	}

	return $query_options;
}

/**
 * Draw search key type select box
 *
 * @return string
 */
function acs_draw_search_key_type_select() {
	$return = '<select id="acs_form_action_key_type" name="acs_form_action_key_type">';

	// Loop search field types
	foreach ( ACS::$SEARCH_FIELD_TYPES as $search_field_type ) {
		$return .= '<option value="' . $search_field_type . '">' . __( $search_field_type, ACS::PREFIX ) . '</option>';
	}
	$return .= '</select>';

	return $return;
}

/**
 * Draw action key type select
 *
 * @return string
 */
function acs_draw_action_key_type_select() {
	$return = '<select id="acs_form_action_key_type" name="acs_form_action_key_type">';

	// Loop search field key types
	foreach ( ACS::$ACTION_FIELD_KEY_TYPES as $action_field_key_type ) {
		$return .= '<option value="' . $action_field_key_type . '">' . __( $action_field_key_type, ACS::PREFIX ) . '</option>';
	}
	$return .= '</select>';

	return $return;
}

/**
 * Draw action entity type select
 *
 * @return string
 */
function acs_draw_action_entity_type_select() {
	$return = '<select id="acs_form_action_entity_type" name="acs_form_action_entity_type">';

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Loop search field entity types
	foreach ( ACS::$ACTION_FIELD_ENTITY_TYPES as $action_field_entity_type ) {
		$return .= '<option value="' . $action_field_entity_type . '">' . __( $action_field_entity_type, ACS::PREFIX ) . '</option>';
	}

	// Add schema selected taxonomies
    if ( ! empty( $settings->acs_schema_taxonomies ) ) {
	    foreach ( explode( ',', $settings->acs_schema_taxonomies ) as $acs_schema_taxonomy ) {
		    $return .= '<option value="' . $acs_schema_taxonomy . '">' . __( $acs_schema_taxonomy, ACS::PREFIX ) . '</option>';
	    }
    }

	$return .= '</select>';

	return $return;
}

/**
 * Draw content box type select
 *
 * @param string $selected
 *
 * @return string
 */
function acs_draw_content_box_type_select( $selected ) {
	$return = '<select id="acs_frontpage_content_box_type" name="acs_frontpage_content_box_type">';

	// Loop content box types
	foreach ( ACS::$CONTENT_BOX_TYPES as $content_box_type_key => $content_box_type_value ) {
		$return .= '<option value="' . $content_box_type_key . '" ' . ( ( $content_box_type_key == $selected) ? 'selected="selected"' : '' ) . '>' . __( $content_box_type_value, ACS::PREFIX ) . '</option>';
	}
	$return .= '</select>';

	return $return;
}

/**
 * Draw author format select
 *
 * @param string $selected
 *
 * @return string
 */
function acs_draw_author_format_select( $selected ) {
	$return = '<select id="acs_results_format_author" name="acs_results_format_author">';

	// Loop author formats
	foreach ( ACS::$AUTHOR_FORMATS as $author_format_key => $author_format_value ) {
		$return .= '<option value="' . $author_format_key . '" ' . ( ( $author_format_key == $selected) ? 'selected="selected"' : '' ) . '>' . __( $author_format_value, ACS::PREFIX ) . '</option>';
	}
	$return .= '</select>';

	return $return;
}

/**
 * Draw filter type fields select
 *
 * @param string $selected
 *
 * @return string
 */
function acs_draw_filter_type_fields( $selected ) {
	$return = '<select id="acs_filter_type_field" name="acs_filter_type_field" class="acs_filters">';
    $return .= '<option value="all">' . __( 'All', ACS::PREFIX ) . '</option>';

    $acs_schema_types = ACS::get_instance()->get_settings()->acs_schema_types;
    $acs_schema_types = $acs_schema_types ? explode( ACS::SEPARATOR, $acs_schema_types ) : array();

	// Loop type fields
	foreach ( $acs_schema_types as $acs_schema_types_field_key => $acs_schema_types_field_value ) {
		$return .= '<option value="' . $acs_schema_types_field_value . '" ' . ( ( $acs_schema_types_field_value == $selected) ? 'selected="selected"' : '' ) . '>' . __( ucwords( $acs_schema_types_field_value ), ACS::PREFIX ) . '</option>';
	}
	$return .= '</select>';

	return $return;
}

/**
 * Draw filter sort fields select
 *
 * @param string $selected
 * @param bool $is_frontend
 *
 * @return string
 */
function acs_draw_filter_sort_fields( $selected, $is_frontend = true ) {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Get custom field parameters
	$acs_sortable_fields = ( ! empty( $settings->acs_schema_fields_sortable ) ) ? array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_sortable ) ) ) : array();

	if ( ! $is_frontend ) $return = '<select id="acs_filter_sort_field" name="acs_filter_sort_field">';
	else $return = '<select id="acs_filter_sort_field" name="acs_filter_sort_field" class="acs_filters">';

	// Loop default sortable fields
	foreach ( ACS::$SORT_FIELDS as $sort_field_key => $sort_field_value ) {
		$return .= '<option value="' . $sort_field_key . '" ' . ( ( $sort_field_key == $selected) ? 'selected="selected"' : '' ) . '>' . __( $sort_field_value, ACS::PREFIX ) . '</option>';
	}

	// Loop custom sortable fields
	foreach ( $acs_sortable_fields as $acs_sortable_field ) {
		$return .= '<option value="' . $acs_sortable_field . '" ' . ( ( $acs_sortable_field == $selected) ? 'selected="selected"' : '' ) . '>' . $acs_sortable_field . ' (' . strtolower( __( 'Custom field', ACS::PREFIX ) ) . ')' . '</option>';
	}

	$return .= '</select>';

	return $return;
}

/**
 * Draw filter sort orders select
 *
 * @param string $selected
 * @param bool $is_frontend
 *
 * @return string
 */
function acs_draw_filter_sort_orders( $selected, $is_frontend = true ) {
	if ( ! $is_frontend ) $return = '<select id="acs_filter_sort_order" name="acs_filter_sort_order">';
	else $return = '<select id="acs_filter_sort_order" name="acs_filter_sort_order" class="acs_filters">';

	// Loop sort orders
	foreach ( ACS::$SORT_ORDERS as $sort_order_key => $sort_order_value ) {
		$return .= '<option value="' . $sort_order_key . '" ' . ( ( $sort_order_key == $selected) ? 'selected="selected"' : '' ) . '>' . __( $sort_order_value, ACS::PREFIX ) . '</option>';
	}
	$return .= '</select>';

	return $return;
}

/**
 * Get document key
 *
 * @param int $post_id
 * @param string $suffix
 *
 * @return string
 */
function acs_get_document_key( $post_id, $suffix = '' ) {
    // For example:
    // posts: 1_1_1
	// terms: 1_1_1_term
	return acs_get_site_id() .
           ACS::KEY_SEPARATOR_DEFAULT .
           acs_get_blog_id() .
           ACS::KEY_SEPARATOR_DEFAULT .
           $post_id .
           ( ( ! empty( $suffix ) ) ? ACS::KEY_SEPARATOR_DEFAULT . $suffix : '' );
}

/**
 * Get filter query to filter by site_id/blog_id and custom filters
 *
 * @param bool $global_search
 * @param string $type_field
 * @param string $filter_query
 * @param bool $filter_terms
 *
 * @return string
 */
function acs_get_filter_query( $global_search = false, $type_field = ACS::TYPE_FIELD_DEFAULT, $filter_query = '', $filter_terms = true ) {
    // Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Prepare default filter query prefix adding schema types
	$filter_query = $filter_query . ' (or ';
	foreach ( explode( ',', $settings->acs_schema_types ) as $type ) {
		$filter_query .= ' post_type:\'' . $type . '\'';
	}

	// Get all taxonomies (by default, we need to remove all from index)
	$site_taxonomy_types = acs_get_all_site_taxonomies();

	// Get schema searchable taxonomies
	$acs_schema_searchable_taxonomies = $settings->acs_schema_searchable_taxonomies;
	$acs_schema_searchable_taxonomies = $acs_schema_searchable_taxonomies ? explode( ACS::SEPARATOR, $acs_schema_searchable_taxonomies ) : array();

	// Get legacy post types
    if ( isset( $settings->acs_schema_fields_legacy_types ) && ! empty( $settings->acs_schema_fields_legacy_types ) ) {
	    $acs_schema_fields_legacy_types = $settings->acs_schema_fields_legacy_types;
	    $acs_schema_fields_legacy_types = $acs_schema_fields_legacy_types ? explode( ACS::SEPARATOR, $acs_schema_fields_legacy_types ) : array();

	    // Prepare default filter query prefix adding legacy post types (if provided)
	    foreach ( $acs_schema_fields_legacy_types as $legacy_type ) {
		    $filter_query .= ' post_type:\'' . $legacy_type . '\'';
	    }
    }

	// Loop site taxonomies
    if ( ! empty( $site_taxonomy_types ) ) {
	    // Get schema taxonomies
	    $acs_schema_taxonomies = $settings->acs_schema_taxonomies;
	    $acs_schema_taxonomies = $acs_schema_taxonomies ? explode( ACS::SEPARATOR, $acs_schema_taxonomies ) : array();

	    foreach ( $site_taxonomy_types as $site_taxonomy_type ) {
		    // Check if terms is synced and searchable, then add it to search
		    if ( in_array( $site_taxonomy_type, $acs_schema_taxonomies ) && in_array( $site_taxonomy_type, $acs_schema_searchable_taxonomies ) ) {
			    $filter_query = $filter_query . ' post_type:\'' . $site_taxonomy_type . '\'';
		    }
	    }
    }

	// Manage default term "category"
	if ( in_array( 'category', $acs_schema_searchable_taxonomies ) ) {
		$filter_query = $filter_query . ' post_type:\'category\'';
	}

	// Retrieve filter query extra conditions
	$extra_conditions = apply_filters( 'acs_add_filter_query_conditions', '', null );
    $extra_conditions = ( ! empty( $extra_conditions ) ? ( ' ' . $extra_conditions ) : '' );

	// Prepare default filter query suffix
	$filter_query = $filter_query . ')';

    if ( $type_field != 'all' ) {
        // Query field type items
        if ( $global_search ) {
            // Do not filter site and blog
            return '(and (not site_id:-1) (not blog_id:-1) post_type:\'' . $type_field . '\' ' . ( ( ! empty( $filter_query ) ) ? $filter_query : '' ) . $extra_conditions . ')';
        }
        else {
            // Filter site and blog
	        return '(and site_id:' . acs_get_site_id() . ' blog_id:' . acs_get_blog_id() . ' post_type:\'' . $type_field . '\' ' . ( ( ! empty( $filter_query ) ) ? $filter_query : '' ) . $extra_conditions . ')';
        }
    }
    else {
        // Query all items
        if ( $global_search ) {
            // Do not filter site and blog
	        return '(and (not site_id:-1) (not blog_id:-1) ' . ( ( ! empty( $filter_query ) ) ? $filter_query : '' ) . $extra_conditions . ')';
        }
        else {
            // Filter site and blog
	        return '(and site_id:' . acs_get_site_id() . ' blog_id:' . acs_get_blog_id() . ' ' . ( ( ! empty( $filter_query ) ) ? $filter_query : '' ) . $extra_conditions . ')';
        }
    }
}

/**
 * Verify actions nonce
 *
 * @param string $service
 *
 * @return mixed|null
 */
function acs_verify_nonce( $service = 'acs_actions_nonce' ) {
	return wp_verify_nonce( $_REQUEST[ 'nonce' ], $service );
}

/**
 * Read facets query string parameters
 *
 * @return array|null
 */
function acs_read_facet_parameters() {
	$facets = null;
	foreach ( $_GET as $key => $value ) {
		if ( strpos( $key, 'facet.' ) === 0 || strpos( $key, 'facet_' ) === 0 ) {
			// Value starts with "facet." or "facet_"
			$facet_field = str_replace( 'facet.', '', str_replace( 'facet_', '', $key ) );

			// Decode JSON data
			$decoded_value = json_decode( html_entity_decode( stripslashes( $value ) ), true );

			if ( ! empty( $decoded_value ) && is_array( $decoded_value ) ) {
				// Use provided (by user) facet data array
				$facet_value = $decoded_value;
			}
			else {
				// If value object is empty, facet are computed for all field values, sorted by count, and the top 10 are returned in the results
				$facet_value = array (
					'sort' => ACS::FACET_DEFAULT_METHOD,
					'size' => ACS::FACET_DEFAULT_COUNT
				);
			}

			// Add facet
			$facets[ $facet_field ] = $facet_value;
		}
	}

	//TODO: if no facets provided, returns a default set composed by all "facet-enabled" fields
	return $facets;
}

/**
 * Display an optional post thumbnail
 *
 * @param string $default_size
 */
function acs_post_thumbnail( $default_size = 'post-thumbnail' ) {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();

    if ( $settings->acs_results_show_fields_image ) {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        // Use a custom image size or default one
        if ( ! empty( $settings->acs_results_format_image ) ) {
            // Get featured image
            $image = wp_get_attachment_image_src( get_post_thumbnail_id(), $settings->acs_results_format_image );

	        $image_src = $image[0];
	        $image_width = $image[1];
	        $image_height = $image[2];
	        $image_alt = $image[3];
        }
        else {
	        // Get featured image
	        $image = wp_get_attachment_image_src( get_post_thumbnail_id(), $default_size );

	        $image_src = $image[0];
	        $image_width = '';
	        $image_height = '';
	        $image_alt = $image[3];
        }

	    if ( ! empty( $image_src ) ) {
		    ?>
		    <a class="post-thumbnail" href="<?php the_permalink() ?>">
			    <img width="<?php echo $image_width ?>" height="<?php echo $image_height ?>" src="<?php echo $image_src ?>" class="attachment-post-thumbnail wp-post-image" alt="<?php echo $image_alt ?>">
		    </a>
		    <?php
	    }
    }
}

/**
 * Prints HTML with meta information for the categories, tags, formats, etc
 */
function acs_post_meta() {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Show sticky flag
	if ( is_sticky() && $settings->acs_results_show_fields_sticky ) {
		printf( '<span class="sticky-post">%s</span> ', __( 'Featured', ACS::PREFIX ) );
	}

	// Show post formats
	if ( $settings->acs_results_show_fields_formats ) {
		$format = get_post_format();
		if ( current_theme_supports( 'post-formats', $format ) ) {
			printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span> ',
				sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', ACS::PREFIX ) ),
				esc_url( get_post_format_link( $format ) ),
				get_post_format_string( $format )
			);
		}
	}

	// Show post date
	if ( $settings->acs_results_show_fields_date ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		// Use a custom date format or default one
		$date_format = ( ! empty ( $settings->acs_results_format_date ) ) ? $settings->acs_results_format_date : get_option( 'date_format' );

		$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				get_the_date( $date_format ),
				esc_attr( get_the_modified_date( 'c' ) ),
				get_the_modified_date( $date_format )
		);

		printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span> ',
				_x( 'Posted on', 'Used before publish date.', ACS::PREFIX ),
				esc_url( get_permalink() ),
				$time_string
		);
	}

    // Show post author
    if ( $settings->acs_results_show_fields_author ) {
        // Use a custom author format or default one
        switch ( $settings->acs_results_format_author ) {
            case 'display_name_with_link':
                $author_name = get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) );
                $author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
                break;
            case 'display_name_without_link':
                $author_name = get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) );
                $author_url = '';
                break;
            case 'full_name_with_link':
                $author_name = get_the_author_meta( 'first_name', get_the_author_meta( 'ID' ) ) . ' ' . get_the_author_meta( 'last_name', get_the_author_meta( 'ID' ) );
                $author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
                break;
            case 'full_name_without_link':
                $author_name = get_the_author_meta( 'first_name', get_the_author_meta( 'ID' ) ) . ' ' . get_the_author_meta( 'last_name', get_the_author_meta( 'ID' ) );
                $author_url = '';
                break;
            case 'username_with_link':
                $author_name = get_the_author_meta( 'user_login', get_the_author_meta( 'ID' ) );
                $author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
                break;
            case 'username_without_link':
                $author_name = get_the_author_meta( 'user_login', get_the_author_meta( 'ID' ) );
                $author_url = '';
                break;
            default:
                $author_name = get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) );
                $author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
                break;
        }

        printf( '<span class="byline"><span class="author vcard"><span class="screen-reader-text">%1$s </span><a class="url fn n" href="%2$s">%3$s</a></span></span> ',
            _x( 'Author', 'Used before post author name.', ACS::PREFIX ),
            ( ! empty( $author_url ) ) ? esc_url( $author_url ) : 'javascript:void(0)',
            $author_name
        );
    }

    if ( 'post' == get_post_type() ) {
		// Show post categories
		if ( $settings->acs_results_show_fields_categories ) {
			$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', ACS::PREFIX ) );
			if ( $categories_list ) {
				printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span> ',
					_x( 'Categories', 'Used before category names.', ACS::PREFIX ),
					$categories_list
				);
			}
		}

		// Show post tags
		if ( $settings->acs_results_show_fields_tags ) {
			$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', ACS::PREFIX ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span> ',
					_x( 'Tags', 'Used before tag names.', ACS::PREFIX ),
					$tags_list
				);
			}
		}
	}

	// Show post comments link
	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) && $settings->acs_results_show_fields_comments ) {
		echo '<span class="comments-link">';
		/* translators: %s: post title */
		comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', ACS::PREFIX ), get_the_title() ) );
		echo '</span>';
	}
}

/**
 * Decode WordPress locale to obtain the Analysis schema language
 *
 * @return string
 */
function acs_decode_schema_language() {
    // Set schema from constants, if available
	$schema_language = ( defined( 'WP_ACS_SCHEMA_LANGUAGE' ) ) ? WP_ACS_SCHEMA_LANGUAGE : '';

    if ( empty( $schema_language ) ) {
	    // Get WordPress locale
	    $wordpress_locale = get_locale();

	    // Get language code
        $code = substr( $wordpress_locale, 0, 2 );

        // Compose schema language
        $schema_language = '_' . $code . '_default_';

        if ( ! in_array( $schema_language, array( '_ar_default_', '_hy_default_', '_eu_default_', '_bg_default_', '_ca_default_', '_cs_default_',
            '_da_default_', '_nl_default_', '_en_default_',  '_fi_default_', '_fr_default_', '_gl_default_', '_de_default_', '_el_default_',
            '_he_default_', '_hi_default_', '_hu_default_', '_id_default_', '_ga_default_', '_it_default_', '_ja_default_', '_ko_default_',
            '_lv_default_', '_no_default_', '_fa_default_', '_pt_default_', '_ro_default_', '_ru_default_', '_es_default_', '_sv_default_',
            '_th_default_', '_tr_default_' ) ) ) {
            // Use default
	        $schema_language = ACS::ANALYSIS_SCHEMA;
        }
    }

    return $schema_language;
}