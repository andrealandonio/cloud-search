<?php
/**
 * Render admin menu docs page
 */
function acs_menu_page_docs() {
    // Detect current tab
    if ( isset ( $_GET['tab'] ) ) {
        $current = $_GET['tab'];
    }
    else {
        $current = 'general';
    }

    // Create tab bar
	$tabs = array(
        'general' => __( 'General', ACS::PREFIX ),
        'css' => __( 'CSS Styles', ACS::PREFIX ),
        'api' => __( 'Works with API', ACS::PREFIX ) . '&nbsp;' . '<span class="dashicons dashicons-megaphone"></span>',
        'query' => __( 'Querying the index', ACS::PREFIX ),
        'facets' => __( 'Facets', ACS::PREFIX ),
        'utilities' => __( 'Utilities', ACS::PREFIX ),
        'filters' => __( 'Filters', ACS::PREFIX )//,
        //'cli' => __( 'CLI', ACS::PREFIX ) . '&nbsp;' . '<span class="dashicons dashicons-laptop"></span>'
    );
    ?>
    <div class="wrap">
        <h2><?php _e( 'Docs', ACS::PREFIX ) ?></h2>

        <p>
            <?php _e( 'In this page you can read about the plugin documentation. In the docs you can find many detailed informations that explains you how to customize plugin CSS, API and much more.', ACS::PREFIX ) ?>
        </p>

        <div id="icon-themes" class="icon32"><br></div>
        <h2 class="nav-tab-wrapper">
            <?php
            foreach( $tabs as $tab => $name ) {
                $class = ( $tab == $current ) ? ' nav-tab-active' : '';
                ?><a class="nav-tab<?php echo $class ?>" href="?page=acs_menu_docs&tab=<?php echo $tab ?>"><?php echo $name ?></a><?php
            }
            ?>
        </h2>

        <form method="post" action="<?php admin_url( 'admin.php?page=acs_menu_docs' ); ?>">
            <div class="form-table">
                <?php
                wp_nonce_field( 'acs_menu_docs' );

                // Detect current tab
                if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab'];
                else $tab = 'general';

                switch ( $tab ) {
	                case 'cli' :
		                ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Overview', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'The plugin adds some basic WP CLI commands. The main advantage of the CLI is that you can run a query/sync/index without any AWS execution time limits that you could find using the admin pages.', ACS::PREFIX ) ?></p>
                            <p><?php _e( 'Here you can find a list of available commands:', ACS::PREFIX ) ?></p>

                            <span>wp cloudsearch create_index</span>
                            <p><?php _e( 'Perform a create/update index operation', ACS::PREFIX ) ?></p>

                            <span>wp cloudsearch run_indexing</span>
                            <p><?php _e( 'Perform an indexing operation (works only if no other index process are running and if an indexing is really needed)', ACS::PREFIX ) ?></p>

                            <span>wp cloudsearch sync_documents</span>
                            <p><?php _e( 'Perform a documents sync operation', ACS::PREFIX ) ?></p>

                            <span>wp cloudsearch delete_documents</span>
                            <p><?php _e( 'Perform a documents delete operation', ACS::PREFIX ) ?></p>
                        </div>
		                <?php
		                break;
	                case 'filters' :
		                ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Add allowed statuses in post transition', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'By default, a post is indexed only when published. If you want to index posts that are in other statuses (e.g. "future") like when published you can override the default behavior with a filter hook that tells the plugin which statuses are allowed.', ACS::PREFIX ) ?></p>

                            <pre>
                                /**
                                 * Enable post transition allowed statuses for "publish" and "future" posts
                                 */
                                function manage_post_transition_allowed_statuses( $array ) {
                                &nbsp;&nbsp;return array('publish', 'future');
                                };
                                add_filter( 'acs_post_transition_allowed_statuses', 'manage_post_transition_allowed_statuses', 10, 1 );
                            </pre>

                            <h4><?php _e( 'Add filter query conditions', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'Using this filter you can add some filter query conditions for filtering additional fields not covered in configuration.', ACS::PREFIX ) ?></p>
                            acs_add_filter_query_conditions
                            <pre>
                                /**
                                 * Add filter query conditions to search
                                 */
                                function add_filter_query_conditions( $extra_conditions ) {
                                &nbsp;&nbsp;$extra_conditions = "(not my_custom_field:'my-custom-value')";
                                &nbsp;&nbsp;return $extra_conditions;
                                };
                                add_filter( 'acs_add_filter_query_conditions', 'add_filter_query_conditions', 10, 1 );
                            </pre>

                            <h4><?php _e( 'Enabling filters for WooCommerce product tags and EDD tags', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'If you want to enable plugin filters for WooCommerce product tags and EDD tags (not categories), just put the following code in a custom plugin or in the functions.php of your theme.', ACS::PREFIX ) ?></p>

                            <pre>
                                /**
                                 * Hierarchical support for EDD tags
                                 */
                                function my_edd_taxonomy_args_product_tag( $array ) {
                                &nbsp;&nbsp;$array[ 'hierarchical' ] = true;
                                &nbsp;&nbsp;return $array;
                                };
                                add_filter( 'edd_download_tag_args', 'my_edd_taxonomy_args_product_tag', 10, 1 );
                            </pre>

                            <pre>
                                /**
                                 * Hierarchical support for WooCommerce product tags
                                 */
                                function my_woocommerce_taxonomy_args_product_tag( $array ) {
                                &nbsp;&nbsp;$array[ 'hierarchical' ] = true;
                                &nbsp;&nbsp;return $array;
                                };
                                add_filter( 'woocommerce_taxonomy_args_product_tag', 'my_woocommerce_taxonomy_args_product_tag', 10, 1 );
                            </pre>

                            <h4><?php _e( 'Modify search arguments', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'Using the "acs_search_args" filter hook you can programmatically modify (if you need to define a custom expression on the fly when certain conditions are met) the "$search_args" right before the query is sent to Amazon.', ACS::PREFIX ) ?></p>
                            
                        </div>
		                <?php
		                break;
                    case 'utilities' :
                        ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Index posts/pages exclusions', ACS::PREFIX ) ?></h4>
                            <p>
		                        <?php _e( 'If you want to exclude posts or pages from search results (eg: "contacts" page, "thank you" page, etc) you can add a simple custom field to post/page. In this way you can exclude them permanently so when you go to sync the index or update a post/page, the excluded posts are not indexed.', ACS::PREFIX ) ?><br />
		                        <?php _e( 'The custom field should be named "acs_exclude" with a value "1".', ACS::PREFIX ) ?>
                            </p>

                            <h4><?php _e( 'Override search template', ACS::PREFIX ) ?></h4>
                            <p>
		                        <?php _e( 'If you want to override search template maintaining the "Use plugin search page" functionality you only need to create a new template file named "cloud-search-template.php" under a "templates" folder in your theme. In this way you can customize the search page container template (not the single result items).', ACS::PREFIX ) ?><br />
                            </p>

                            <h4><?php _e( 'Some functions', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'Here you can find a simple list of utility functions that you can use in your custom templates.', ACS::PREFIX ) ?></p>

                            <span>acs_trunc_by_chars( $phrase, $max )</span>
                            <p><?php _e( 'Truncates a phrase according to given max chars.', ACS::PREFIX ) ?></p>

                            <span>acs_trunc_by_words( $phrase, $max )</span>
                            <p><?php _e( 'Truncates a phrase according to given max words.', ACS::PREFIX ) ?></p>

                            <span>acs_truncate( $text )</span>
                            <p><?php _e( 'Truncates a text according to plugin settings (number of chars/words that you have configured in the settings page).', ACS::PREFIX ) ?></p>

                            <span>acs_highlight_text( $text, $key )</span>
                            <p><?php _e( 'Highlights the given text according to plugin settings (highlight settings that you have configured in the settings page).', ACS::PREFIX ) ?></p>

                            <span>acs_highlight_title( $title, $key )</span>
                            <p><?php _e( 'Highlights the given title according to plugin settings (highlight settings that you have configured in the settings page).', ACS::PREFIX ) ?></p>

                            <h4><?php _e( 'Development', ACS::PREFIX ) ?></h4>
                            <p>
                                <?php _e( 'If you want to compile JS and CSS on your local machine, some Grunt tasks are available. First of all, install Grunt and dependencies using "npm install". The available tasks are:', ACS::PREFIX ) ?>
                            </p>
                            <ul class="acs_docs_content_list">
                                <li><i>grunt check</i>: check if JS files are valid</li>
                                <li><i>grunt clean</i>: clean dist folders</li>
                                <li><i>grunt build</i>: compile JS and CSS files into dist folders</li>
                            </ul>
                        </div>
                        <?php
                        break;
	                case 'facets' :
		                ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Overview', ACS::PREFIX ) ?></h4>
                            <p>
				                <?php _e( 'A facet is an index field that represents a category that you want to use to refine and filter search results.', ACS::PREFIX ) ?><br />
				                <?php _e( 'You can retrieve facet information in two ways:', ACS::PREFIX ) ?>
                            </p>
                            <ul class="acs_docs_content_list">
                                <li><i>sort</i>: <?php _e( 'Returns facet information sorted either by facet counts or facet values.', ACS::PREFIX ) ?></li>
                                <li><i>buckets</i>: <?php _e( 'Returns facet information for particular facet values or ranges.', ACS::PREFIX ) ?></li>
                            </ul>

                            <h4><?php _e( 'How use it', ACS::PREFIX ) ?></h4>
                            <p>
                                <?php _e( 'You can get facet information for any facet-enabled field by specifying the facet.FIELD parameter in an API search request. By default, Amazon CloudSearch returns facet counts for the top 10 values.', ACS::PREFIX ) ?><br />
	                            <?php _e( 'You can specify facet options to control the sorting of the facet values for each field, limit the number of facet values returned, or choose what facet values to count and return.', ACS::PREFIX ) ?>
                            </p>

                            <h4><?php _e( 'An example', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'Here an example for retrieving facet information about fields "post_type", "post_format" and "post_status". The API request URL should be:', ACS::PREFIX ) ?></p>
                            <span>/wp-admin/admin-ajax.php?action=acs_api_search_full&keyword=lorem&format=json&facet.post_type={"sort":"count","size":5}&facet.post_format={}&facet.post_status={"buckets":["publish","future"]}</span>

                            <p><?php _e( 'Keep in mind that facets works only with API. In other words, the above URL is composed by the parameters:', ACS::PREFIX ) ?></p>
                            <ul class="acs_docs_content_list">
                                <li>keyword=lorem</li>
                                <li>format=json</li>
                                <li>facet.post_type={"sort":"count","size":5}</li>
                                <li>facet.post_format={}</li>
                                <li>facet.post_status={"buckets":["publish","future"]}</li>
                            </ul>

                            <p><?php _e( 'According to the "format" parameter you can get an XML or JSON response that looks like the following code:', ACS::PREFIX ) ?></p>
                            <pre>
                                &lt;response&gt;
                                &nbsp;&nbsp;&lt;data&gt; ... &lt;/data&gt;
                                &nbsp;&nbsp;&lt;facets&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&lt;facet name="post_status"&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;bucket value="publish" count="7"/&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;bucket value="future" count="0"/&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&lt;/facet&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&lt;facet name="post_type"&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;bucket value="post" count="6"/&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;bucket value="page" count="1"/&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&lt;/facet&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&lt;facet name="post_format"&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;bucket value="aside" count="1"/&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;bucket value="gallery" count="5"/&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&lt;bucket value="video" count="1"/&gt;
                                &nbsp;&nbsp;&nbsp;&nbsp;&lt;/facet&gt;
                                &nbsp;&nbsp;&lt;/facets&gt;
                                &lt;/response&gt;
                            </pre>

                            <pre>
                                {
                                &nbsp;&nbsp;items: [ ... ],
                                &nbsp;&nbsp;facets: {
                                &nbsp;&nbsp;&nbsp;&nbsp;post_status: {
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;buckets: [
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value: "publish",
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;count: 7
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value: "future",
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;count: 0
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                                &nbsp;&nbsp;&nbsp;&nbsp;},
                                &nbsp;&nbsp;&nbsp;&nbsp;post_type: {
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;buckets: [
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value: "post",
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;count: 6
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value: "page",
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;count: 1
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                                &nbsp;&nbsp;&nbsp;&nbsp;},
                                &nbsp;&nbsp;&nbsp;&nbsp;post_format: {
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;buckets: [
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value: "aside",
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;count: 1
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value: "gallery",
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;count: 5
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;value: "video",
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;count: 1
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]
                                &nbsp;&nbsp;&nbsp;&nbsp;}
                                &nbsp;&nbsp;}
                                }
                            </pre>

                            <p>
	                            <?php _e( 'Note that if no facets parameters are provided the response JSON is composed only by the array of items/results without the two fields "items" and "facets".', ACS::PREFIX ) ?><br />
                                <?php _e( 'Reading the facets information in the response, you can refine search query adding a filter query on some fields. For example, the API request URL can be modified to filter only "page" post types.', ACS::PREFIX ) ?>
                            </p>
                            <span>/wp-admin/admin-ajax.php?action=acs_api_search_full&keyword=lorem&format=json&facet.post_type={"sort":"count","size":5}&facet.post_format={}&facet.post_status={"buckets":["publish","future"]}&filter_query=post_type:'page'</span>

                            <h4><?php _e( 'References', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'Here you can find a list of utilities and references:', ACS::PREFIX ) ?></p>
                            <ul class="acs_docs_content_list">
                                <li><a href="http://docs.aws.amazon.com/cloudsearch/latest/developerguide/faceting.html" target="_blank" title="<?php _e( 'Faceting', ACS::PREFIX ) ?>"><?php _e( 'Faceting', ACS::PREFIX ) ?></a></li>
                            </ul>
                        </div>
		                <?php
		                break;
                    case 'query' :
                        ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Overview', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'The plugin use the Lucene query parser as default parser for every CloudSearch search because is a very popular search engine library, with an active community and a host of real world implementations. Lucene has a custom query syntax for querying its indexes. Here are some query examples demonstrating the query syntax.', ACS::PREFIX ) ?></p>

                            <span>post_title:foo</span>
                            <p><?php _e( 'Search for word "foo" in the post title field.', ACS::PREFIX ) ?></p>

                            <span>post_title:"foo bar"</span>
                            <p><?php _e( 'Search for phrase "foo bar" in the post title field.', ACS::PREFIX ) ?></p>

                            <span>post_title:"foo bar" AND post_content:"quick fox"</span>
                            <p><?php _e( 'Search for phrase "foo bar" in the post title field AND the phrase "quick fox" in the post content field.', ACS::PREFIX ) ?></p>

                            <span>(post_title:"foo bar" AND post_content:"quick fox") OR post_title:fox</span>
                            <p><?php _e( 'Search for either the phrase "foo bar" in the post title field AND the phrase "quick fox" in the post content field, or the word "fox" in the post title field.', ACS::PREFIX ) ?></p>

                            <span>post_title:foo -post_title:bar</span>
                            <p><?php _e( 'Search for word "foo" and not "bar" in the post title field.', ACS::PREFIX ) ?></p>

                            <span>post_title:foo*</span>
                            <p><?php _e( 'Search for any word that starts with "foo" in the post title field.', ACS::PREFIX ) ?></p>

                            <span>post_title:foo*bar</span>
                            <p>
                                <?php _e( 'Search for any word that starts with "foo" and ends with "bar" in the post title field.', ACS::PREFIX ) ?><br />
                                <?php _e( 'Note that Lucene does not support using a * symbol as the first character of a search.', ACS::PREFIX ) ?>
                            </p>

                            <span>post_date:[1452816082 TO 1452817029]</span>
                            <p>
                                <?php _e( 'Search for any values between the lower and upper bound specified by the Range Query in the post date field. Range Queries can be inclusive or exclusive of the upper and lower bounds. Sorting is done lexicographically.', ACS::PREFIX ) ?><br />
                                <?php _e( 'Note that performing range queries on numbers could be very heavy for the index.', ACS::PREFIX ) ?>
                            </p>

                            <p>
                                <?php _e( 'An example of API request for searching for any word that starts with "foo" and ends with "bar" in the post title field could be (for demo we are limiting the results to 20 items):', ACS::PREFIX ) ?><br />
                                <i>/wp-admin/admin-ajax.php?action=acs_api_search&keyword=post_title:foo*bar&start=0&size=20</i>
                            </p>

                            <h4><?php _e( 'Use filter queries', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'By default, the plugin searches your keyword in all the index fields, using frontend filters you can (for now) filter results by post type. If you want to filter your results on a specific field you have to use the syntax explained above and use a composed keyword, such as "post_title:foo" for search the word "foo" in the post title field. In addition, you can add other filters using a custom script and making an Ajax call passing the "filter_query" parameter with your desired filters, for example with a filter like "post_format:video" you can get only video posts. Keep in mind that the filter query is added with an AND condition to the default filter query.', ACS::PREFIX ) ?></p>

                            <h4><?php _e( 'More about site and blog ID', ACS::PREFIX ) ?></h4>
                            <p>
                                <?php _e( 'As already explained in the help page, the plugin uses site_id and blog_id to better works with multisite features. Every item is stored in the index with a unique key composed by the current site, the current blog and the post ID. In this way no duplicate can be saved in the index. By default all the queries performed with the standard "acs_api_search" API filter the result in the same site/blog, instead, using the "acs_api_search_full" API your results are searched in all the site/blog existing in the index. ', ACS::PREFIX ) ?><br />
                                <?php _e( 'In conclusion, if you need to search in a single site (probably a blog in a single site installation) you should use the standard API with the defaults plugin templates. If you need to search data in a network of multiple sites you should use full API customizing the scripts (for the API call) and the templates.', ACS::PREFIX ) ?>
                            </p>
                        </div>
                        <?php
                        break;
                    case 'api' :
                        ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Overview', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'The plugin provides to the user a set of APIs with which you can check the status of the index, verify the number of indexed documents and query the index to search for items. You can get the API result in two formats: JSON and XML. The default one is JSON, but simply adding a "format" parameter in the API url you can switch the result output. Below we analyze every single API.', ACS::PREFIX ) ?></p>

                            <span>/wp-admin/admin-ajax.php?action=acs_api_search</span>
                            <p><?php _e( 'With this API you can query the index and get a list of IDs of the founded posts. This service read 4 parameters: "keyword" representing the search key (the only mandatory field), "start" and "size" representing the pagination values and "filter_query" to specify an extra filter query. Pay attention that this service works only with current site, if you need to search items in all the sites you have to use the next API.', ACS::PREFIX ) ?></p>

                            <span>/wp-admin/admin-ajax.php?action=acs_api_search_full</span>
                            <p>
                                <?php _e( 'This service is similar to the previous explained, reads the same parameters but instead of search only in current site, search data in all sites and return a list of objects representing every indexed item. This service returns to you all the schema fields and should be used if your search page have to show data from different sources.', ACS::PREFIX ) ?>
                                <?php _e( 'Here a basic example for "acs_api_search_full" API:', ACS::PREFIX ) ?><br />
                                <i>http://www.yourdomain.com/wp-admin/admin-ajax.php?action=acs_api_search_full&keyword=example&start=0&size=100&type_field=all&sort_field=post_date_gmt&sort_order=desc</i>
                            </p>

                            <span>/wp-admin/admin-ajax.php?action=acs_api_index_searchable_documents</span>
                            <p><?php _e( 'This service provides to you the number of items in the index. In other words, the number of items that you can search using the search full API.', ACS::PREFIX ) ?></p>

                            <span>/wp-admin/admin-ajax.php?action=acs_api_index_site_documents</span>
                            <p><?php _e( 'This service provides to you the number of items in the site. In other words, the number of items that you can search using the search API.', ACS::PREFIX ) ?></p>

                            <span>/wp-admin/admin-ajax.php?action=acs_api_index_fields</span>
                            <p><?php _e( 'This service provides to you the number of fields configured in the index.', ACS::PREFIX ) ?></p>

                            <span>/wp-admin/admin-ajax.php?action=acs_api_index_status</span>
                            <p><?php _e( 'This service provides to you the index status. Especially if the index requires an indexing or if a sync/indexing operation is in progress.', ACS::PREFIX ) ?></p>

                            <span>/wp-json/cloud-search/v1/suggest?keyword=test</span>
                            <p><?php _e( 'This service provides to you a list of suggested items, retrieved from the provided input keyword. This API is also used for the suggestion feature in the templates.', ACS::PREFIX ) ?></p>

                            <span>/wp-json/cloud-search/v1/results?keyword=test&start=0&size=10</span>
                            <p><?php _e( 'This service provides to you a way for searching items, retrieved from the provided input keyword.', ACS::PREFIX ) ?></p>

                            <h4><?php _e( 'Work with AMP', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'AMP pages actually supports autocomplete, but need that the results array have to be in a different format. A top level key as "items" is needed, otherwise with "results" as top level key it doesn\'t work. For enabling AMP new top level key within results you have to provide an "amp" parameter to the API.', ACS::PREFIX ) ?></p>

                            <h4><?php _e( 'How to write your custom script', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'The built-in search page uses jQuery to initialize search engine. As you can see in the following code, the page provides some values to a ACS init function that manages the bootstrap and manages every search and pagination features. The "init" function parameters are: "keyword", "start and "size" already explained in the "acs_api_search" API, "type_field", "sort_field" and "sort_order" that represent the filter element IDs and "container_result_items", "container_ajax_loader" and "container_load_more" that represent the element IDs where you want to put the result items, the page loader and the load more message.', ACS::PREFIX ) ?></p>

                            <pre>
                                &lt;script type="text/javascript"&gt;
                                    jQuery(document).ready(function() {
                                    &nbsp;&nbsp;ACS.init({
                                    &nbsp;&nbsp;&nbsp;&nbsp;keyword: '&lt;?php echo get_search_query() ?&gt;',
                                    &nbsp;&nbsp;&nbsp;&nbsp;start: 0,
                                    &nbsp;&nbsp;&nbsp;&nbsp;size: &lt;?php echo ACS::get_instance()->get_settings()->acs_results_max_items ?&gt;,
                                    &nbsp;&nbsp;&nbsp;&nbsp;type_field: '#acs_filter_type_field',
                                    &nbsp;&nbsp;&nbsp;&nbsp;sort_field: '#acs_filter_sort_field',
                                    &nbsp;&nbsp;&nbsp;&nbsp;sort_order: '#acs_filter_sort_order',
                                    &nbsp;&nbsp;&nbsp;&nbsp;container_result_items: '#acs_search_results_items',
                                    &nbsp;&nbsp;&nbsp;&nbsp;container_ajax_loader: '#acs_search_results_container .ajax-loader',
                                    &nbsp;&nbsp;&nbsp;&nbsp;container_load_more: '#acs_search_results_container .load-more'
                                    &nbsp;});
                                    });
                                &lt;/script&gt;
                            </pre>

                            <p><?php _e( 'If you do not want to use jQuery or you need to change the script behavior, you can try to write your custom script code (with your preferred library or framework) and could be similar to:', ACS::PREFIX ) ?></p>

                            <pre>
                                jQuery.ajax({
                                &nbsp;&nbsp;url: '/wp-admin/admin-ajax.php',
                                &nbsp;&nbsp;type: 'get',
                                &nbsp;&nbsp;dataType: 'json',
                                &nbsp;&nbsp;data: {
                                &nbsp;&nbsp;&nbsp;&nbsp;action: 'acs_search_documents_full',
                                &nbsp;&nbsp;&nbsp;&nbsp;keyword: 'your search keyword',
                                &nbsp;&nbsp;&nbsp;&nbsp;start: 'your search starting point',
                                &nbsp;&nbsp;&nbsp;&nbsp;size: 'your search result items',
                                &nbsp;&nbsp;&nbsp;&nbsp;filter_query: 'your search filter query',
                                &nbsp;&nbsp;&nbsp;&nbsp;type_field: 'your search type field',
                                &nbsp;&nbsp;&nbsp;&nbsp;sort_field: 'your search sort field or an array of sort fields (eg: ["_score", "post_date"])',
                                &nbsp;&nbsp;&nbsp;&nbsp;sort_order: 'your search sort order or an array of sort orders (eg: ["asc", "desc"])',
                                &nbsp;&nbsp;&nbsp;&nbsp;extras: {
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;custom_data_1: 'your search extra information',
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;custom_data_2: 'your search extra information'
                                &nbsp;&nbsp;&nbsp;&nbsp;}
                                &nbsp;&nbsp;},
                                &nbsp;&nbsp;success: function (result) {
                                &nbsp;&nbsp;&nbsp;&nbsp;// Manage AJAX loader (hide or show)
                                &nbsp;&nbsp;&nbsp;&nbsp;// Show no result or display items, then the load more
                                &nbsp;&nbsp;&nbsp;&nbsp;// Update starting point for pagination
                                &nbsp;&nbsp;&nbsp;&nbsp;...
                                &nbsp;&nbsp;}
                                });
                            </pre>

                            <p><?php _e( 'Where "type_field" could be "all" for searching in all the fields or a specific field name, "sort_field" could be "_score", "post_date_gmt", "post_modified_gmt" or "id" for sorting results by relevance, by published date, by modified date or by ID, "sort_order" could "desc" or "asc" for view results in descending or ascending order and "extras" is an array that could contain a list of attributes that can be provided as input and get back (without any variation) as output from the service. This is useful to pass parameters through service and templates (eg: search content templates could not read global variables (different scopes), with this parameter you can pass data from search page to every single result item template). If you want to sort data on more than one field you can provide an array of values for "sort_field" and "sort_order" fields. Only if the 2 arrays have the same number of items the sort clause will be composed with multiple values.', ACS::PREFIX ) ?></p>
                            <p><?php _e( 'The API response looks like (JSON format):', ACS::PREFIX ) ?></p>

                            <pre>
                                {
                                &nbsp;&nbsp;code: "ok",
                                &nbsp;&nbsp;actions: [
                                &nbsp;&nbsp;&nbsp;&nbsp;"search_documents"
                                &nbsp;&nbsp;],
                                &nbsp;&nbsp;message: "ok",
                                &nbsp;&nbsp;data: {
                                &nbsp;&nbsp;&nbsp;&nbsp;items: "...",
                                &nbsp;&nbsp;&nbsp;&nbsp;start: 0,
                                &nbsp;&nbsp;&nbsp;&nbsp;found: 100,
                                &nbsp;&nbsp;&nbsp;&nbsp;message: "...",
                                &nbsp;&nbsp;&nbsp;&nbsp;load_more: true,
                                &nbsp;&nbsp;&nbsp;&nbsp;extras: "..."
                                &nbsp;&nbsp;}
                                }
                            </pre>

                            <p><?php _e( 'Where "code" and "message" represent a feedback of the operation (e.g. "ok" or "error"), "actions" contains the actions performed (in the example, only the "search_documents" operation), "data" contains an object of specific informations. These informations are: "items", "start", "found", "message" and "load_more". The first one contains the result items in HTML format (provided by the content box selected in the admin settings page), the other fields could be used for manage the pagination, the no result message and the load more feature.', ACS::PREFIX ) ?></p>

                            <h4><?php _e( 'What\'s new with terms?', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'Added support for terms, the APIs results change not returning an IDs array but an object array (with ID and type fields).', ACS::PREFIX ) ?></p>
                            <p><?php _e( 'The "acs_api_search" API JSON response change from an array of IDs', ACS::PREFIX ) ?>:</p>

                            <pre>
                                [
                                &nbsp;&nbsp;11,
                                &nbsp;&nbsp;9,
                                ]
                            </pre>

                            <p><?php _e( 'to an array of object composed by the entity ID and the entity type ("post" or "term")', ACS::PREFIX ) ?>:</p>

                            <pre>
                                [
                                &nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;id: 11,
                                &nbsp;&nbsp;&nbsp;&nbsp;type: "term",
                                &nbsp;&nbsp;},
                                &nbsp;&nbsp;{
                                &nbsp;&nbsp;&nbsp;&nbsp;id: 9,
                                &nbsp;&nbsp;&nbsp;&nbsp;type: "post",
                                &nbsp;&nbsp;}
                                ]
                            </pre>

                            <p><?php _e( 'The "acs_api_search_full" API JSON response does not change. It contains an array of object that represent the results.', ACS::PREFIX ) ?></p>
                        </div>
                        <?php
                        break;
                    case 'css' :
                        ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Overview', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'Generally all the classes of the plugin start with the "acs" prefix. You can override the plugin CSS rules adding to your theme\'s stylesheet the following rules, changing the default values:', ACS::PREFIX ) ?></p>

                            <p>
                                <span><?php _e( 'Front page header rules', ACS::PREFIX ) ?></span><br />
                                <?php _e( 'Changing this rules you can alter the search page header, especially, you can add custom rules for page title and subtitle. Usually the page title contains the phrase "You looked for" and the subtitle contains the result items counter.', ACS::PREFIX ) ?>
                            </p>
                            <pre>
                                .acs_search_header {}
                                .acs_search_header .acs_page_title {}
                                .acs_search_header .acs_page_subtitle {}
                            </pre>

                            <p>
                                <span><?php _e( 'Front page filters rules', ACS::PREFIX ) ?></span><br />
                                <?php _e( 'Changing this rules you can alter the search page filter section, you can change all about the filter dropdowns, the label style, the select input style and the entire box container.', ACS::PREFIX ) ?>
                            </p>
                            <pre>
                                .acs_search_results_filters {}
                                .acs_search_results_filters .acs_filters_box {}
                                .acs_search_results_filters .acs_filters_box.acs_filter_type {}
                                .acs_search_results_filters .acs_filters_box.acs_filter_sort {}
                                .acs_search_results_filters .acs_filters_label {}
                                select.acs_filters {}
                            </pre>

                            <p>
                                <span><?php _e( 'Front page results rules', ACS::PREFIX ) ?></span><br />
                                <?php _e( 'Changing this rules you can alter the search page results section, you can modify the "no results" and the "load more" containers, the result items image to best fit it into your theme and the entire box container.', ACS::PREFIX ) ?>
                            </p>
                            <pre>
                                .acs_search_results_container {}
                                .acs_search_results_container .acs_search_results_items {}
                                .acs_search_results_container .acs_search_results_items.no_results {}
                                .acs_search_results_container .acs_search_results_items .acs-image {}
                                .acs_search_results_container .acs_search_results_items .hentry.has-post-thumbnail:first-child {}
                                .acs_search_results_container .acs_search_results_status {}
                                .acs_search_results_container .acs_search_results_status .load_more {}
                            </pre>

                            <p>
                                <span><?php _e( 'Front page item result rules', ACS::PREFIX ) ?></span><br />
                                <?php _e( 'Changing this rules you can alter the search page item results layout. By default the plugin try to preserve the theme CSS rules, but overriding these rules you can modify every field in the container of a result item. You can modify the article container for post/term, the image, the header with the post title, the excerpt container and all the footer meta, such as the sticky flag, the post format, the post date/time, the author, the categories and terms list and the comment link boxes.', ACS::PREFIX ) ?>
                            </p>
                            <pre>
                                .acs_search_results_container .acs_search_results_items .post {}
                                .acs_search_results_container .acs_search_results_items .term {}
                                .acs_search_results_container .acs_search_results_items .acs-image {}
                                .acs_search_results_container .acs_search_results_items .entry-header {}
                                .acs_search_results_container .acs_search_results_items .entry-summary {}
                                .acs_search_results_container .acs_search_results_items .entry-meta .sticky-post {}
                                .acs_search_results_container .acs_search_results_items .entry-meta .entry-format {}
                                .acs_search_results_container .acs_search_results_items .entry-meta .posted-on {}
                                .acs_search_results_container .acs_search_results_items .entry-meta .author {}
                                .acs_search_results_container .acs_search_results_items .entry-meta .cat-links {}
                                .acs_search_results_container .acs_search_results_items .entry-meta .tags-links {}
                                .acs_search_results_container .acs_search_results_items .entry-meta .comments-link {}
                            </pre>

                            <p>
                                <span><?php _e( 'Suggestions', ACS::PREFIX ) ?></span><br />
                                <?php _e( 'In order to customize the suggestions look & feel you can, if you need a simple customization, use the plugin settings configuration and change colors and font size, otherwise if you need a deeper customization you can modify the following CSS rules (simply changing values and appending the "!important" clause).', ACS::PREFIX ) ?>
                            </p>
                            <pre>
                                /* Suggestion loading */
                                .acs_suggesting {
                                &nbsp;&nbsp;background: url('loading.gif') no-repeat 98% 50%;
                                }

                                /* Suggestion standard item */
                                .ui-menu .ui-menu-item {
                                &nbsp;&nbsp;font-size: 14px; (*)
                                &nbsp;&nbsp;font-weight: normal;
                                &nbsp;&nbsp;background: #FFFFFF; (*)
                                &nbsp;&nbsp;color: #333333; (*)
                                &nbsp;&nbsp;text-shadow: 0;
                                &nbsp;&nbsp;border: 0;
                                &nbsp;&nbsp;-moz-border-radius: 0;
                                &nbsp;&nbsp;-webkit-border-radius: 0;
                                &nbsp;&nbsp;border-radius: 0;
                                &nbsp;&nbsp;white-space: nowrap;
                                }

                                /* Suggestion focused item */
                                .ui-menu .ui-menu-item.ui-state-focus, .ui-menu .ui-menu-item.ui-state-hover, .ui-menu .ui-menu-item.ui-state-active {
                                &nbsp;&nbsp;background: #EFEFEF; (*)
                                &nbsp;&nbsp;color: #333333; (*)
                                &nbsp;&nbsp;margin: 0;
                                &nbsp;&nbsp;text-shadow: 0;
                                &nbsp;&nbsp;border: 0;
                                }
                            </pre>

                            <p>
                                <?php _e( 'Fields marked with (*) will be overridden with plugin settings (if configured).', ACS::PREFIX ) ?>
                            </p>
                        </div>
                        <?php
                        break;
                    default:
                        ?>
                        <div class="acs_docs_content">
                            <h4><?php _e( 'Customize load more with HTML', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'In the "Setting" section you have the opportunity to customize the "load more" message using a simple string, but you can also set an HTML snippet if you want to highly customize the "loader", for example with:', ACS::PREFIX ) ?></p>

                            <pre>
                                &lt;div class="my-custom-load-more"&gt;MORE&lt;/div&gt;

                                used in:

                                &lt;span class="load-more"&gt;
                                &nbsp;&nbsp;&lt;div class="my-custom-load-more"&gt;MORE&lt;/div&gt;
                                &lt;/span&gt;
                            </pre>

                            <h4><?php _e( 'Customize template for no results', ACS::PREFIX ) ?></h4>
                            <p><?php _e( 'In the "Setting" section you have the opportunity to customize the "no results" message using a simple string, but you can also set an HTML template if you want to highly customize the "no results" message. If you are using a custom template none text messages will be displayed and if your search get no items your template will be displayed inside the results container.', ACS::PREFIX ) ?></p>

                            <h4><?php _e( 'Plugin templates', ACS::PREFIX ) ?></h4>
                            <p>
                                <?php _e( 'The plugin built-in template use a generic HTML optimized to fit in all the WordPress themes. If you are using a "native" WordPress theme, the plugin provide an optimized template for your theme. This choice is done by the plugin, if you choose the "Plugin based" content box type, when it\'s time to show the HTML results, the plugin gets your active theme, if it\'s a "native" theme use the optimized version for the theme, instead use the generic optimized HTML.', ACS::PREFIX ) ?><br /><br />
                                <?php _e( 'The actual themes with an optimized template are:', ACS::PREFIX ) ?>
                            </p>
                            <ul class="acs_docs_content_list">
                                <li>Twenty Twelve</li>
                                <li>Twenty Thirteen</li>
                                <li>Twenty Fourteen</li>
                                <li>Twenty Fifteen</li>
                                <li>Twenty Sixteen</li>
                                <li>Twenty Seventeen</li>
                                <li>Twenty Nineteen</li>
                                <li>Twenty Twenty</li>
                            </ul>

                            <h4><?php _e( 'More about item image size and custom item image ID fields', ACS::PREFIX ) ?></h4>
                            <p>
                                <?php _e( 'The "Item image size name" and "Custom item image ID" fields are an extra feature used for storing in the index an extra field with a post image. To compose the result page the plugin retrieve a set of post IDs from the API, then using WordPress functions gets post information such as, title, permalink, image, etc. If you do not want to make an extra call to the WordPress functions or simply, you cannot do it, storing an extra field with the post featured image, lets you to show a result item without contacting WordPress anymore. This scenario could be very frequent in case of multisite with different sources that feed the CloudSearch index. You have no possibility to read the post image defined by a foreign environment.', ACS::PREFIX ) ?><br />
                                <?php _e( 'To solve this problem the plugin stores an extra text field with the name "post_image" and every time you sync a post this field is filled with the feature post image with the size that you have provided in the settings page. The size name is the value you have used in the "add_image_size()" function, or one of the reserved WordPress alias: thumb, thumbnail, medium, large, post-thumbnail. If not set, no post image is saved in the index. In the same way, if you are using the "Multiple Post Thumbnails" plugin, inserting in the field "Custom item image ID" the key to retrieve the custom "Multiple Post Thumbnails" image, you can also store this "media" type in the CloudSearch index.', ACS::PREFIX ) ?><br />
                                <?php _e( 'PS: if a "Custom item image ID" is provided, the plugin will search the image using "Multiple Post Thumbnails" without reading the "Item image size name" field.', ACS::PREFIX ) ?>
                            </p>

                            <h4><?php _e( 'Manipulate standard fields', ACS::PREFIX ) ?></h4>
                            <p>
                                <?php _e( 'You can manipulate standard fields (such as post_title, post_content, etc) adding in your sub-theme a filter named "cloud_search_#POST_TYPE#_fields" by which you can change or adds all necessary fields according to your site data.', ACS::PREFIX ) ?><br />
                            </p>

                            <pre>
                                add_filter( 'cloud_search_my-custom-post-type_fields', 'manipulate_cloud_search_fields', 10, 3 );

                                function manipulate_cloud_search_fields( $fields, $post, $from_save_transaction ) {
                                &nbsp;&nbsp;&nbsp;&nbsp;// Manage your custom values
                                &nbsp;&nbsp;&nbsp;&nbsp;$custom_fields = array(
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'post_title' => 'CHANGED IN YOUR SUB-THEME',
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'post_content' => 'CHANGED IN YOUR SUB-THEME'
                                &nbsp;&nbsp;&nbsp;&nbsp;);

                                &nbsp;&nbsp;&nbsp;&nbsp;// Merge default fields with your custom values
                                &nbsp;&nbsp;&nbsp;&nbsp;$fields = array_merge( $fields, $custom_fields );

                                &nbsp;&nbsp;&nbsp;&nbsp;return $fields;
                                }
                            </pre>
                        </div>
                        <?php
                        break;
                }
                ?>
            </div>
        </form>
    </div>
    <?php
}
