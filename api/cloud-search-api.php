<?php
/**
 * Includes
 */
require_once( 'cloud-search-api-search.php' );
require_once( 'cloud-search-api-status.php' );

/**
 * Return JSON or XML response
 *
 * @param stdClass $response
 * @param string $format
 *
 * @return string
 */
function acs_format_response( $response, $format ) {
	// Read response data
	$items = $response->items;
	$found = $response->found;
	$facets = $response->facets;
	$requires_index_documents = $response->requires_index_documents;
	$processing = $response->processing;
	$errors = $response->errors;

	if ( strtolower( $format )  == 'xml' ) {
		// XML response
		header( 'Content-type: text/xml; charset=utf-8' );

		$return = '<?xml version="1.0" encoding="utf-8"?>';
		$return .= '<response>';
		$return .= '<data>';

		if ( is_array( $items ) ) {
			// Loop array of results
			foreach ( $items as $item_value ) {
				if ( is_array( $item_value ) ) {
					$return .= '<value>';
					foreach ( $item_value as $item_value_key => $item_value_value ) {
						$return .= '<' . $item_value_key . '>';
						foreach ( $item_value_value as $item_value_value_obj ) {
							$return .= '<value>' . $item_value_value_obj . '</value>';
						}
						$return .= '</' . $item_value_key . '>';
					}
					$return .= '</value>';
				}
				else {
					$return .= '<value>' . $item_value . '</value>';
				}
			}
		}
		else {
			// Manage single values (used for internal services)
			if ( isset( $found ) ) $return .= '<found>' . $found . '</found>';
			if ( isset( $requires_index_documents ) ) $return .= '<requires_index_documents>' . $requires_index_documents . '</requires_index_documents>';
			if ( isset( $processing ) ) $return .= '<processing>' . $processing . '</processing>';
			if ( isset( $errors ) ) $return .= '<errors>' . $errors . '</errors>';
		}

		$return .= '</data>';

		if ( is_array( $facets ) ) {
			$return .= '<facets>';
			// Loop array of facets
			foreach ( $facets as $facet_buckets_key => $facet_buckets_value ) {
				if ( is_array( $facet_buckets_value[ 'buckets' ] ) ) {
					$return .= '<facet name="' . $facet_buckets_key . '">';
					foreach ( $facet_buckets_value[ 'buckets' ] as $facet_key => $facet_value ) {
						$return .= '<bucket value="' . $facet_value[ 'value' ] . '" count="' . $facet_value[ 'count' ] . '" />';
					}
					$return .= '</facet>';
				}
			}
			$return .= '</facets>';
		}

		$return .= '</response>';
	}
	else {
		// JSON response
		header( 'Content-Type: application/json' );
		if ( is_array( $items ) ) {
			// Encode items
			if ( ! empty( $facets ) ) {
				$return = json_encode( array(
					'items' => $items,
					'facets' => $facets
				) );
			}
			else $return = json_encode( $items );
		}
		else {
			// Manage single values (used for internal services)
			$return = json_encode( $response );
		}
	}

	return $return;
}