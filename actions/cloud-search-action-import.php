<?php
/**
 * Process a settings export that generates a JSON file of the plugin settings
 */
function acs_settings_export() {
	// Security checks
	if ( empty( $_POST[ ACS::ACTION_SETTINGS ] ) || ACS::ACTION_EXPORT != $_POST[ ACS::ACTION_SETTINGS ] ) return;
	if ( ! wp_verify_nonce( $_POST[ ACS::NONCE_SETTINGS_EXPORT ], ACS::NONCE_SETTINGS_EXPORT ) ) return;
	if ( ! current_user_can( 'manage_options' ) ) return;

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	ignore_user_abort( true );
	nocache_headers();
	header( 'Content-Type: application/json; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=cloud-search-settings-export-' . date( 'm-d-Y' ) . '.json' );
	header( "Expires: 0" );
	echo json_encode( $settings );

	exit;
}
add_action( 'admin_init', 'acs_settings_export' );

/**
 * Process a settings import JSON a json file
 */
function acs_settings_import() {
	// Security checks
	if ( empty( $_POST[ ACS::ACTION_SETTINGS ] ) || ACS::ACTION_IMPORT != $_POST[ ACS::ACTION_SETTINGS ] ) return;
	if ( ! wp_verify_nonce( $_POST[ ACS::NONCE_SETTINGS_IMPORT ], ACS::NONCE_SETTINGS_IMPORT ) ) return;
	if ( ! current_user_can( 'manage_options' ) ) return;

	// Check file extension
	$extension = end( explode( '.', $_FILES[ 'import_file' ][ 'name' ] ) );
	if ( $extension != 'json' ) {
		wp_die( __( 'Please upload a valid .json file' ) );
	}

	// Check file validity
	$import_file = $_FILES[ 'import_file' ][ 'tmp_name' ];
	if ( empty( $import_file ) ) {
		wp_die( __( 'Please upload a file to import' ) );
	}

	// Retrieve the settings from the file and convert the json object to an array
	$new_settings = (array) json_decode( file_get_contents( $import_file ) );

	// Save settings action
	$settings = new stdClass();

	// Read post data
	$settings->acs_aws_access_key_id = ( !empty( $new_settings[ 'acs_aws_access_key_id' ] ) ) ? wp_kses_post( $new_settings[ 'acs_aws_access_key_id' ] ) : '';
	$settings->acs_aws_secret_access_key = ( !empty( $new_settings[ 'acs_aws_secret_access_key' ] ) ) ? wp_kses_post( $new_settings[ 'acs_aws_secret_access_key' ] ) : '';
	$settings->acs_aws_region = ( !empty( $new_settings[ 'acs_aws_region' ] ) ) ? wp_kses_post( $new_settings[ 'acs_aws_region' ] ) : '';
	$settings->acs_search_endpoint = ( !empty( $new_settings[ 'acs_search_endpoint' ] ) ) ? wp_kses_post( $new_settings[ 'acs_search_endpoint' ] ) : '';
	$settings->acs_search_domain_name = ( !empty( $new_settings[ 'acs_search_domain_name' ] ) ) ? wp_kses_post( $new_settings[ 'acs_search_domain_name' ] ) : '';
	$settings->acs_frontpage_content_box_type = ( !empty( $new_settings[ 'acs_frontpage_content_box_type' ] ) ) ? wp_kses_post( $new_settings[ 'acs_frontpage_content_box_type' ] ) : 'default';
	$settings->acs_frontpage_content_box_value = ( !empty( $new_settings[ 'acs_frontpage_content_box_value' ] ) ) ? wp_kses_post( $new_settings[ 'acs_frontpage_content_box_value' ] ) : '';
	$settings->acs_frontpage_use_plugin_search_page = ( !empty( $new_settings[ 'acs_frontpage_use_plugin_search_page' ] ) ) ? 1 : 0;
	$settings->acs_frontpage_use_jquery = ( !empty( $new_settings[ 'acs_frontpage_use_jquery' ] ) ) ? 1 : 0;
	$settings->acs_frontpage_show_filters = ( !empty( $new_settings[ 'acs_frontpage_show_filters' ] ) ) ? 1 : 0;
	$settings->acs_frontpage_custom_css = ( !empty( $new_settings[ 'acs_frontpage_custom_css' ] ) ) ? wp_kses_post( $new_settings[ 'acs_frontpage_custom_css' ] ) : '';
	$settings->acs_results_show_fields_sticky = ( !empty( $new_settings[ 'acs_results_show_fields_sticky' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_formats = ( !empty( $new_settings[ 'acs_results_show_fields_formats' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_categories = ( !empty( $new_settings[ 'acs_results_show_fields_categories' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_tags = ( !empty( $new_settings[ 'acs_results_show_fields_tags' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_comments = ( !empty( $new_settings[ 'acs_results_show_fields_comments' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_content = ( !empty( $new_settings[ 'acs_results_show_fields_content' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_excerpt = ( !empty( $new_settings[ 'acs_results_show_fields_excerpt' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_custom = ( !empty( $new_settings[ 'acs_results_show_fields_custom' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_terms_content = ( !empty( $new_settings[ 'acs_results_show_fields_terms_content' ] ) ) ? 1 : 0;
	$settings->acs_results_show_fields_terms_custom = ( !empty( $new_settings[ 'acs_results_show_fields_terms_custom' ] ) ) ? 1 : 0;
	$settings->acs_results_custom_field = ( !empty( $new_settings[ 'acs_results_custom_field' ] ) ) ? wp_kses_post( $new_settings[ 'acs_results_custom_field' ] ) : '';
	$settings->acs_results_show_fields_image = ( !empty( $new_settings[ 'acs_results_show_fields_image' ] ) ) ? 1 : 0;
	$settings->acs_results_format_image = ( !empty( $new_settings[ 'acs_results_format_image' ] ) ) ? wp_kses_post( $new_settings[ 'acs_results_format_image' ] ) : '';
	$settings->acs_results_show_fields_date = ( !empty( $new_settings[ 'acs_results_show_fields_date' ] ) ) ? 1 : 0;
	$settings->acs_results_format_date = ( !empty( $new_settings[ 'acs_results_format_date' ] ) ) ? wp_kses_post( $new_settings[ 'acs_results_format_date' ] ) : '';
	$settings->acs_results_show_fields_author = ( !empty( $new_settings[ 'acs_results_show_fields_author' ] ) ) ? 1 : 0;
	$settings->acs_results_format_author = ( !empty( $new_settings[ 'acs_results_format_author' ] ) ) ? wp_kses_post( $new_settings[ 'acs_results_format_author' ] ) : '';
	$settings->acs_results_no_results_msg = ( !empty( $new_settings[ 'acs_results_no_results_msg' ] ) ) ? stripslashes( wp_kses_post( $new_settings[ 'acs_results_no_results_msg' ] ) ) : __( 'No results', ACS::PREFIX );
	$settings->acs_results_no_results_box_value = ( !empty( $new_settings[ 'acs_results_no_results_box_value' ] ) ) ? wp_kses_post( $new_settings[ 'acs_results_no_results_box_value' ] ) : '';
	$settings->acs_results_load_more_msg = ( !empty( $new_settings[ 'acs_results_load_more_msg' ] ) ) ? stripslashes( wp_kses_post( $new_settings[ 'acs_results_load_more_msg' ] ) ) : __( 'Load more', ACS::PREFIX );
	$settings->acs_results_max_items = ( !empty( $new_settings[ 'acs_results_max_items' ] ) ) ? intval( wp_kses_post( $new_settings[ 'acs_results_max_items' ] ) ) : ACS::SEARCH_RETURN_FULL_ITEMS;
	$settings->acs_results_field_weights = ( !empty( $new_settings[ 'acs_results_field_weights' ] ) ) ? stripslashes( wp_kses_post( $new_settings[ 'acs_results_field_weights' ] ) ) : '';
	$settings->acs_filter_sort_field = ( !empty( $new_settings[ 'acs_filter_sort_field' ] ) ) ? wp_kses_post( $new_settings[ 'acs_filter_sort_field' ] ) : ACS::SORT_FIELD_DEFAULT;
	$settings->acs_filter_sort_order = ( !empty( $new_settings[ 'acs_filter_sort_order' ] ) ) ? wp_kses_post( $new_settings[ 'acs_filter_sort_order' ] ) : ACS::SORT_ORDER_DEFAULT;
	$settings->acs_filter_text_length = ( !empty( $new_settings[ 'acs_filter_text_length' ] ) ) ? intval( wp_kses_post( $new_settings[ 'acs_filter_text_length' ] ) ) : ACS::SEARCH_TEXT_LENGTH;
	$settings->acs_filter_text_length_type = ( !empty( $new_settings[ 'acs_filter_text_length_type' ] ) ) ? wp_kses_post( $new_settings[ 'acs_filter_text_length_type' ] ) : ACS::SEARCH_TEXT_LENGTH_TYPE;
	$settings->acs_schema_fields_int = ( !empty( $new_settings[ 'acs_schema_fields_int' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_int' ] ) : '';
	$settings->acs_schema_fields_double = ( !empty( $new_settings[ 'acs_schema_fields_double' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_double' ] ) : '';
	$settings->acs_schema_fields_date = ( !empty( $new_settings[ 'acs_schema_fields_date' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_date' ] ) : '';
	$settings->acs_schema_fields_literal = ( !empty( $new_settings[ 'acs_schema_fields_literal' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_literal' ] ) : '';
	$settings->acs_schema_fields_sortable = ( !empty($new_settings[ 'acs_schema_fields_sortable' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_sortable' ] ) : '';
	$settings->acs_schema_fields_prefix = ( !empty( $new_settings[ 'acs_schema_fields_prefix' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_prefix' ] ) : '';
	$settings->acs_schema_fields_separator = ( !empty( $new_settings[ 'acs_schema_fields_separator' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_separator' ] ) : ACS::FIELD_SEPARATOR_DEFAULT;
	$settings->acs_schema_fields_image_size = ( !empty( $new_settings[ 'acs_schema_fields_image_size' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_image_size' ] ) : '';
	$settings->acs_schema_fields_custom_image_id = ( !empty( $new_settings[ 'acs_schema_fields_custom_image_id' ] ) ) ? wp_kses_post( $new_settings[ 'acs_schema_fields_custom_image_id' ] ) : '';
	$settings->acs_schema_fields_invalid_chars = ( !empty( $new_settings[ 'acs_schema_fields_invalid_chars' ] ) ) ? stripslashes( $new_settings[ 'acs_schema_fields_invalid_chars' ] ) : '';
	$settings->acs_schema_fields_legacy_types = ( !empty( $new_settings[ 'acs_schema_fields_legacy_types' ] ) ) ? stripslashes( $new_settings[ 'acs_schema_fields_legacy_types' ] ) : '';
	$settings->acs_schema_prevent_deletion = ( !empty( $new_settings[ 'acs_schema_prevent_deletion' ] ) ) ? 1 : 0;
	$settings->acs_network_site_id = ( !empty( $new_settings[ 'acs_network_site_id' ] ) ) ? wp_kses_post( $new_settings[ 'acs_network_site_id' ] ) : '';
	$settings->acs_network_blog_id = ( !empty( $new_settings[ 'acs_network_blog_id' ] ) ) ? wp_kses_post( $new_settings[ 'acs_network_blog_id' ] ) : '';
	$settings->acs_highlight_type = ( !empty( $new_settings[ 'acs_highlight_type' ] ) ) ? wp_kses_post( $new_settings[ 'acs_highlight_type' ] ) : ACS::HIGHLIGHT_TYPE_DEFAULT;
	$settings->acs_highlight_titles = ( !empty( $new_settings[ 'acs_highlight_titles' ] ) ) ? 1 : 0;
	$settings->acs_highlight_color_text = ( !empty( $new_settings[ 'acs_highlight_color_text' ] ) ) ? wp_kses_post( $new_settings[ 'acs_highlight_color_text' ] ) : '';
	$settings->acs_highlight_color_background = ( !empty( $new_settings[ 'acs_highlight_color_background' ] ) ) ? wp_kses_post( $new_settings[ 'acs_highlight_color_background' ] ) : '';
	$settings->acs_highlight_style = ( !empty( $new_settings[ 'acs_highlight_style' ] ) ) ? stripslashes( wp_kses_post( $new_settings[ 'acs_highlight_style' ] ) ) : '';
	$settings->acs_highlight_class = ( !empty( $new_settings[ 'acs_highlight_class' ] ) ) ? stripslashes( wp_kses_post( $new_settings[ 'acs_highlight_class' ] ) ) : '';
	$settings->acs_suggest_active = ( !empty( $new_settings[ 'acs_suggest_active' ] ) ) ? 1 : 0;
	$settings->acs_suggest_only_title = ( !empty( $new_settings[ 'acs_suggest_only_title' ] ) ) ? 1 : 0;
	$settings->acs_suggest_selector = ( !empty( $new_settings[ 'acs_suggest_selector' ] ) ) ? stripslashes( wp_kses_post( $new_settings[ 'acs_suggest_selector' ] ) ) : ACS::SUGGEST_DEFAULT_SELECTOR;
	$settings->acs_suggest_trigger = ( !empty( $new_settings[ 'acs_suggest_trigger' ] ) ) ? intval( wp_kses_post( $new_settings[ 'acs_suggest_trigger' ] ) ) : ACS::SUGGEST_DEFAULT_TRIGGER;
	$settings->acs_suggest_results = ( !empty( $new_settings[ 'acs_suggest_results' ] ) ) ? intval( wp_kses_post( $new_settings[ 'acs_suggest_results' ] ) ) : ACS::SUGGEST_DEFAULT_RESULTS;
	$settings->acs_suggest_order = ( !empty( $new_settings[ 'acs_suggest_order' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_order' ] ) : ACS::SUGGEST_ORDER_TYPE_1;
	$settings->acs_suggest_click = ( !empty( $new_settings[ 'acs_suggest_click' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_click' ] ) : ACS::SUGGEST_CLICK_TYPE_1;
	$settings->acs_suggest_all_font_size = ( !empty( $new_settings[ 'acs_suggest_all_font_size' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_all_font_size' ] ) : ACS::SUGGEST_DEFAULT_ALL_FONT_SIZE;
	$settings->acs_suggest_all_color = ( !empty( $new_settings[ 'acs_suggest_all_color' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_all_color' ] ) : ACS::SUGGEST_DEFAULT_ALL_COLOR;
	$settings->acs_suggest_all_background = ( !empty( $new_settings[ 'acs_suggest_all_background' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_all_background' ] ) : ACS::SUGGEST_DEFAULT_ALL_BACKGROUND;
	$settings->acs_suggest_focused_font_size = ( !empty( $new_settings[ 'acs_suggest_focused_font_size' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_focused_font_size' ] ) : ACS::SUGGEST_DEFAULT_FOCUSED_FONT_SIZE;
	$settings->acs_suggest_focused_color = ( !empty( $new_settings[ 'acs_suggest_focused_color' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_focused_color' ] ) : ACS::SUGGEST_DEFAULT_FOCUSED_COLOR;
	$settings->acs_suggest_focused_background = ( !empty( $new_settings[ 'acs_suggest_focused_background' ] ) ) ? wp_kses_post( $new_settings[ 'acs_suggest_focused_background' ] ) : ACS::SUGGEST_DEFAULT_FOCUSED_BACKGROUND;
	$settings->acs_hide_section_help = ( !empty( $new_settings[ 'acs_hide_section_help' ] ) ) ? 1 : 0;
	$settings->acs_hide_section_docs = ( !empty( $new_settings[ 'acs_hide_section_docs' ] ) ) ? 1 : 0;
	$settings->acs_hide_section_import =  ( !empty( $new_settings[ 'acs_hide_section_import' ] ) ) ? 1 : 0;

	// Remove '.php' occurrences from boxes value
	$settings->acs_frontpage_content_box_value = str_replace( '.php', '', $settings->acs_frontpage_content_box_value );
	$settings->acs_results_no_results_box_value = str_replace( '.php', '', $settings->acs_results_no_results_box_value );
	$settings->acs_frontpage_content_box_value = str_replace( '.PHP', '', $settings->acs_frontpage_content_box_value );
	$settings->acs_results_no_results_box_value = str_replace( '.PHP', '', $settings->acs_results_no_results_box_value );
	$settings->acs_schema_types = ( !empty( $new_settings[ 'acs_schema_types' ] ) ) ? $new_settings[ 'acs_schema_types' ] : array();
	$settings->acs_schema_fields = ( !empty( $new_settings[ 'acs_schema_fields' ] ) ) ? $new_settings[ 'acs_schema_fields' ] : array();
	$settings->acs_schema_taxonomies = ( !empty( $new_settings[ 'acs_schema_taxonomies' ] ) ) ? $new_settings[ 'acs_schema_taxonomies' ] : array();
	$settings->acs_schema_searchable_taxonomies = ( !empty( $new_settings[ 'acs_schema_searchable_taxonomies' ] ) ) ? $new_settings[ 'acs_schema_searchable_taxonomies' ] : array();

	// Save option on database
	update_option( ACS::OPTION_SETTINGS, $settings );

	// Reload settings option to refresh settings data after POST
	wp_safe_redirect( admin_url( 'admin.php?page=' . ACS::MENU_IMPORT ) );

	exit;
}
add_action( 'admin_init', 'acs_settings_import' );