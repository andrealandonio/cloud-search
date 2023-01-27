=== CloudSearch ===
Contributors: lando1982, sburdett, methnen, bheadrick
Tags: aws, amazon, cloud, search, research, CloudSearch, cs, suggest, facet
Requires at least: 4.4
Tested up to: 5.9
Stable tag: 3.0.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html

CloudSearch is a flexible plugin that allows you to leverage the search index power of Amazon CloudSearch in your WordPress site.

== Description ==

CloudSearch is a flexible plugin that allows you to leverage the search index power of Amazon CloudSearch in your WordPress site.
To use this plugin you'll need an Amazon Web Services account. Attention: Amazon CloudSearch is a paid service and will require a credit card.

Before you can start using CloudSearch, the plugin needs to be activated and configured. Activate the plugin, then go to the menu "CloudSearch -> Settings" (you can find this menu in the sidebar of your WordPress admin panel).
Fill the form data:

* Enter Amazon access key ID, Amazon secret access key and Amazon region for your account (look for "Security Credentials" in your Amazon console to retrieve these data)
* Enter your CloudSearch index search endpoint and the domain name (I suggest to create the CloudSearch index before you start the plugin configuration)
* Schema settings
* Other settings

IMPORTANT NOTES WITH RELEASE 2.0.0:
* At least WordPress 4.4 version mandatory (for WP_Term support)
* Changed APIs output from an array of IDs to an array of object composed by the entity ID and the entity type

Minimum requirements:

* WordPress Version 4.4
* PHP Version 5.3
* Amazon Web Services account with CloudSearch enabled

= Usage =

1. Go to `CloudSearch -> Settings`
2. Enter your `Amazon access key ID`, `Secret access key` and the `Amazon region` where you have created the CloudSearch index
3. Enter a `Search endpoint` and the `Domain name`. Get these info in your CloudSearch dashboard in AWS Console
4. Choose post types, custom fields and custom taxonomies that you want to export to the CloudSearch index
5. Set up other settings or leave defaults
6. Save settings.
7. Go to `CloudSearch -> Manage`
8. Click the action `Create index`, `Run indexing` and `Sync all documents`. Between every action wait until the `Status` field is `Active`, then go on with the next task
9. After these operation your index is ready, now you can search documents in your CloudSearch index

