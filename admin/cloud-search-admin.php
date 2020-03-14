<?php
/**
 * Includes
 */
require_once( 'cloud-search-admin-help.php' );
require_once( 'cloud-search-admin-docs.php' );
require_once( 'cloud-search-admin-import.php' );
require_once( 'cloud-search-admin-manage.php' );
require_once( 'cloud-search-admin-settings.php' );

/**
 * Setup plugin menu
 */
function acs_setup_menu() {
	// Get WordPress version
	$wp_version = get_bloginfo( 'version' );

	// Get settings option
	$settings = ACS::get_instance()->get_settings();

	// Defaults
	$submenu_help = false;
	$submenu_docs = false;
	$submenu_import = false;

	// Check WordPress version, apply dashicons only for WordPress 4.0 or higher
	if ( $wp_version >= 4.0 ) {
		// Add menu pages
        add_menu_page( 'CloudSearch', 'CloudSearch', 'manage_options', ACS::MENU_MANAGE, 'acs_menu_page_manage', 'dashicons-cloud' );
		$submenu_manage = add_submenu_page( ACS::MENU_MANAGE, __( 'Manage', ACS::PREFIX ) . ' - CloudSearch', __( 'Manage', ACS::PREFIX ), 'manage_options', ACS::MENU_MANAGE, 'acs_menu_page_manage' );
		$submenu_settings = add_submenu_page( ACS::MENU_MANAGE, __( 'Settings', ACS::PREFIX ) . ' - CloudSearch', __( 'Settings', ACS::PREFIX ), 'manage_options', ACS::MENU_SETTINGS, 'acs_menu_page_settings' );
		if ( ! isset( $settings->acs_hide_section_help ) || ! $settings->acs_hide_section_help ) $submenu_help = add_submenu_page( ACS::MENU_MANAGE, __( 'Help', ACS::PREFIX ) . ' - CloudSearch', __( 'Help', ACS::PREFIX ), 'manage_options', ACS::MENU_HELP, 'acs_menu_page_help' );
		if ( ! isset( $settings->acs_hide_section_docs ) || ! $settings->acs_hide_section_docs ) $submenu_docs = add_submenu_page( ACS::MENU_MANAGE, __( 'Documentation', ACS::PREFIX ) . ' - CloudSearch', __( 'Documentation', ACS::PREFIX ), 'manage_options', ACS::MENU_DOCS, 'acs_menu_page_docs' );
		if ( ! isset( $settings->acs_hide_section_import ) || ! $settings->acs_hide_section_import ) $submenu_import = add_submenu_page( ACS::MENU_MANAGE, __( 'Import / Export', ACS::PREFIX ) . ' - CloudSearch', __( 'Import / Export', ACS::PREFIX ), 'manage_options', ACS::MENU_IMPORT, 'acs_menu_page_import' );
        }
	else {
		// Add menu pages
        add_menu_page( 'CloudSearch', 'CloudSearch', 'manage_options', ACS::MENU_MANAGE, 'acs_menu_page_manage' );
		$submenu_manage = add_submenu_page( ACS::MENU_MANAGE, __( 'Manage', ACS::PREFIX ) . ' - CloudSearch', __( 'Manage', ACS::PREFIX ), 'manage_options', ACS::MENU_MANAGE, 'acs_menu_page_manage' );
		$submenu_settings = add_submenu_page( ACS::MENU_MANAGE, __( 'Settings', ACS::PREFIX ) . ' - CloudSearch', __( 'Settings', ACS::PREFIX ), 'manage_options', ACS::MENU_SETTINGS, 'acs_menu_page_settings' );
		if ( ! isset( $settings->acs_hide_section_help ) || ! $settings->acs_hide_section_help ) $submenu_help = add_submenu_page( ACS::MENU_MANAGE, __( 'Help', ACS::PREFIX ) . ' - CloudSearch', __( 'Help', ACS::PREFIX ), 'manage_options', ACS::MENU_HELP, 'acs_menu_page_help' );
		if ( ! isset( $settings->acs_hide_section_docs ) || ! $settings->acs_hide_section_docs ) $submenu_docs = add_submenu_page( ACS::MENU_MANAGE, __( 'Documentation', ACS::PREFIX ) . ' - CloudSearch', __( 'Documentation', ACS::PREFIX ), 'manage_options', ACS::MENU_DOCS, 'acs_menu_page_docs' );
		if ( ! isset( $settings->acs_hide_section_import ) || ! $settings->acs_hide_section_import ) $submenu_import = add_submenu_page( ACS::MENU_MANAGE, __( 'Import / Export', ACS::PREFIX ) . ' - CloudSearch', __( 'Import / Export', ACS::PREFIX ), 'manage_options', ACS::MENU_IMPORT, 'acs_menu_page_import' );
	}

    // Add actions to enqueue style and scripts
	add_action( 'admin_print_styles-' . $submenu_manage, 'acs_admin_custom_css' );
	add_action( 'admin_print_styles-' . $submenu_settings, 'acs_admin_custom_css' );
	if ( ! isset( $settings->acs_hide_section_help ) || ! $settings->acs_hide_section_help ) add_action( 'admin_print_styles-' . $submenu_help, 'acs_admin_custom_css' );
	if ( ! isset( $settings->acs_hide_section_docs ) || ! $settings->acs_hide_section_docs ) add_action( 'admin_print_styles-' . $submenu_docs, 'acs_admin_custom_css' );
	if ( ! isset( $settings->acs_hide_section_import ) || ! $settings->acs_hide_section_import ) add_action( 'admin_print_styles-' . $submenu_import, 'acs_admin_custom_css' );

	add_action( 'admin_print_scripts-' . $submenu_manage, 'acs_admin_custom_manage_js' );
	add_action( 'admin_print_scripts-' . $submenu_settings, 'acs_admin_custom_settings_js' );
	if ( ! isset( $settings->acs_hide_section_help ) || ! $settings->acs_hide_section_help ) add_action( 'admin_print_scripts-' . $submenu_help, 'acs_admin_custom_help_js' );
	if ( ! isset( $settings->acs_hide_section_docs ) || ! $settings->acs_hide_section_docs ) add_action( 'admin_print_scripts-' . $submenu_docs, 'acs_admin_custom_help_js' );
	if ( ! isset( $settings->acs_hide_section_import ) || ! $settings->acs_hide_section_import ) add_action( 'admin_print_scripts-' . $submenu_import, 'acs_admin_custom_help_js' );
}
add_action('admin_menu', 'acs_setup_menu');

