<?php
/**
 * Render admin menu help page
 */
function acs_menu_page_help() {
    ?>
    <div class="wrap">
        <h2><?php _e( 'Help', ACS::PREFIX ) ?></h2>

		<div class="acs_help_legend">
			<ul class="acs_help_legend_first_level">
				<li><a href="#acs_help_about_the_plugin" class="acs_to_bottom"><?php _e( 'About the plugin', ACS::PREFIX ) ?></a></li>
				<li><a href="#acs_help_installation" class="acs_to_bottom"><?php _e( 'Installation', ACS::PREFIX ) ?></a></li>
				<li>
                    <a href="#acs_help_configuration" class="acs_to_bottom"><?php _e( 'Configuration', ACS::PREFIX ) ?></a>
                    <ul class="acs_help_legend_second_level">
                        <li><a href="#acs_help_configuration_setup_amazon_aws" class="acs_to_bottom"><?php _e( 'Setup Amazon AWS', ACS::PREFIX ) ?></a></li>
                        <li><a href="#acs_help_configuration_setup_cloudsearch_plugin" class="acs_to_bottom"><?php _e( 'Setup CloudSearch plugin', ACS::PREFIX ) ?></a></li>
                        <li><a href="#acs_help_configuration_more_about_plugin_settings" class="acs_to_bottom"><?php _e( 'More about plugin settings', ACS::PREFIX ) ?></a></li>
                    </ul>
                </li>
				<li>
                    <a href="#acs_help_how_plugin_works" class="acs_to_bottom"><?php _e( 'How plugin works', ACS::PREFIX ) ?></a>
                    <ul class="acs_help_legend_second_level">
                        <li><a href="#acs_help_how_plugin_works_frontend_results_and_filters" class="acs_to_bottom"><?php _e( 'Frontend results and filters', ACS::PREFIX ) ?></a></li>
                        <li><a href="#acs_help_how_plugin_works_wordpress_admin_section" class="acs_to_bottom"><?php _e( 'WordPress admin section', ACS::PREFIX ) ?></a></li>
                        <li><a href="#acs_help_how_plugin_works_customize_your_pages" class="acs_to_bottom"><?php _e( 'Customize your pages', ACS::PREFIX ) ?></a></li>
                        <li><a href="#acs_help_how_plugin_works_sync_operations" class="acs_to_bottom"><?php _e( 'Sync operations', ACS::PREFIX ) ?></a></li>
                        <li><a href="#acs_help_how_plugin_works_manage_languages" class="acs_to_bottom"><?php _e( 'Manage languages', ACS::PREFIX ) ?></a></li>
                    </ul>
                </li>
				<li><a href="#acs_help_api" class="acs_to_bottom"><?php _e( 'API', ACS::PREFIX ) ?></a></li>
				<li><a href="#acs_help_faq" class="acs_to_bottom"><?php _e( 'FAQ', ACS::PREFIX ) ?></a></li>
				<li><a href="#acs_help_references" class="acs_to_bottom"><?php _e( 'References', ACS::PREFIX ) ?></a></li>
			</ul>
		</div>

		<hr />

		<div class="acs_help_content">

			<a id="acs_help_about_the_plugin"><h4><?php _e( 'About the plugin', ACS::PREFIX ) ?></h4><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p>
                <?php _e( 'CloudSearch is a flexible plugin that allows you to leverage the search index power of Amazon CloudSearch in your WordPress site. The plugin is completely free. To use this plugin you\'ll need an Amazon Web Services account. Attention: Amazon CloudSearch is a paid service and will require a credit card. There are costs (based upon usage) associated with the Amazon CloudSearch service.', ACS::PREFIX ) ?>
                <?php _e( 'You can learn more about expected costs', ACS::PREFIX ) ?> <a href="http://aws.amazon.com/cloudsearch/pricing/" target="_blank"><?php _e( 'here', ACS::PREFIX ) ?></a>.
            </p>

            <div class="acs_help_content_list_bottom_spacer"><?php _e( 'The plugin strengths:', ACS::PREFIX ) ?></div>
			<ul class="acs_help_content_list">
				<li><?php _e( 'Provides results based upon relevancy which provides better results than WordPress\'s standard text search', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Provides built-in methods for filtering post types and for sorting the results', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Integrates with your site\'s theme without the need for other customization. Provides optional features such as filtering and layout customization', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Uses Amazon\'s CloudSearch SaaS and runs on top of a WordPress installation with no additional servers, services, or hosting configuration needed', ACS::PREFIX ) ?></li>
			</ul>

			<a id="acs_help_installation"><h4><?php _e( 'Installation', ACS::PREFIX ) ?></h4><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'Like most WordPress plugins there are 3 ways to install a plugin.', ACS::PREFIX ) ?></p>
			<div class="acs_help_content_list_title"><?php _e( 'Install by searching the WordPress.org plugin repository', ACS::PREFIX ) ?></div>
			<ul class="acs_help_content_list">
				<li><?php _e( 'Log in to your WordPress admin dashboard', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Click the "Plugins" menu and enter "CloudSearch" into the search box', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Select "CloudSearch" and click "Install Now."', ACS::PREFIX ) ?></li>
				<li><?php _e( 'You will receive a confirmation message when the plugin finishes installing', ACS::PREFIX ) ?></li>
			</ul>
			<p><?php _e( 'or after downloading "CloudSearch" you can:', ACS::PREFIX ) ?></p>
			<div class="acs_help_content_list_title"><?php _e( 'Install by uploading the plugin through your WordPress dashboard', ACS::PREFIX ) ?></div>
			<ul class="acs_help_content_list">
				<li><?php _e( 'Log in to your WordPress admin dashboard', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Click the "Plugins" menu and choose "Add New" from the top menu', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Click "Choose File", locate the "cloud-search.zip" file and click "Install Now"', ACS::PREFIX ) ?></li>
				<li><?php _e( 'You will receive a confirmation message when the plugin finishes installing', ACS::PREFIX ) ?></li>
			</ul>
			<div class="acs_help_content_list_title"><?php _e( 'Install manually', ACS::PREFIX ) ?></div>
			<ul class="acs_help_content_list">
				<li><?php _e( 'Unzip the "cloud-search.zip" that you downloaded', ACS::PREFIX ) ?></li>
				<li><?php _e( 'Upload the contents of "cloud-search.zip" into your plugins directory', ACS::PREFIX ) ?></li>
			</ul>

			<a id="acs_help_configuration"><h4><?php _e( 'Configuration', ACS::PREFIX ) ?></h4><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'Before you can start using CloudSearch plugin, the Amazon account needs to be prepared and the plugin needs to be activated and configured.', ACS::PREFIX ) ?></p>

			<a id="acs_help_configuration_setup_amazon_aws"><h5><?php _e( 'Setup Amazon AWS', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'To use CloudSearch plugin you will need an Amazon Web Services account configured. Please look the reference section and take some time to look over the AWS documentation. Now, continue reading to find how you can set-up AWS account.' , ACS::PREFIX ) ?></p>
            <div class="acs_help_content_list_title"><?php _e( 'If you have an Amazon Web Services account', ACS::PREFIX) ?></div>
			<ul class="acs_help_content_list">
                <li><?php _e( 'Log in with your account username and password', ACS::PREFIX) ?></li>
			    <?php echo str_replace( '#OBJ#', '<img src="' . plugins_url( 'cloud-search/images/help/how_to_access_security_credentials.jpg' ) . '" width="700" alt="" />', '<li>' . __( 'Click on the "Account" link in top right corner of the header, then click on "Security Credentials" to find your Access Key ID and your Secret Access key', ACS::PREFIX) . '<br />#OBJ#</li>' ) ?>
			    <?php echo str_replace( '#OBJ#', '<img src="' . plugins_url( 'cloud-search/images/help/read_access_keys.jpg' ) . '" width="700" alt="" /><br /><img src="' . plugins_url( 'cloud-search/images/help/create_access_keys.jpg' ) . '" width="700" alt="" />', '<li>' . __( 'Now you can copy keys to a text document (you will use during plugin activation) or you can create new ones', ACS::PREFIX ) . '<br />#OBJ#</li>' ) ?>
                <li><?php _e( 'Go to CloudSearch dashboard', ACS::PREFIX) ?></li>
                <li><?php _e( 'Proceed now with index creation following the steps explained below', ACS::PREFIX) ?></li>
                <div class="steps">
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_1.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'Create new domain providing a name (use other values with defaults)', ACS::PREFIX ) . '</div></div>' ) ?>
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_2.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'Choose manual index configuration', ACS::PREFIX ) . '</div></div>' ) ?>
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_3.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'Do not add index fields and go forward (the plugin will create fields)', ACS::PREFIX ) . '</div></div>' ) ?>
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_4.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'Provide security policy (for demo, allow open access to all services)', ACS::PREFIX ) . '</div></div>' ) ?>
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_5.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'Review all the information and confirm index creation', ACS::PREFIX ) . '</div></div>' ) ?>
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_6.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'Confirmation of creation', ACS::PREFIX ) . '</div></div>' ) ?>
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_7.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'Initialization of your index (loading status)', ACS::PREFIX ) . '</div></div>' ) ?>
                    <?php echo str_replace( '#OBJ#', '<img class="acs_enlarge_step_image" src="' . plugins_url( 'cloud-search/images/help/setup_aws_step_8.jpg' ) . '" width="300" alt="" />', '<div class="steps_img">#OBJ#<div class="steps_caption">' . __( 'When index is active save "Search Endpoint" URL (will be used during plugin configuration)', ACS::PREFIX ) . '</div></div>' ) ?>
                </div>
            </ul>
            <div class="acs_help_content_list_title"><?php _e( 'If you do not have an Amazon Web Services account', ACS::PREFIX) ?></div>
            <ul class="acs_help_content_list">
                <li><?php _e( 'Visit the', ACS::PREFIX) ?> <a href="http://aws.amazon.com/" target="_blank"><?php _e( 'Amazon Web Services', ACS::PREFIX) ?></a> <?php _e( 'page and create a new account', ACS::PREFIX) ?></li>
                <li><?php _e( 'As mentioned before, AWS is not free, you will need to enter payment and billing informations', ACS::PREFIX) ?></li>
                <li><?php _e( 'There will be a phone identity verification', ACS::PREFIX) ?></li>
                <li><?php _e( 'Once verified, you can log in with your account username and password', ACS::PREFIX) ?></li>
                <li><?php _e( 'Follow the set-up instructions explained for "existing" Amazon accounts. See above', ACS::PREFIX) ?></li>
            </ul>

            <a id="acs_help_configuration_setup_cloudsearch_plugin"><h5><?php _e( 'Setup CloudSearch plugin', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p>
                <?php _e( 'After Amazon AWS account setup, you have to activate and setup the plugin. You have to go into the "Settings" page and fill the necessary data. This page is composed by different setting sections: an Amazon settings, a Index settings, a Schema settings, a Frontpage settings, a Result setting and a generic Other settings. For an initial setup you have only to set up the Amazon and Index settings. The other sections are not mandatory for the basic plugin configuration.', ACS::PREFIX ) ?><br />
            </p>
            <p>
                <i><strong><?php _e( 'Amazon settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by 3 fields that are used to connect with the Amazon Web Services. The fields are "AWS Access key ID", "AWS Secret access key" and "AWS Region". All the information about these values are viewable in your Amazon account (as you can read in the "Setup Amazon AWS" help section).', ACS::PREFIX ) ?><br />
	            <?php _e( 'To increase security you can store the Amazon access keys in the wp-config.php file and hide them from the plugin settings. Note that is just for this plugin and not for Amazon Web Services. You have to define until 5 constants in this way:', ACS::PREFIX ) ?>
            </p>
            <pre>define('WP_ACS_ACCESS_KEY', 'KEY_HERE');
define('WP_ACS_SECRET_KEY', 'KEY_HERE');
define('WP_ACS_SESSION_TOKEN', 'KEY_HERE');
define('WP_ACS_REGION', 'REGION_HERE');
define('WP_ACS_SEARCH_ENDPOINT', 'SEARCH_ENDPOINT_HERE');
define('WP_ACS_SEARCH_DOMAIN', 'SEARCH_DOMAIN_HERE');</pre>
			<p><?php _e( 'To better configure CloudSearch client you can modify some client\'s options by defining the related constants in the wp-config.php file. If not provided, default values are used. You have these possible options:', ACS::PREFIX ) ?></p>
            <pre>define('WP_ACS_DOMAIN_CLIENT_RETRIES', 3);
define('WP_ACS_DOMAIN_CLIENT_CONNECT_TIMEOUT', 0);
define('WP_ACS_DOMAIN_CLIENT_TIMEOUT', 0);
define('WP_ACS_SCHEMA_LANGUAGE', '_en_default_');</pre>
            <p>
                <i><strong><?php _e( 'Index settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by 2 fields that are used to communicate to a specific CloudSearch index schema. The fields are "Search endpoint" and "Domain name". All the information about these values are viewable in your Amazon CloudSearch domain dashboard (as you can read in the "Setup Amazon AWS" help section). For example, look at your index dashboard, you have the "search-YOURNAME-wyg76gd7hdiagd87wqeguhgw72.eu-west-1.cloudsearch.amazonaws.com" value that you use as "Search endpoint" and the "YOURNAME" value that you use as "Domain name".', ACS::PREFIX ) ?><br />
                <?php _e( 'Once you have finished to setup these two section you have to go the "Manage" section, press the "Create/update index" button and then run an indexing by pressing the relative button. After several minutes your index is ready to work and now you can start with an initial synchronization of all the documents.', ACS::PREFIX ) ?>
            </p>
			<p>
				<?php _e( 'Mandatory fields are only: AWS region, search endpoint and domain name. If you provide an AWS Access key ID and an AWS Secret access key, the plugin uses these as credentials, otherwise it will try to use IAM roles. Using custom credentials gives you the opportunity to use different AWS keys for this service giving to these only a small set of permissions.', ACS::PREFIX ) ?>
			</p>

			<a id="acs_help_configuration_more_about_plugin_settings"><h5><?php _e( 'More about plugin settings', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'In the "Settings" page there are more fields than the Amazon and Index settings. With the other fields you can highly customize the CloudSearch plugin. In the following paragraph we go deep into the other settings.', ACS::PREFIX ) ?></p>
            <p>
                <i><strong><?php _e( 'Schema settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by 3 main fields that are used to select which custom types, fields and taxonomies you want to store into your CloudSearch index. For every list you have to select the rows that you want to save in the index. A set of default fields are already provided by the plugin, with this boxes you only add extra information. The post types and taxonomy types are composed automatically searching into your WordPress all registered custom types and custom taxonomies, instead, the field types need a manual setup. By default, retrieves all registered custom fields but you can limit the list values. Read the "Other settings" section to understand how to customize the field types list values.', ACS::PREFIX ) ?><br />
	            <?php _e( 'The default term "category" is synchronized by default on CloudSearch. You have to select the "Taxonomy types" in the "Schema settings" to synchronize other custom terms. All the terms are by default not searchable, if you want to search also terms you have to select the desired terms in the "Searchable taxonomies" field in the "Schema settings" .', ACS::PREFIX ) ?><br />
                <?php _e( 'The section have a last field "Prevent fields deletion" that is useful if you change your index schema outside the plugin (by default the plugin deletes all the index fields not configured in the this section). For example, if you have added some index fields in the Amazon CloudSearch console and you do not want to lose your changes, enabling this field, when the plugin run an indexing do not remove fields not defined in the "Schema settings". If you have added fields to CloudSearch without using the plugin and, if by mistake, you have clicked the "Create/update index" button, don\'t worry! Before run an indexing you have to go in the AWS console and navigate to your "Indexing options" CloudSearch section (see image below). You can see some fields in a "Pending Deletion" status, you simply have to click recover to all the fields, then do an indexing to update the CloudSearch schema keeping your "custom" fields.', ACS::PREFIX) ?>
            </p>
			<?php echo str_replace( '#OBJ#', '<img src="' . plugins_url( 'cloud-search/images/help/aws_indexing_options.jpg' ) . '" width="170" alt="How to go to AWS Indexing options" />', __( '#OBJ#<br /><br />', ACS::PREFIX ) ) ?>
            <p>
                <i><strong><?php _e( 'Frontpage settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by fields that are used to customize the frontpage layout. The "Use plugin search page" gives you the opportunity to use the integrated search result page or to create one by your own. The "Use jQuery" field is useful if you do not want to use jQuery for frontend asynchronous call (for example, contacting the API). Disabling this field no jQuery scripts are loaded in page. Read the "Documentation" to understand how to customize your scripts and work with the API. The "Show filters" is used to choose if you want to display the filter dropdowns in the frontpage search page.', ACS::PREFIX ) ?>
            </p>
            <div class="acs_help_content_list_bottom_spacer"><?php _e( 'The section main field is the "Content box type". This field gives you the opportunity to set the search item box template choosing from:', ACS::PREFIX ) ?></div>
            <ul class="acs_help_content_list">
                <li><i><?php _e( 'Default', ACS::PREFIX ) ?></i>: <?php _e( 'use the default "content.php" template from WordPress', ACS::PREFIX ) ?></li>
                <li><i><?php _e( 'Custom', ACS::PREFIX ) ?></i>: <?php _e( 'use a named template from WordPress (eg: content-your_name.php)', ACS::PREFIX ) ?></li>
                <li><i><?php _e( 'Formats and types based', ACS::PREFIX ) ?></i>: <?php _e( 'use the default WordPress formats management with a named template prefix (eg: your_prefix-format_or_type_slug.php)', ACS::PREFIX ) ?></li>
                <li><i><?php _e( 'Plugin based', ACS::PREFIX ) ?></i>: <?php _e( 'use the custom plugin "content.php" template located in plugin sources', ACS::PREFIX ) ?></li>
            </ul>
            <p>
                <i><strong><?php _e( 'Result settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by fields that are used to customize the result items. These fields are used specially with the plugin content box type and the build-in jQuery scripts, if you use different solution not all of these option are used. Going deep in every single option. The "Showed fields" field is used to define which fields you what to show in the result item boxes (e.g. you can show the post content, the post excerpt, the post author, the post date, the featured image, etc). The "No results message" gives you the opportunity to set up a text if a search query gets no results, alternatively, you can provide a custom template for no results. The "Load more message" is similar to the "No results message", you can insert a text or, directly, the HTML. The field "Length of the text" give you the opportunity to limit the post content text (by choosing how many chars or words to display). The last 3 fields "Search max items", "Default sort field" and "Default sort order" are useful for set up the max page items, the default index field used for sort and the default sort order in the CloudSearch queries.', ACS::PREFIX ) ?>
            </p>
            <div class="acs_help_content_list_bottom_spacer"><?php _e( 'The author field formats could be:', ACS::PREFIX ) ?></div>
            <ul class="acs_help_content_list">
                <li><i><?php _e( 'Display name with link', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Display name without link', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Full name with link', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Full name without link', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Username with link', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Username without link', ACS::PREFIX ) ?></i></li>
            </ul>
            <p><?php _e( 'You can assign weights to selected fields so you can boost the relevance _score of documents with matches in key fields, and minimize the impact of matches in less important fields. By default all fields have a weight of 1. Field weights are set with the q.options fields option. You specify fields as an array of strings. To set the weight for a field, you append a caret (^) and a positive numeric value to the field name. You cannot set a field weight to zero or use mathematical functions or expressions to define a field weight. For example, if you want matches within the post_title field to score higher than matches within the post_content field, you could set the weight of the post_title field to 2 and the weight of the post_content field to 0.5:', ACS::PREFIX ) ?></p>
            <pre>post_title^2,post_content^0.5</pre>
            <p><?php _e( 'Fields weighting works well with "simple" and "lucene" query parsers. The fields option defines the set of fields that are searched by default if you use the simple query parser or don\'t specify a field in part of a compound expression when using the structured query parser.', ACS::PREFIX ) ?></p>
            <div class="acs_help_content_list_bottom_spacer"><?php _e( 'The default sort field could be:', ACS::PREFIX ) ?></div>
            <ul class="acs_help_content_list">
                <li><i><?php _e( 'Relevance', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Publish date', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Modified date', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'ID', ACS::PREFIX ) ?></i></li>
            </ul>
            <div class="acs_help_content_list_bottom_spacer"><?php _e( 'The default sort order could be:', ACS::PREFIX ) ?></div>
            <ul class="acs_help_content_list">
                <li><i><?php _e( 'Descending', ACS::PREFIX ) ?></i></li>
                <li><i><?php _e( 'Ascending', ACS::PREFIX ) ?></i></li>
            </ul>
            <p><?php _e( 'Sort for terms searches is only related to relevance (_score) because in terms table there is no reference to date information.', ACS::PREFIX ) ?></p>
            <p>
                <i><strong><?php _e( 'Highlighting settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by fields that are used to set up highlight configurations. The "Style type" field gives you the opportunity to choose an HTML style for every search hit, like "strong", "italic" and "underline". With the field "Highlight in titles" you can choose if you want to highlight search hits in titles. The "Text color" and "Background color" are very similar and are usefull if you want to apply an RGB color to every hit text or background. The last two fields, "CSS style" and "CSS class", are used to provide a CSS inline style and to insert a CSS class name. All the search hits will be wrapped in a span with the provided settings.', ACS::PREFIX ) ?><br />
	            <?php _e( 'The highlights features are available also for term results.', ACS::PREFIX ) ?>
            </p>
            <p>
                <i><strong><?php _e( 'Suggestions settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by fields that are used to set up the search suggestions configurations. First of all you can activate the suggestions by turning on the "Active" flag. The other fields give you the opportunity to customize the suggestion output style (for generic and focused rows), the suggestion results order and the max showed items, the click behavior when you click a suggested value, the jQuery selector for the search field and the typed chars before the suggestion starts. If active, after you have typed the number of your configured chars, the plugin makes a request to a CloudSearch API (note that API query searches only on the post title field in the current site, do not search in network) and gives you back the search results like a dropdown menu under the search field.', ACS::PREFIX ) ?><br />
                <?php _e( 'Pay attention that, the "Use jQuery" flag is mandatory if you want to activate the search suggestions.', ACS::PREFIX ) ?>
            </p>
            <p>
                <i><strong><?php _e( 'Other settings', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'This section is composed by fields that are used to set up generic configurations. The "Custom field types prefix/suffix" is very important if you want to filter the custom field types list in the "Schema settings". Provide a prefix or a suffix to retrieve a smaller set of custom fields instead all the registered fields (e.g. use "cmb_,post_", separated by comma, to select only fields that name starts by "cmb_" or "post_"). The "Field values separator" is used in text-array index fields for separate internal values (e.g. for category values use "ID|NAME", where "|" is the separator char). The field "Item image size name" gives you the opportunity to store in the CloudSearch index a custom image for every post. This value is not necessary to retrieve and show search query results, it\'s only used for extra features. Think at an external application that needs to read your CloudSearch data, providing a image size you can store in your index the featured image URL and the external application can read it without an extra lookup for the post image because it\'s already contained in your index. In the same way, if you are using the "Multiple Post Thumbnails" plugin, inserting in the field "Custom item image ID" the key to retrieve the custom "Multiple Post Thumbnails" image, you can also store this "media" type in the CloudSearch index.', ACS::PREFIX ) ?>
	            <?php _e( 'You can provide a list of invalid chars (for example Unicode chars), separated by "|", that will be removed from your documents before syncing them into the CloudSearch index.', ACS::PREFIX ) ?>
	            <?php _e( 'You can provide a list of custom legacy post types, separated by ",", for adding those post types to searches.', ACS::PREFIX ) ?><br />
                <?php _e( 'Finally, you can choose if you want to hide "Help" and "Documentation" sections from admin menu.', ACS::PREFIX ) ?>
            </p>

			<a id="acs_help_how_plugin_works"><h4><?php _e( 'How plugin works', ACS::PREFIX ) ?></h4><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'Plugin\'s behavior is very simple, after creating the schema on CloudSearch at every post transition event a sync of the modified document is throw through the AWS index to keep synchronized data between your WordPress database and CloudSearch schema (by default if a post is "publish" a insert/update is executed, otherwise, for drafts, delete, etc a delete is executed). Alternatively at the save post hook we have every time the possibility to force a sync/delete operation to a single post or for the entire database. Note that the plugin starts working only when the \'Amazon settings\' and \'Index settings\' fields are provided. In addition to the backend functionality the Plugin is integrated with the search engine of WordPress. If you provide a "?s=" value a search is performed using the plugin. The plugin catches the "s" parameter value, does a asynchronous call to an internal API and displays the search results according to the plugin settings explained above.', ACS::PREFIX ) ?></p>
            <div class="acs_help_content_list_bottom_spacer"><?php _e( 'In the following paragraph you can find a list of technical choices used for writing the plugin\'s core:', ACS::PREFIX ) ?></div>
            <ul class="acs_help_content_list">
                <li><?php _e( 'a row key in the CloudSearch schema is defined with the pattern #site_id#_#blog_id#_#post_id#, in this way every row is completely unique and you can add external rows simply using a different #site_id#/#blog_id#, for example, your WordPress use a key like "1_1_1234", an external tool can use"0_0_1234", same post ID but for different environments. To avoid duplication on IDs a "_term" suffix is used for term items.', ACS::PREFIX ) ?></li>
                <li><?php _e( 'the sets of standard fields automatically added to the schema:', ACS::PREFIX ) ?><br />
<pre>
Field              Type        Facet          Search         Return         Sort
-----------------  ----------  -------------  -------------  -------------  -------------
site_id            int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
blog_id            int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
id                 int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
post_type          literal     Yes            Yes (default)  Yes (default)  Yes
post_status        literal     Yes            Yes (default)  Yes (default)  Yes
post_format        literal     Yes            Yes (default)  Yes (default)  Yes
post_title         text        No             Yes            Yes            Yes
post_content       text        No             Yes            Yes            No
post_excerpt       text        No             Yes            Yes            No
post_url           text        No             No             Yes            No
post_image         text        No             No             Yes            No
post_date          int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
post_date_gmt      int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
post_modified      int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
post_modified_gmt  int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
post_author        int         Yes (default)  Yes (default)  Yes (default)  Yes (default)
post_author_name   text        No             Yes            Yes            Yes
post_extra         text        No             No             Yes            No
category           text-array  Yes (default)  Yes            Yes            No
tag                text-array  Yes (default)  Yes            Yes            No
</pre>
                </li>
                <li><?php _e( 'to maintain a CloudSearch pattern rule, every "-" character in a custom field/taxonomy slug is replaced with "_"', ACS::PREFIX ) ?></li>
                <li><?php _e( 'to increase information in the taxonomies instead of save only a ID or a name, the plugin use a "separator" syntax for append both the ID both the taxonomy name (e.g. "789|My category")', ACS::PREFIX ) ?></li>
                <li><?php _e( 'the "Lucene" parser is used by default because this parser is very powerful for customized search query (using extra parameters) and for querying the schema', ACS::PREFIX ) ?></li>
            </ul>

			<a id="acs_help_how_plugin_works_frontend_results_and_filters"><h5><?php _e( 'Frontend results and filters', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'The front page uses the provided content box type to show a list of results. The page is completely asynchronous to speed up the page\'s rendition. On top of results you can find some filters. With these options you can filter results by post type and choose a different sort field (very useful to filter and expose relevant content). By default, sorting works with the value provided in the "Results settings" admin page. If you are using some custom post types, after you have stored them into the index, when you try a search they will be shown according to the "Content box type" value. Pay attention that, when using "default" or "plugin" content box, some fields of your custom types could be not properly shown due to "not-standard" usage. It\'s recommended to create different content boxes and work with the "Formats and types based" type. In this way you can separate boxes, one for the standard posts and one for every new custom post type (e.g. use "content.php" for standard posts and "content-yourtype.php" for new types).', ACS::PREFIX ) ?></p>

            <a id="acs_help_how_plugin_works_wordpress_admin_section"><h5><?php _e( 'WordPress admin section', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'You can find the plugin dashboard under the a WordPress admin\'s menu with name of "CloudSearch". The plugin is composed by 4 main admin sections. The first two are the "Help" and the "Documentation" sections that are very useful to understand how plugin works and in which way you can customize the plugin behavior. In addition to these sections there are a "Setting" section that gives you the opportunity, after a initial setup, of customize the plugin. In the end, the "Manage" section is very important to check the index status, the searchable documents and above all for create, update, sync and delete data from your index.', ACS::PREFIX ) ?></p>
			<?php //_e( '<p>The plugin is composed by 5 main admin sections. The first two are the "Help" and the "Documentation" sections that are very useful to understand how plugin works and in which way you can customize the plugin behavior. In addition to these sections there are a "Setting" section that gives you the opportunity, after a initial setup, of customize the plugin. In the end, the last two sections are used for the daily plugin management. A "Search" section where you can query the CloudSearch and view the results directly in your WordPress administrator panel and a "Manage" section very important to check the index status, the searchable documents and above all for create, sync and delete data from your index.</p>', ACS::PREFIX ) ?>
            <div class="acs_help_content_list_bottom_spacer"><?php _e( 'The index status could be:', ACS::PREFIX) ?></div>
			<ul class="acs_help_content_list">
				<li><i><?php _e( 'Running (green)', ACS::PREFIX) ?></i>: <?php _e( 'the index is active and running.', ACS::PREFIX) ?></li>
				<li><i><?php _e( 'Processing (yellow)', ACS::PREFIX) ?></i>: <?php _e( 'the index is in a state that prevents new documents from being synced or some operation are in progress.', ACS::PREFIX) ?></li>
				<li><i><?php _e( 'Domain needs indexing (red)', ACS::PREFIX) ?></i>: <?php _e( 'the index is in an invalid state. Need an indexing.', ACS::PREFIX) ?></li>
			</ul>

            <a id="acs_help_how_plugin_works_customize_your_pages"><h5><?php _e( 'Customize your pages', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'After installation and configuration, the plugin will use by default a built-in search template to provide CloudSearch results with no other customization needed, however there are different ways to customize your page. More informations about these features are detailed in the documentation page. The plugin provide an initial configuration that gives you a basic setting. If you want to alter this basic configurations, especially when using custom post types, post formats and custom fields/taxonomies, you can create your custom content boxes and change the content box type in the "Frontpage settings", you can create your search page and choose to not use the built-in search page using for example a different Javascript library (e.g. AngularJS or other frameworks instead of jQuery). These operations are suggested if you are using a custom theme and if you want to show more details or if you want a "not-standard" behavior in the result boxes.', ACS::PREFIX ) ?></p>

			<a id="acs_help_how_plugin_works_sync_operations"><h5><?php _e( 'Sync operations', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p>
                <?php _e( 'As told before, the sync operation is thrown when a post/term is saved. In the "Manage" section you can start a sync or a delete task when you want. These operations are completely asynchronous and work like a batch scripts using chunks. When you start a sync (for deletion is the same process), the plugin registers a batch start, splits the documents to synchronize in subsets (chunk) to better manage asynchronous calls and at predetermined intervals sends data to the index until no chunks remains to be synchronized, then register a batch end. If you close the browser or you change the page, the plugin registers a batch pause and automatically resumes process when you come back to the "Manage" section again. In the "Manage" section you can see if a process is pending and how many documents are already synchronized. Pay attention that you cannot have more the one operation running at the same time, you have to wait an operation\'s end before launch a new one. The processes may take several minutes. Until processing completes, searches may return obsolete data. Anyway, it could be a little cache reading new CloudSearch data due to propagation time on AWS system.', ACS::PREFIX ) ?><br /><br />
                <i><?php _e( 'Unfortunately, CloudSearch does not currently support nested JSON (object arrays).', ACS::PREFIX ) ?></i>
            </p>

            <a id="acs_help_how_plugin_works_manage_languages"><h5><?php _e( 'Manage languages', ACS::PREFIX ) ?></h5><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p>
                <?php _e( 'You can choose the Analysis schema language for the CloudSearch index in three ways:', ACS::PREFIX ) ?>
                <ol class="acs_help_content_list">
                    <li><?php _e( 'Use a constant: define in your "wp-config.php" the constant "WP_ACS_SCHEMA_LANGUAGE" with the desired language', ACS::PREFIX ) ?></li>
                    <li><?php _e( 'Use the WordPress locale: if no constant is defined the plugin takes automatically the same language of your WordPress installation', ACS::PREFIX ) ?></li>
                    <li><?php _e( 'Use defaults: as last option, the plugin use a "Multi-language" setting as default', ACS::PREFIX ) ?></li>
                </ol>
			    <?php _e( 'You can find the list of all the available languages for CloudSearch', ACS::PREFIX ) ?>
                <a href="https://docs.aws.amazon.com/cloudsearch/latest/developerguide/text-processing.html" target="_blank" title="<?php _e( 'Text Processing in Amazon CloudSearch', ACS::PREFIX ) ?>"><?php _e( 'here', ACS::PREFIX ) ?></a>.
            </p>

            <a id="acs_help_api"><h4><?php _e( 'API', ACS::PREFIX ) ?></h4><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'The plugin use the built in AJAX WordPress functionality to retrieve asynchronous informations. You can call an API using the "/wp-admin/admin-ajax.php" page with one of the following "action":', ACS::PREFIX ) ?></p>
			<ul class="acs_help_content_list">
				<li>acs_api_search</li>
				<li>acs_api_search_full</li>
				<li>acs_api_index_searchable_documents</li>
				<li>acs_api_index_site_documents</li>
				<li>acs_api_index_fields</li>
				<li>acs_api_index_status</li>
			</ul>
			<p>
                <?php _e( 'The last 4 services are used only by the admin pages and you need a nonce token to get a response. The first one (as the second) is usable from everyone (do not need a nonce token) and gives you the ability to perform a query into CloudSearch. This service needs 3 parameters, a "keyword" with the searching word, a "start" and a "size" used for pagination and "load more" functionalities and gives back the list of objects representing the founding documents. You can get the API result in two formats: JSON and XML. The default one is JSON, but simply adding a "format" parameter in the API url you can switch the result output. Further information in the documentation section.', ACS::PREFIX ) ?><br />
				<?php _e( 'By querying the first two API you can retrieve facet information about your data.', ACS::PREFIX) ?>
            </p>

			<a id="acs_help_faq"><h4><?php _e( 'FAQ', ACS::PREFIX ) ?></h4><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p>
                <i><strong><?php _e( 'Works on multisite?', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'By default, search does not works on multisites. The basic search API looks only in the current site. If you want to search also in other sites you need to customize the page script for use the full search API version. This API searches in all sites and gives back to you the detailed results instead of a simple list of ID.', ACS::PREFIX ) ?>
            </p>
			<p>
                <i><strong><?php _e( 'Works with multi language?', ACS::PREFIX ) ?></strong></i><br />
                <?php _e( 'The plugin does not work with other languages plugins. However, there is a alternative and simple way to work with other languages. The best solution is to add custom fields containing language values into your index (e.g. titles, excerpts, contents and all the other textual fields) and a field that tells you if the post is translated. Then, customize the front page JavaScript adding an extra query filter (e.g. on "is_translated" field) at the default search key (see more details in the documentation page). Finally, you can manage in your custom content box what are the correct language fields to show (e.g. showing the english or the italian text depending on the language provided in the search query).', ACS::PREFIX ) ?>
            </p>
            <p>
                <i><strong><?php _e( 'Supports facets?', ACS::PREFIX ) ?></strong></i><br />
				<?php _e( 'Yes, you can use API for retrieve facet information.', ACS::PREFIX ) ?>
            </p>

            <a id="acs_help_references"><h4><?php _e( 'References', ACS::PREFIX ) ?></h4><span class="dashicons dashicons-arrow-up acs_to_top"></span></a>
            <p><?php _e( 'Here you can find a list of utilities and references:', ACS::PREFIX ) ?></p>
			<ul class="acs_help_content_list">
				<li><a href="http://aws.amazon.com/what-is-aws/" target="_blank" title="<?php _e( 'What is AWS', ACS::PREFIX ) ?>"><?php _e( 'What is AWS', ACS::PREFIX ) ?></a></li>
				<li><a href="http://aws.amazon.com/cloudsearch/" target="_blank" title="<?php _e( 'What is Amazon CloudSearch', ACS::PREFIX ) ?>">What is Amazon CloudSearch</a></li>
				<li><a href="http://aws.amazon.com/cloudsearch/pricing/" target="_blank" title="<?php _e( 'Amazon CloudSearch pricing', ACS::PREFIX ) ?>">Amazon CloudSearch pricing</a></li>
                <li><a href="http://docs.aws.amazon.com/cloudsearch/latest/developerguide/weighting-fields.html" target="_blank" title="<?php _e( 'Amazon CloudSearch weighting fields', ACS::PREFIX ) ?>">Amazon CloudSearch weighting fields</a></li>
                <li><a href="https://docs.aws.amazon.com/cloudsearch/latest/developerguide/text-processing.html" target="_blank" title="<?php _e( 'Text Processing in Amazon CloudSearch', ACS::PREFIX ) ?>"><?php _e( 'Text Processing in Amazon CloudSearch', ACS::PREFIX ) ?></a></li>
			</ul>

        </div>

    </div>
    <?php
}