Links: [Author's Site](http://www.andrealandonio.it)

== Installation ==

1. Unzip the downloaded `cloud-search` zip file
2. Upload the `cloud-search` folder and its contents into the `wp-content/plugins/` directory of your WordPress installation
3. Activate `cloud-search` from Plugins page

== Frequently Asked Questions ==

= How much does the plugin cost? =

There is no charge for the plugin. The only charges you incur are for usage of Amazon CloudSearch.

= Works on multisite? =

Yes, for every site you have a specific configuration but you cannot search documents between different sites with defaults configuration. You need to customize search scripts.

= Does the plugin support multi languages? =

The plugin does not work with other languages plugins. However, there is a alternative and simple way to work with other languages. An explanation could be found in the plugin help page.

= Which post types and fields are indexed by default? How do I modify indexed schema? =

By default, only posts and standard fields are indexed. To modify this, in the admin "Setting" section you can add other post types, fields and taxonomies (also custom objects).

= Supports facets? =

Yes, you can use API for retrieve facet information.

= Supports field weightings? =

Yes, you can add a weight to some fields to boost up the relevance of the results.

= Supports for WooCommerce product tags and EDD tags? =

Yes, you can manage WooCommerce product tags and EDD tags simply adding a little code in a custom plugin or in the functions.php of your theme.

== Screenshots ==

1. Settings page
2. Manage page
3. Front page
4. Other settings (results and highlighting)
5. Other settings (suggestions)

== Changelog ==

= 3.0.0 - 2023-01-27 =
* Added support to AWS SDK version 3

= 2.12.0 - 2021-10-26 =
* Added AWS session token management

= 2.11.1 - 2021-06-25 =
* Added permission_callback in register_rest_route

= 2.11.0 - 2021-06-21 =
* Added acs_search_args filter hook

= 2.10.2 - 2021-03-14 =
* Tested up with WordPress 5.7 release

= 2.10.1 - 2020-12-09 =
* Tested up to latest WordPress releases

= 2.10.0 - 2020-10-23 =
* Added AMP management in API results

= 2.9.4 - 2020-08-17 =
* Removed path from loading image

= 2.9.3 - 2020-04-24 =
* Removed undefined index warnings

= 2.9.2 - 2020-04-23 =
* Fixed query_filter error introduced in 2.9.1

= 2.9.1 - 2020-04-21 =
* Move "acs_add_filter_query_conditions" filter hook apply position to a "larger" one

= 2.9.0 - 2020-04-16 =
* Added "acs_add_filter_query_conditions" filter hook

= 2.8.6 - 2020-03-14 =
* Updated "jquery-ui-1.9.2.custom.min.js" path in the enqueue scripts function

= 2.8.5 - 2020-03-10 =
* Changed no results found JS response

= 2.8.4 - 2020-01-25 =
* Changed wrong checkbox form read method

= 2.8.3 - 2020-01-17 =
* Simplified "acs_check_user_capabilities" method by checking only "activate_plugins" capability

= 2.8.1 - 2020-01-05 =
* Added support for Date types
* Fixed undefined index for TextOptions

= 2.8.0 - 2019-12-27 =
* Added REST API route for suggestion service

= 2.7.0 - 2019-11-22 =
* Manage settings for adding a filter post type for "legacy" items

= 2.6.1 - 2019-11-17 =
* Added support for twentytwenty theme

= 2.6.0 - 2019-10-17 =
* Fixed bug with sync all documents and acs_exclude filter

= 2.5.2 - 2019-10-08 =
* Changed "filter_query" clauses from exclusive to inclusive

= 2.5.1 - 2019-03-05 =
* Added "acs_post_transition_allowed_statuses" filter hook

= 2.5.0 - 2019-02-28 =
* Removal of the dependency on the Amazon Web Services plugin
* Fixed PHP strict issues on code

= 2.4.0 - 2018-11-07 =
* Added "post_content_original" field in search actions
* Added "Import / Export" settings page

= 2.3.0 - 2018-10-15 =
* Added sortable custom field support
* Extend default sort fields with sortable custom fields

= 2.2.0 - 2018-10-01 =
* Added invalid chars remove feature before docs syncing

= 2.1.0 - 2018-09-28 =
* Fixed delete documents action
* Added usage of WordPress language instead the default ACS::ANALYSIS_SCHEMA constant

= 2.0.0 - 2018-09-20 =
* Added term support to sync and search also terms on CloudSearch index (need WP_Term support, available from WordPress 4.4)

= 1.8.0 - 2018-05-23 =
* Added custom search page CSS on admin settings section
* Manage 'the_posts' filter
* Removed '_score' field from update documents operation

= 1.7.0 - 2018-05-17 =
* Added stop sync/delete button on admin manage section

= 1.6.7 - 2018-05-11 =
* Fixed warning/notice on 'acs_plugin_disable_search_wp_query'
* Added 'Filters' section on documentation page

= 1.6.6 - 2018-04-20 =
* Added definition of search endpoint and domain name in wp-config.php file

= 1.6.5 - 2018-04-20 =
* Added custom field option in results settings

= 1.6.4 - 2018-04-17 =
* Added search template override management

= 1.6.3 - 2018-04-13 =
* Strip shortcodes on post content/excerpt

= 1.6.2 - 2018-03-29 =
* Added client options configuration

= 1.6.1 - 2018-03-16 =
* Fixed site/blog ID management on multisite

= 1.6.0 - 2018-02-26 =
* Added field weightings management
* Removed unwanted WordPress default search page automatic query

= 1.5.2 - 2017-10-04 =
* Fixed search query bugs

= 1.5.1 - 2017-08-29 =
* Fixed minor notices

= 1.5.0 - 2017-07-11 =
* Added update documents action on admin manage section

= 1.4.2 - 2017-05-12 =
* Fixed bad connection test behavior

= 1.4.1 - 2017-04-20 =
* Added private fields selection support

= 1.4.0 - 2017-04-20 =
* Added facet support

= 1.3.3 - 2017-04-12 =
* Added hide menu sections functionality

= 1.3.2 - 2017-04-03 =
* Added exclude pages/posts support, removed notices, moved Amazon access keys in wp-config.php

= 1.3.1 - 2017-03-14 =
* Added screen readers support

= 1.3.0 - 2017-02-20 =
* Added sort on multiple fields functionality

= 1.2.6 - 2017-02-08 =
* Added site/blog info override functionality

= 1.2.5 - 2017-02-05 =
* Removed auto-focus from suggest jquery
* Fixed bad search query creation
* Added addcslashes for text highlighting
* Added preserve text functionality on search index documents method

= 1.2.4 - 2017-01-28 =
* Reviewed auto suggest introducing search titles filter

= 1.2.3 - 2017-01-01 =
* Added theme "Twenty Seventeen" support

= 1.2.2 - 2016-11-15 =
* Added "Multiple Post Thumbnails" plugin support

= 1.2.1 - 2016-11-08 =
* Added IAM connection

= 1.2.0 - 2016-11-01 =
* Added auto suggest on search field

= 1.1.1 - 2016-10-10 =
* Fixed invalid UTF-8 chars validation errors

= 1.1.0 - 2016-10-09 =
* Added Asia Pacific (Seoul) AWS region
* Added text length selection on result snippet
* Added highlights on result snippet
* Added "extras" field management in services

= 1.0.3 - 2016-07-26 =
* Added fields manipulation

= 1.0.2 - 2016-05-31 =
* Fixed "post_author_name" empty field error

= 1.0.1 - 2016-02-26 =
* Fixed "post_author_name" field get method

= 1.0.0 - 2016-02-14 =
* First release

== Upgrade Notice ==

= 1.0.0 =
This version requires PHP 5.3+