/**
 * Enqueue frontend styles and scripts
 */
function acs_custom_styles_and_scripts() {
	// Get settings option
	$settings = ACS::get_instance()->get_settings();

    // Add AJAX callbacks if jQuery and suggestion are enabled
    if ( $settings->acs_suggest_active && $settings->acs_frontpage_use_jquery ) {
        // Register stylesheets
        wp_enqueue_style( 'acs_style_suggest', plugins_url( 'cloud-search/css/dist/cloud-search-suggest.min.css' ), array(), ACS::VERSION );

        // Add custom suggest CSS
        if ( ! empty( $settings->acs_suggest_all_font_size ) && ! empty( $settings->acs_suggest_all_color ) && ! empty( $settings->acs_suggest_all_background ) && ! empty( $settings->acs_suggest_focused_font_size ) && ! empty( $settings->acs_suggest_focused_color ) && ! empty( $settings->acs_suggest_focused_background ) ) {
            echo "<style type=\"text/css\">" . "\n\r" .
                ".ui-menu .ui-menu-item { font-size: " . $settings->acs_suggest_all_font_size . "; background: " . $settings->acs_suggest_all_background . "; color: " . $settings->acs_suggest_all_color . "; font-weight: normal; text-shadow: 0; border: 0; -moz-border-radius: 0; -webkit-border-radius: 0; border-radius: 0; white-space: nowrap; }" . "\n\r" .
                ".ui-menu .ui-menu-item.ui-state-focus, .ui-menu .ui-menu-item.ui-state-hover, .ui-menu .ui-menu-item.ui-state-active { font-size: " . $settings->acs_suggest_focused_font_size . "; background: " . $settings->acs_suggest_focused_background . "; color: " . $settings->acs_suggest_focused_color . "; margin: 0; text-shadow: 0; border: 0; }" . "\n\r" .
                ".acs_suggesting { background: url(" . ACS::get_instance()->get_loading_image() . ") no-repeat 98% 50% !important; }" . "\n\r" .
                "</style>" .
                "\n\r";
        }

        // Register scripts
        if ( wp_script_is( 'jquery-ui-autocomplete', 'registered' ) ) {
            wp_register_script( 'acs_script_suggest', plugins_url( 'cloud-search/js/dist/cloud-search-suggest.min.js' ), array( 'jquery', 'jquery-ui-autocomplete' ), ACS::VERSION );
            wp_enqueue_script( 'acs_script_suggest' );
        }
        else {
            wp_register_script( 'jquery-ui-autocomplete', plugins_url( 'cloud-search/js/jquery/jquery-ui-1.9.2.custom.min.js' ), array( 'jquery-ui' ), ACS::VERSION, true );
            wp_register_script( 'acs_script_suggest', plugins_url( 'cloud-search/js/dist/cloud-search-suggest.min.js' ), array( 'jquery', 'jquery-ui-autocomplete' ), ACS::VERSION );
            wp_enqueue_script( 'acs_script_suggest' );
        }

        // Setup utils script vars
        wp_localize_script(
            'acs_script_suggest',
            'acs_config_suggest',
            array(
                //'ajax_url' => admin_url( 'admin-ajax.php' ),
	            'ajax_url' => esc_url_raw( get_rest_url() . ACS::API_NAMESPACE . '/v' . ACS::API_VERSION . '/suggest' ),
                'acs_suggest_selector' => stripslashes( $settings->acs_suggest_selector ),
                'acs_suggest_trigger' => $settings->acs_suggest_trigger,
                'acs_suggest_results' => $settings->acs_suggest_results,
                'acs_suggest_click' => $settings->acs_suggest_click
            )
        );
    }

    // Add CSS style and scripts if jQuery is enabled and is search page
	if ( is_search() && $settings->acs_frontpage_use_jquery ) {
        // Get current language
        $language = get_bloginfo( 'language' );
        if ( $language != 'it-IT' ) $language = 'en-US';

        // Register stylesheets
        wp_enqueue_style( 'acs_style', plugins_url( 'cloud-search/css/dist/cloud-search.min.css' ), array(), ACS::VERSION );

		// Register scripts
        wp_register_script( 'acs_script_locale', plugins_url( 'cloud-search/js/dist/cloud-search-locale-' . $language . '.min.js' ), array( 'jquery' ), ACS::VERSION );
        wp_register_script( 'acs_script', plugins_url( 'cloud-search/js/dist/cloud-search.min.js' ), array( 'jquery', 'jquery-ui-autocomplete' ), ACS::VERSION );
        wp_enqueue_script( 'acs_script_locale' );
        wp_enqueue_script( 'acs_script' );

        // Setup utils script vars
        wp_localize_script(
            'acs_script',
            'acs_config',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );
	}

	// Add custom CSS style in search page
	if ( is_search() && ! empty( $settings->acs_frontpage_custom_css ) ) {
		echo '<style type="text/css">' . $settings->acs_frontpage_custom_css . '</style>';
	}
}
add_action( 'wp_enqueue_scripts', 'acs_custom_styles_and_scripts' );

