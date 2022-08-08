<?php
/**
 * Imports
 */

use \WP_Cloud_Search\Aws\CloudSearchDomain\CloudSearchDomainClient;
use \WP_Cloud_Search\Aws\CloudSearch\CloudSearchClient;

/**
 * Includes
 */
require_once( 'cloud-search-action-search.php' );
require_once( 'cloud-search-action-status.php' );

/**
 * Check if connection is right
 *
 * @return bool
 */
function acs_check_connection() {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Check schema mandatory configuration
	if ( ! acs_check_mandatory_configuration() ) return false;
	$check_connection = false;

	try {
		// Get domain client, if there is no exception, connection is ok
		if ( acs_check_basic_configuration() ) {
			/** @noinspection MissedFieldInspection */
			CloudSearchDomainClient::factory( array(
				'version' => '2013-01-01',
				'endpoint' => ( defined( 'WP_ACS_SEARCH_ENDPOINT' ) ) ? WP_ACS_SEARCH_ENDPOINT : $settings->acs_search_endpoint,
                'region' => ( defined( 'WP_ACS_REGION' ) ) ? WP_ACS_REGION : $settings->acs_aws_region,
                'credentials' => array(
                    'key' => ( defined( 'WP_ACS_ACCESS_KEY' ) ) ? WP_ACS_ACCESS_KEY : $settings->acs_aws_access_key_id,
                    'secret' => ( defined( 'WP_ACS_SECRET_KEY' ) ) ? WP_ACS_SECRET_KEY : $settings->acs_aws_secret_access_key,
                    'token' => ( defined( 'WP_ACS_SESSION_TOKEN' ) ) ? WP_ACS_SESSION_TOKEN : $settings->acs_aws_session_token
                )
			) );

			$check_connection = true;
		}
		else if ( acs_check_mandatory_configuration() ) {
			// Try to use IAM roles to connect to the client
			/** @noinspection MissedFieldInspection */
			$client = CloudSearchClient::factory( array(
				'version' => '2013-01-01',
				'region' => ( defined( 'WP_ACS_REGION' ) ) ? WP_ACS_REGION : $settings->acs_aws_region
			) );

			// Search configured domain
			$describe_domains_result = $client->describeDomains( array(
				'DomainNames' => array( ( defined ( 'WP_ACS_SEARCH_DOMAIN' ) ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name )
			));

			// Read result, if empty, connection is wrong
			if ( empty( $describe_domains_result ) ) {
				$check_connection = false;
			}
			else {
				$check_connection = true;
			}
		}
	}
	catch ( Exception $e ) {
		$check_connection = false;
	}

	return $check_connection;
}

/**
 * Create index fields
 *
 * @return ACS_Result
 */
function acs_index_create() {
    // Get client
    $client = acs_get_client();

    // Get index fields
    $index_field_names = acs_get_index_fields( true );
    $index_field_names_removed = array();

    // Get settings option
    $settings = ACS::get_instance()->get_settings();

	// Get custom field parameters
	$acs_int_fields = array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_int ) ) );
	$acs_double_fields = array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_double ) ) );
	$acs_date_fields = array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_date ) ) );
	$acs_literal_fields = array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_literal ) ) );
	$acs_sortable_fields = array_map( 'trim', str_getcsv( str_replace( '-', '_', $settings->acs_schema_fields_sortable ) ) );

	// Get index fields (without any filter)
	/** @noinspection MissedFieldInspection */
    $result_all = $client->describeIndexFields( array(
        'DomainName' => ( defined ( 'WP_ACS_SEARCH_DOMAIN') ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name,
        'Deployed' => true
    ) );
    $result_fields_all = $result_all->getPath( 'IndexFields' );

    // Find remote index field not present in local index schema
    foreach ( $result_fields_all as $result_field_all ) {
        $field_name = $result_field_all[ 'Options' ][ 'IndexFieldName' ];
        if ( ($key = array_search( $field_name, $index_field_names ) ) === false ) {
            // If field is already in the index, remove it from new fields array (only if prevent deletion is disable)
            if ($settings->acs_schema_prevent_deletion == 0) $index_field_names_removed[] = $field_name;
        }
    }

    // Get index fields (filtered with settings fields)
	/** @noinspection MissedFieldInspection */
    $result_filtered = $client->describeIndexFields( array(
        'DomainName' => ( defined ( 'WP_ACS_SEARCH_DOMAIN') ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name,
        'FieldNames' => $index_field_names,
        'Deployed' => true
    ) );
    $result_fields_filtered = $result_filtered->getPath( 'IndexFields' );

	// Get schema fields
	$acs_schema_fields = $settings->acs_schema_fields;
	$acs_schema_fields = $acs_schema_fields ? array_map( 'acs_str_validate_field_pattern', explode( ACS::SEPARATOR, $acs_schema_fields ) ) : array();

    // Find local index field not present in the remote index schema
    foreach ( $result_fields_filtered as $result_field_filtered ) {
	    // Read "IndexField" info
	    $field_type = $result_field_filtered[ 'Options' ][ 'IndexFieldType' ];
	    $field_name = $result_field_filtered[ 'Options' ][ 'IndexFieldName' ];

	    // Replace field slug "-" with "_" due to Amazon CloudSearch valid pattern rule
	    $field_name_cleaned = str_replace( '-', '_', acs_str_replace_first( ACS::CUSTOM_FIELD_PREFIX , '', $field_name ) );

	    // Read "SortEnabled" flag
	    if ( $field_type == 'int' ) $field_sort = $result_field_filtered[ 'Options' ][ 'IntOptions' ][ 'SortEnabled' ];
	    else if ( $field_type == 'double' ) $field_sort = $result_field_filtered[ 'Options' ][ 'DoubleOptions' ][ 'SortEnabled' ];
	    else if ( $field_type == 'date' ) $field_sort = $result_field_filtered[ 'Options' ][ 'DateOptions' ][ 'SortEnabled' ];
	    else if ( $field_type == 'literal' ) $field_sort = $result_field_filtered[ 'Options' ][ 'LiteralOptions' ][ 'SortEnabled' ];
	    else if ( isset( $result_field_filtered[ 'Options' ][ 'TextOptions' ] ) ) {
	    	$field_sort = $result_field_filtered[ 'Options' ][ 'TextOptions' ][ 'SortEnabled' ];
	    }
	    else $field_sort = 0;

	    // Check if some fields need an update
	    if ( ! empty( $acs_schema_fields ) && in_array( $field_name_cleaned, $acs_schema_fields ) ) {
		    if ( ! empty( $acs_int_fields ) && in_array( $field_name_cleaned, $acs_int_fields ) && $field_type != 'int' ) {
			    // Detect int field (need an update)
			    continue;
		    }
		    else if ( ! empty( $acs_double_fields ) && in_array( $field_name_cleaned, $acs_double_fields ) && $field_type != 'double' ) {
			    // Detect double field (need an update)
			    continue;
		    }
		    else if ( ! empty( $acs_date_fields ) && in_array( $field_name_cleaned, $acs_date_fields ) && $field_type != 'date' ) {
			    // Detect date field (need an update)
			    continue;
		    }
		    else if ( ! empty( $acs_literal_fields ) && in_array( $field_name_cleaned, $acs_literal_fields ) && $field_type != 'literal' ) {
			    // Detect literal field (need an update)
			    continue;
		    }
		    else if ( ! empty( $acs_sortable_fields ) && in_array( $field_name_cleaned, $acs_sortable_fields ) && $field_sort != 1 ) {
			    // Detect sortable field to be activated (need an update)
			    continue;
		    }
		    else if ( ! in_array( $field_name_cleaned, $acs_sortable_fields ) && $field_sort == 1 ) {
			    // Detect un-sortable field to be removed (need an update)
			    continue;
		    }
		    else if ( ! empty( $acs_int_fields ) && ! in_array( $field_name_cleaned, $acs_int_fields ) &&
		              ! empty( $acs_double_fields ) && ! in_array( $field_name_cleaned, $acs_double_fields ) &&
		              ! empty( $acs_date_fields ) && ! in_array( $field_name_cleaned, $acs_date_fields ) &&
		              ! empty( $acs_literal_fields ) && ! in_array( $field_name_cleaned, $acs_literal_fields ) &&
		              ! empty( $acs_sortable_fields ) && ! in_array( $field_name_cleaned, $acs_sortable_fields ) &&
		              ( ! isset( $result_field_filtered[ 'Options' ][ 'TextOptions' ] ) || empty( $result_field_filtered[ 'Options' ][ 'TextOptions' ] ) ) ) {
			    // Reset
			    continue;
		    }
	    }

	    if ( ($key = array_search( $field_name, $index_field_names ) ) !== false ) {
		    // If field is already in the index, remove it from new fields array
		    unset( $index_field_names[ $key ] );
	    }
    }

    // Define result variables
    $fields_managed = 0;
    $fields_with_error = array();

    // Add index fields in index schema
    $index_field_data = acs_get_index_fields();
    foreach ( $index_field_names as $index_field_name ) {
        $index_field_params = array(
            'IndexFieldName' => $index_field_name,
            'IndexFieldType' => $index_field_data[ $index_field_name ][ 'type' ],
        );
        $option_key = $index_field_data[ $index_field_name ][ 'option_key' ];
        if ( $option_key ) {
            $index_field_params[ $option_key ] = $index_field_data[ $index_field_name ][ 'option_value' ];
        }

        try {
            $client->defineIndexField( array(
	            'DomainName' => ( defined ( 'WP_ACS_SEARCH_DOMAIN') ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name,
                'IndexField' => $index_field_params
            ) );
            $fields_managed = $fields_managed + 1;
        }
        catch ( Exception $e ) {
            $fields_with_error[] = $index_field_name;
        }
    }

    // Remove index fields from index schema
    foreach ( $index_field_names_removed as $index_field_name ) {
        try {
            $client->deleteIndexField( array(
	            'DomainName' => ( defined ( 'WP_ACS_SEARCH_DOMAIN') ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name,
                'IndexFieldName' => $index_field_name
            ));
            $fields_managed = $fields_managed + 1;
        }
        catch ( Exception $e ) {
            $fields_with_error[] = $index_field_name;
        }
    }

    // Prepare result object
    $acs_result = new ACS_Result();
    $acs_result->set_data( array(
        'fields_managed' => $fields_managed,
        'fields_with_error' => $fields_with_error
    ) );

    // Return result object
    return $acs_result;
}

/**
 * Run indexing task over documents
 *
 * @return ACS_Result
 */
function acs_run_indexing() {
    // Get client
    $client = acs_get_client();

    // Get settings option
    $settings = ACS::get_instance()->get_settings();

    // Run indexing
    $result = $client->indexDocuments( array(
	    'DomainName' => ( defined ( 'WP_ACS_SEARCH_DOMAIN' ) ) ? WP_ACS_SEARCH_DOMAIN : $settings->acs_search_domain_name
    ) );

    // Prepare result object
    $acs_result = new ACS_Result();
    $acs_result->set_data( array(
        'fields_managed' => ( ! empty( $result->getPath( 'FieldNames' ) ) ) ? count( $result->getPath( 'FieldNames' ) ) : 0,
        'fields_with_error' => 0
    ) );

    // Return result object
    return $acs_result;
}