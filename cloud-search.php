<?php
/*
Plugin Name: CloudSearch
Description: CloudSearch is a flexible plugin that allows you to leverage the search index power of Amazon CloudSearch in your WordPress site.
Author: Andrea Landonio
Author URI: http://www.andrealandonio.it
Text Domain: cloud-search
Domain Path: /languages/
Version: 3.0.0
License: GPL v3

CloudSearch
Copyright (C) 2013-2023, Andrea Landonio - landonio.andrea@gmail.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Security check
if ( ! defined( 'ABSPATH' ) ) die( 'Direct access to files not allowed' );

require_once('vendor/scoper-autoload.php');

/**
 * Includes & loader
 */
require_once( 'cloud-search-autoloader.php' );
require_once( 'classes/cloud-search.php' );
require_once( 'classes/cloud-search-message.php' );
require_once( 'classes/cloud-search-result.php' );
require_once( 'actions/cloud-search-actions.php' );
require_once( 'admin/cloud-search-admin.php');
require_once( 'api/cloud-search-api.php' );
require_once( 'cloud-search-utils.php' );
require_once( 'cloud-search-hooks.php' );
require_once( 'cloud-search-schema.php' );
require_once( 'cloud-search-indexer.php' );
require_once( 'cloud-search-wp-cli.php' );

/**
 * Register activation hook
 */
function acs_activation() {
	if ( ! acs_check_user_capabilities() ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		die( __( 'You don\'t have the permissions to perform this operation.', ACS::PREFIX ) );
	}

	// Setting default options
	update_option( ACS::OPTION_STATUS, ACS::STATUS_NO_OPERATION );
	update_option( ACS::OPTION_ITEMS, 0 );

	// Add default plugin option
	if ( get_option( ACS::OPTION_SETTINGS ) === false ) {
		$settings = new stdClass();
		$settings->acs_frontpage_content_box_type = 'plugin';
		$settings->acs_frontpage_use_plugin_search_page = 1;
		$settings->acs_frontpage_use_jquery = 1;
		$settings->acs_frontpage_show_filters = 1;
		$settings->acs_results_show_fields_sticky = 1;
		$settings->acs_results_show_fields_formats = 1;
		$settings->acs_results_show_fields_categories = 1;
		$settings->acs_results_show_fields_tags = 1;
		$settings->acs_results_show_fields_comments = 1;
		$settings->acs_results_show_fields_content = 1;
        $settings->acs_results_show_fields_excerpt = 0;
		$settings->acs_results_show_fields_custom = 0;
		$settings->acs_results_custom_field = '';
		$settings->acs_results_show_fields_image = 1;
		$settings->acs_results_format_image = '';
		$settings->acs_results_show_fields_date = 1;
		$settings->acs_results_format_date = '';
		$settings->acs_results_show_fields_author = 1;
		$settings->acs_results_format_author = '';
		$settings->acs_results_show_fields_terms_content = 1;
		$settings->acs_results_show_fields_terms_custom = 0;
		$settings->acs_results_custom_terms_field = '';
		$settings->acs_results_no_results_msg = __( 'No results', ACS::PREFIX );
		$settings->acs_results_load_more_msg = __( 'Load more', ACS::PREFIX );
		$settings->acs_filter_type_field = ACS::TYPE_FIELD_DEFAULT;
		$settings->acs_filter_sort_field = ACS::SORT_FIELD_DEFAULT;
		$settings->acs_filter_sort_order = ACS::SORT_ORDER_DEFAULT;
		$settings->acs_results_max_items = ACS::SEARCH_RETURN_FULL_ITEMS;
		$settings->acs_results_field_weights = '';
        $settings->acs_filter_text_length = ACS::SEARCH_TEXT_LENGTH;
        $settings->acs_filter_text_length_type = ACS::SEARCH_TEXT_LENGTH_TYPE;
		$settings->acs_schema_fields_int = '';
		$settings->acs_schema_fields_double = '';
		$settings->acs_schema_fields_date = '';
		$settings->acs_schema_fields_literal = '';
		$settings->acs_schema_fields_sortable = '';
		$settings->acs_schema_fields_prefix = '';
		$settings->acs_schema_fields_separator = ACS::FIELD_SEPARATOR_DEFAULT;
		$settings->acs_schema_fields_image_size = '';
		$settings->acs_schema_fields_custom_image_id = '';
		$settings->acs_schema_fields_invalid_chars = '';
		$settings->acs_schema_fields_legacy_types = '';
		$settings->acs_schema_prevent_deletion = 0;
		$settings->acs_network_site_id = '';
		$settings->acs_network_blog_id = '';
        $settings->acs_highlight_type = ACS::HIGHLIGHT_TYPE_DEFAULT;
        $settings->acs_highlight_titles = 0;
        $settings->acs_highlight_color_text = '';
        $settings->acs_highlight_color_background = '';
        $settings->acs_highlight_style = '';
        $settings->acs_highlight_class = '';
        $settings->acs_suggest_active = 0;
		$settings->acs_suggest_only_title = 0;
        $settings->acs_suggest_selector = ACS::SUGGEST_DEFAULT_SELECTOR;
        $settings->acs_suggest_trigger = ACS::SUGGEST_DEFAULT_TRIGGER;
        $settings->acs_suggest_results = ACS::SUGGEST_DEFAULT_RESULTS;
        $settings->acs_suggest_order = ACS::SUGGEST_ORDER_TYPE_1;
        $settings->acs_suggest_click = ACS::SUGGEST_CLICK_TYPE_1;
        $settings->acs_suggest_all_font_size = ACS::SUGGEST_DEFAULT_ALL_FONT_SIZE;
        $settings->acs_suggest_all_color = ACS::SUGGEST_DEFAULT_ALL_COLOR;
        $settings->acs_suggest_all_background = ACS::SUGGEST_DEFAULT_ALL_BACKGROUND;
        $settings->acs_suggest_focused_font_size = ACS::SUGGEST_DEFAULT_FOCUSED_FONT_SIZE;
        $settings->acs_suggest_focused_color = ACS::SUGGEST_DEFAULT_FOCUSED_COLOR;
        $settings->acs_suggest_focused_background = ACS::SUGGEST_DEFAULT_FOCUSED_BACKGROUND;
		$settings->acs_hide_section_help = 0;
		$settings->acs_hide_section_docs = 0;
		$settings->acs_hide_section_import = 0;

        add_option( ACS::OPTION_SETTINGS, $settings );
	}
}
register_activation_hook( __FILE__, 'acs_activation' );