/**
 * Enqueue admin styles
 */
function acs_admin_custom_css() {
    // Register stylesheets
    wp_register_style( 'acs_style', plugins_url( 'cloud-search/css/dist/cloud-search.min.css' ) );
    wp_enqueue_style( 'acs_style' );
}

/**
 * Enqueue admin scripts for help page
 */
function acs_admin_custom_help_js() {
	// Get current language
	$language = get_bloginfo( 'language' );
	if ( $language != 'it-IT' ) $language = 'en-US';

	// Register scripts
	wp_register_script( 'acs_script_admin_locale', plugins_url( 'cloud-search/js/dist/cloud-search-locale-' . $language . '.min.js' ), array( 'jquery' ), ACS::VERSION );
	wp_register_script( 'acs_script_admin', plugins_url( 'cloud-search/js/dist/cloud-search-admin-help.min.js' ), array( 'jquery' ), ACS::VERSION );
	wp_enqueue_script( 'acs_script_admin_locale' );
	wp_enqueue_script( 'acs_script_admin' );
}

/**
 * Enqueue admin scripts for manage page
 */
function acs_admin_custom_manage_js() {
	// Get current language
	$language = get_bloginfo( 'language' );
	if ( $language != 'it-IT' ) $language = 'en-US';

	// Register scripts
	wp_register_script( 'acs_script_admin_locale', plugins_url( 'cloud-search/js/dist/cloud-search-locale-' . $language . '.min.js' ), array( 'jquery' ), ACS::VERSION );
    wp_register_script( 'acs_script_admin', plugins_url( 'cloud-search/js/dist/cloud-search-admin-manage.min.js' ), array( 'jquery' ), ACS::VERSION );
	wp_enqueue_script( 'acs_script_admin_locale' );
    wp_enqueue_script( 'acs_script_admin' );

	// Setup utils script vars
	wp_localize_script(
		'acs_script_admin',
		'acs_config',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'acs_actions_nonce' )
		)
	);
}

/**
 * Enqueue admin scripts for settings page
 */
function acs_admin_custom_settings_js() {
	// Get current language
	$language = get_bloginfo( 'language' );
	if ( $language != 'it-IT' ) $language = 'en-US';

	// Register scripts
	wp_register_script( 'acs_script_admin_locale', plugins_url( 'cloud-search/js/dist/cloud-search-locale-' . $language . '.min.js' ), array( 'jquery' ), ACS::VERSION );
	wp_register_script( 'acs_script_admin', plugins_url( 'cloud-search/js/dist/cloud-search-admin-settings.min.js' ), array( 'jquery' ), ACS::VERSION );
	wp_enqueue_script( 'acs_script_admin_locale' );
	wp_enqueue_script( 'acs_script_admin' );
}