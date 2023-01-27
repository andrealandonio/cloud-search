<?php

/**
 * Class ACS
 */
class ACS {

    /**
     * Class constants
     */
    const VERSION = '3.0.0';
    const PREFIX = 'cloud_search';
    const SEPARATOR = ',';

    const OPTION_MANAGE =' acs_option_manage';
    const OPTION_SEARCH =' acs_option_search';
    const OPTION_SETTINGS = 'acs_option_settings';
    const OPTION_STATUS = 'acs_option_status';
    const OPTION_ITEMS = 'acs_option_items';
	const OPTION_SWITCH = 'acs_option_switch';
    const OPTION_VERSION = 'acs_version';

    const MENU_HELP = 'acs_menu_help';
    const MENU_DOCS = 'acs_menu_docs';
	const MENU_IMPORT = 'acs_menu_import';
    const MENU_MANAGE = 'acs_menu_manage';
    const MENU_SEARCH = 'acs_menu_search';
    const MENU_SETTINGS = 'acs_menu_settings';

	const SYNC_CHUNK = 100;
	const DELETE_CHUNK = 100;

    const QUERY_PARSER = 'lucene';
    const ANALYSIS_SCHEMA = '_mul_default_';
    const TYPE_FIELD_DEFAULT = 'all';
    const SORT_FIELD_DEFAULT = '_score';
    const SORT_ORDER_DEFAULT = 'desc';
    const HIGHLIGHT_TYPE_DEFAULT = 'none';
    const FIELD_SEPARATOR_DEFAULT = '|';
	const KEY_SEPARATOR_DEFAULT = '_';
	const EXCLUDE_FIELD = 'acs_exclude';
	const DEFAULT_TYPE = 'post';
	const DEFAULT_STATUS = 'publish';
	const TERM_KEY_SUFFIX = 'term';

    const CUSTOM_FIELD_PREFIX = 'cf_';
    const CUSTOM_TAXONOMY_PREFIX = 'ct_';

	const SEARCH_TEMPLATES_DIRECTORY = 'search';

    const SEARCH_RETURN_TEST_ITEMS = 1;
    const SEARCH_RETURN_FULL_ITEMS = 100;
    const SEARCH_TEXT_LENGTH = 50;
    const SEARCH_TEXT_LENGTH_TYPE = 'words';
	const SEARCH_FIELD_DEFAULT = 'all';
	const SEARCH_KEY_TYPE_DEFAULT = 'all';
	const SEARCH_ENTITY_TYPE_DEFAULT = 'post';

    const ACTION_GET_INDEX_SEARCHABLE_DOCUMENTS = 'get_index_searchable_documents';
    const ACTION_GET_INDEX_SITE_DOCUMENTS = 'get_index_site_documents';
    const ACTION_GET_INDEX_FIELDS = 'get_index_fields';
    const ACTION_GET_INDEX_STATUS = 'get_index_status';
	const ACTION_SEARCH_DOCUMENTS = 'search_documents';
    const ACTION_UPDATE_DOCUMENTS = 'update_documents';
	const ACTION_SYNC_DOCUMENTS = 'sync_documents';
    const ACTION_DELETE_DOCUMENTS = 'delete_documents';
	const ACTION_STOP_SYNC_DOCUMENTS = 'stop_sync_documents';
	const ACTION_SETTINGS = 'acs_settings';
	const ACTION_IMPORT = 'acs_settings_import';
	const ACTION_EXPORT = 'acs_settings_export';

	const NONCE_SETTINGS_IMPORT = 'acs_settings_import_nonce';
	const NONCE_SETTINGS_EXPORT = 'acs_settings_export_nonce';

	const SUGGEST_DEFAULT_SELECTOR = 'input[name=\'s\']';
    const SUGGEST_DEFAULT_TRIGGER = 3;
    const SUGGEST_DEFAULT_RESULTS = 10;
    const SUGGEST_DEFAULT_ALL_FONT_SIZE = '14px';
    const SUGGEST_DEFAULT_ALL_COLOR = '#333333';
    const SUGGEST_DEFAULT_ALL_BACKGROUND = '#FFFFFF';
    const SUGGEST_DEFAULT_FOCUSED_FONT_SIZE = '14px';
    const SUGGEST_DEFAULT_FOCUSED_COLOR = '#333333';
    const SUGGEST_DEFAULT_FOCUSED_BACKGROUND = '#EFEFEF';
    const SUGGEST_ORDER_TYPE_1 = 'relevance';
    const SUGGEST_ORDER_TYPE_2 = 'alphabetically';
    const SUGGEST_ORDER_TYPE_3 = 'reverse';
    const SUGGEST_CLICK_TYPE_1 = 'none';
    const SUGGEST_CLICK_TYPE_2 = 'goto';

	const FACET_DEFAULT_METHOD = 'count';
	const FACET_DEFAULT_COUNT = 10;

