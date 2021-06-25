<?php
/**
 * Hook for managing post transitions
 *
 * @param string $new_status
 * @param string $old_status
 * @param \WP_Post $post
 */
function acs_manage_post_transition( $new_status, $old_status, $post ) {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	$acs_schema_types = $settings->acs_schema_types;
	$acs_schema_types = $acs_schema_types ? explode( ACS::SEPARATOR, $acs_schema_types ) : array();

	// Check if current post type needs to be sync (check in valid schema types)
	if ( acs_check_mandatory_configuration() && in_array( $post->post_type, $acs_schema_types ) ) {
		// Read post exclusion field
		$excluded = get_post_meta( $post->ID, ACS::EXCLUDE_FIELD, true );

		// Retrieve allowed post statutes to manage
		$allowed_statuses = apply_filters( 'acs_post_transition_allowed_statuses', array( 'publish' ), $post );

		if ( in_array( $new_status, $allowed_statuses ) && ( ! isset( $excluded ) || empty( $excluded ) || $excluded != 1 ) ) {
			// If post status is "allowed" and is not excluded, add or update it to index
			try {
                acs_index_document( $post, true );
			}
			catch ( Exception $e ) {
				error_log( __( 'Error managing post transitions for published post', ACS::PREFIX ) . ': ' . $e->getMessage() );
			}
		}
		else {
			// If post isn't published or is excluded, delete it from index
			try {
				acs_delete_document( $post );
			}
			catch ( Exception $e ) {
				error_log( __( 'Error managing post transitions for unpublished post', ACS::PREFIX ) . ': ' . $e->getMessage() );
			}
		}
	}
}
add_action( 'transition_post_status', 'acs_manage_post_transition', 99, 3 );

/**
 * Hook for managing save term transitions
 *
 * @param int $term_id
 */
function acs_manage_terms_save_transitions( $term_id ) {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Retrieve current category
	$term = get_term( $term_id );
	$taxonomy = $term->taxonomy;

	$acs_schema_taxonomies = $settings->acs_schema_taxonomies;
	$acs_schema_taxonomies = $acs_schema_taxonomies ? array_merge( array( 'category' ), explode( ACS::SEPARATOR, $acs_schema_taxonomies ) ) : array( 'category' );

	// Check if current term type needs to be sync (check in valid schema terms types)
	if ( acs_check_mandatory_configuration() && in_array( $taxonomy, $acs_schema_taxonomies ) ) {
		// Read post exclusion field
		$excluded = get_term_meta( $term_id, ACS::EXCLUDE_FIELD, true );

		if ( ! isset( $excluded ) || empty( $excluded ) || $excluded != 1 ) {
			// Add or update term to index
			try {
				acs_index_document( $term, true );
			}
			catch ( Exception $e ) {
				error_log( __( 'Error managing save transitions for term', ACS::PREFIX ) . ': ' . $e->getMessage() );
			}
		}
	}
}
add_action( 'edit_terms', 'acs_manage_terms_save_transitions', 99 );
add_action( 'create_terms', 'acs_manage_terms_save_transitions', 99 );

/**
 * Hook for managing delete term transitions
 *
 * @param int $term_id
 */
function acs_manage_terms_delete_transitions( $term_id ) {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Retrieve current category
	$term = get_term( $term_id );
	$taxonomy = $term->taxonomy;

	//TODO: manage valid taxonomies if I want to manage not only native "category" ($settings->acs_schema_terms_types)
	$acs_schema_terms_types = $settings->acs_schema_terms_types;
	$acs_schema_terms_types = $acs_schema_terms_types ? explode( ACS::SEPARATOR, $acs_schema_terms_types ) : array();

	// Check if current term type needs to be sync (check in valid schema terms types)
	if ( acs_check_mandatory_configuration() && ( 1==1 || in_array( $taxonomy, $acs_schema_terms_types ) ) ) {
		// Delete term from index
		try {
			acs_delete_document( $term );
		}
		catch ( Exception $e ) {
			error_log( __( 'Error managing delete transitions for term', ACS::PREFIX ) . ': ' . $e->getMessage() );
		}

	}
}
add_action( 'delete_category', 'acs_manage_terms_delete_transitions', 99 );

/**
 * Use ACS plugin search template
 *
 * @param string $template
 *
 * @return string
 */
function acs_plugin_search_template( $template ) {
	if ( is_search() ) {
		// Get settings option
		$settings = ACS::get_instance()->get_settings();

		// Get search template
		if ( $settings->acs_frontpage_use_plugin_search_page ) {
			// Look for an overridden template named 'cloud-search-template.php' in current theme
			$overridden_search_template = locate_template( 'templates/cloud-search-template.php' );
			if ( !empty( $overridden_search_template ) ) {
				// Founded an overridden template, use it
				$template = $overridden_search_template;
			}
			else {
				// Not founded an overridden template, use default
				$template = dirname( __FILE__ ) . '/templates/cloud-search-template-default.php';
			}
		}
	}

	return $template;
}
add_filter( 'template_include', 'acs_plugin_search_template' );

/**
 * Disable default automatic search query (only in frontend)
 *
 * @param string $sql
 * @param \WP_Query $query
 *
 * @return bool
 */
function acs_plugin_disable_search_wp_query( $sql, $query ) {
	if ( !is_admin() && $query->is_search() ) {
		// Prevent SELECT FOUND_ROWS() query
		$query->query_vars[ 'no_found_rows' ] = true;

		// Prevent post term and meta cache update queries
		$query->query_vars[ 'cache_results' ] = false;

		return false;
	}

	return $sql;
}
add_filter( 'posts_request', 'acs_plugin_disable_search_wp_query', 10, 2 );

/**
 * Manage the_posts filter
 *
 * @param array $posts
 * @param string $query
 *
 * @return array
 */
function acs_plugin_manage_the_posts( $posts, $query ) {
	if ( is_null( $posts ) ) {
		$posts = array();
		return $posts;
	}

	return $posts;
}
add_filter( 'the_posts', 'acs_plugin_manage_the_posts', 9, 2 );

/**
 * Register API routes
 */
function acs_register_routes_hooks() {
	// Define routes
  	$routes = array( 
    	'suggest' => 'acs_route_get_suggestions',
    	'results' => 'acs_search_documents'
  	);

	// Loop routes
	foreach ( $routes as $route_base => $route_callback ) {
		// Registering route
		register_rest_route( ACS::API_NAMESPACE . '/v' . ACS::API_VERSION, '/' . $route_base, array(
			array(
				'methods' => WP_REST_Server::READABLE,
				'callback' => $route_callback,
				'permission_callback'=>'__return_true'
			)
		) );
	}
}