/**
 * Register deactivation hook
 */
function acs_deactivation() {
	// Delete plugin options
	delete_option( ACS::OPTION_STATUS );
	delete_option( ACS::OPTION_ITEMS );
}
register_deactivation_hook( __FILE__, 'acs_deactivation' );

/**
 * Check plugin version and upgrade
 */
function acs_upgrade() {
    $installed_version = get_option( ACS::OPTION_VERSION );

    //TODO: upgrades on db new fields

    // If table does not exists, create it
    if ( ( empty( $installed_version ) ) || intval( str_replace( '.', '', $installed_version ) ) < 120 ) {
        // Version less then release "1.2.0"

        // Get settings option
        $settings = ACS::get_instance()->get_settings();

        // Upgrades fields
        $settings->acs_highlight_type = ACS::HIGHLIGHT_TYPE_DEFAULT;
        $settings->acs_highlight_titles = 0;
        $settings->acs_highlight_color_text = '';
        $settings->acs_highlight_color_background = '';
        $settings->acs_highlight_style = '';
        $settings->acs_highlight_class = '';
        $settings->acs_suggest_active = 0;
	    $settings->acs_suggest_only_title = 0;
        $settings->acs_suggest_selector = ACS::SUGGEST_DEFAULT_SELECTOR;
        $settings->acs_suggest_trigger = ACS::SUGGEST_DEFAULT_TRIGGER;
        $settings->acs_suggest_results = ACS::SUGGEST_DEFAULT_RESULTS;
        $settings->acs_suggest_order = ACS::SUGGEST_ORDER_TYPE_1;
        $settings->acs_suggest_click = ACS::SUGGEST_CLICK_TYPE_1;
        $settings->acs_suggest_all_font_size = ACS::SUGGEST_DEFAULT_ALL_FONT_SIZE;
        $settings->acs_suggest_all_color = ACS::SUGGEST_DEFAULT_ALL_COLOR;
        $settings->acs_suggest_all_background = ACS::SUGGEST_DEFAULT_ALL_BACKGROUND;
        $settings->acs_suggest_focused_font_size = ACS::SUGGEST_DEFAULT_FOCUSED_FONT_SIZE;
        $settings->acs_suggest_focused_color = ACS::SUGGEST_DEFAULT_FOCUSED_COLOR;
        $settings->acs_suggest_focused_background = ACS::SUGGEST_DEFAULT_FOCUSED_BACKGROUND;
	    $settings->acs_hide_section_help = 0;
	    $settings->acs_hide_section_docs = 0;
	    $settings->acs_hide_section_import = 0;

        // Save option on database
        update_option( ACS::OPTION_SETTINGS, $settings );

        // Update plugin version
        update_option( ACS::OPTION_VERSION, ACS::VERSION );
    }

	// If terms fields does not exists, create it
	if ( ( empty( $installed_version ) ) || intval( str_replace( '.', '', $installed_version ) ) < 200 ) {
		// Version less then release "2.0.0"

		// Get settings option
		$settings = ACS::get_instance()->get_settings();

		// Upgrades fields
		$settings->acs_results_show_fields_terms_content = 1;
		$settings->acs_results_show_fields_terms_custom = 0;
		$settings->acs_results_custom_terms_field = '';

		// Save option on database
		update_option( ACS::OPTION_SETTINGS, $settings );

		// Update plugin version
		update_option( ACS::OPTION_VERSION, ACS::VERSION );
	}
}
add_action( 'plugins_loaded', 'acs_upgrade' );

/**
 * Init plugin
 *
 * @throws Exception
 */
function acs_init() {
	// Register autoloader
	$abspath = dirname( __FILE__ );
	new WP_Cloud_Search_Autoloader( 'WP_Cloud_Search', $abspath );

    // Get settings option
    $settings = ACS::get_instance()->get_settings();

    // If suggest is enabled add AJAX (via admin-ajax.php) and REST API callbacks
    if ( $settings->acs_suggest_active ) {
    	// Admin AJAX
        add_action( 'wp_ajax_acs_suggest_callback', 'wp_ajax_acs_suggest_callback' );
        add_action( 'wp_ajax_nopriv_acs_suggest_callback', 'wp_ajax_acs_suggest_callback' );

        // REST API
	    add_action( 'rest_api_init', 'acs_register_routes_hooks' );
    }
}
add_action( 'init', 'acs_init' );

/**
 * Load internalization supports
 */
function acs_load_text_domain() {
    load_plugin_textdomain( ACS::PREFIX, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'acs_load_text_domain' );