    const STATUS_NO_OPERATION = 0;
    const STATUS_SYNC_ACTIVE = 10;
	const STATUS_DELETE_ACTIVE = 20;

	const API_VERSION = '1';
	const API_NAMESPACE = 'cloud-search';

	/**
     * Class array constants
     */
    public static $SEARCH_FIELD_TYPES = array(
        'all' => 'all',
        'id' => 'id'
    );
    public static $ACTION_FIELD_KEY_TYPES = array(
        'id' => 'id'
    );
	public static $ACTION_FIELD_ENTITY_TYPES = array(
		'post' => 'post',
		'category' => 'category'
	);
    public static $CONTENT_BOX_TYPES = array(
        'default' => 'Default',
        'custom' => 'Custom',
        'format' => 'Formats and types based',
        'plugin' => 'Plugin based'
    );
    public static $AUTHOR_FORMATS = array(
        'display_name_with_link' => 'Display name with link',
        'display_name_without_link' => 'Display name without link',
        'full_name_with_link' => 'Full name with link',
        'full_name_without_link' => 'Full name without link',
        'username_with_link' => 'Username with link',
        'username_without_link' => 'Username without link'
    );
    public static $SORT_FIELDS = array(
        '_score' => 'Relevance',
        'post_date_gmt' => 'Publish date',
        'post_modified_gmt' => 'Modified date',
        'id' => 'ID'
    );
    public static $SORT_ORDERS = array(
        'desc' => 'Descending',
        'asc' => 'Ascending'
    );

	/**
     * Class instance
     *
     * @var ACS $instance
     */
    private static $instance;

    /**
     * Site ID
     *
     * @var int $site_id
     */
    private $site_id;

    /**
     * Blog ID
     *
     * @var int $blog_id
     */
    private $blog_id;

    /**
     * Loading image
     *
     * @var string $loading_image
     */
    private $loading_image;

    /**
     * Key
     *
     * @var string $key
     */
    private $key;

	/**
	 * Schema language
	 *
	 * @var string $language
	 */
	private $language;

    /**
     * Settings and configuration object
     *
     * @var stdClass $settings
     */
	private $settings;

	/**
	 * Returns the singleton class instance
	 *
	 * @return ACS
	 */
	public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new ACS();
        }

		return self::$instance;
	}

	/**
	 * Protected constructor to prevent creating a new instance of the singleton via the 'new' operator from outside of this class
	 */
	protected function __construct() {
        // Read settings option
        $this->settings = get_option( ACS::OPTION_SETTINGS );

        // Set site/blog defaults
        $this->site_id = 1;
        $this->blog_id = 1;

        // Set site/blog info if WordPress multisite support is enabled
        if ( is_multisite() ) {
            $blog_details = get_blog_details();
            $this->site_id = $blog_details->site_id;
            $this->blog_id = $blog_details->blog_id;
        }

		// Overrides site/blog info (if provided by the user)
		if ( isset( $this->settings->acs_network_site_id ) && ! empty( $this->settings->acs_network_site_id ) && is_numeric( $this->settings->acs_network_site_id ) ) $this->site_id = intval( $this->settings->acs_network_site_id );
		if ( isset( $this->settings->acs_network_blog_id ) && ! empty( $this->settings->acs_network_blog_id ) && is_numeric( $this->settings->acs_network_blog_id ) ) $this->blog_id = intval( $this->settings->acs_network_blog_id );

        // Set loading image path
        $this->loading_image = plugins_url( 'cloud-search/images/loading.gif' );

		// Set Analysis schema language
		$this->language = acs_decode_schema_language();
    }

	/**
	 * Private clone method to prevent cloning of the instance of the singleton instance
	 *
	 * @return void
	 */
	private function __clone() {
	}

    /**
     * Get settings option
     *
     * @return stdClass|null
     */
    public function get_settings() {
        return $this->settings;
    }

	/**
	 * Reload settings option
	 */
	public function reload_settings() {
		$this->settings = get_option( ACS::OPTION_SETTINGS );
	}

    /**
     * Get site_id
     *
     * @return int
     */
    public function get_site_id() {
        return $this->site_id;
    }

    /**
     * Get blog_id
     *
     * @return int
     */
    public function get_blog_id() {
        return $this->blog_id;
    }

    /**
     * Get loading_image
     *
     * @return string
     */
    public function get_loading_image() {
        return $this->loading_image;
    }

    /**
     * Set loading_image
     *
     * @param string $loading_image
     */
    public function set_loading_image( $loading_image ) {
        $this->loading_image = $loading_image;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function get_key() {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param string $key
     */
    public function set_key( $key ) {
        $this->key = $key;
    }

	/**
	 * Get language
	 *
	 * @return string
	 */
	public function get_language() {
		return $this->language;
	}

	/**
	 * Set language
	 *
	 * @param string $language
	 */
	public function set_language( $language ) {
		$this->language = $language;
	}
}