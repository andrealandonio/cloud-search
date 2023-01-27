<?php
/**
 * Render admin menu settings page
 */
function acs_menu_page_settings() {
	// Manage actions
	$message = acs_manage_menu_page_settings_actions();

    // Read site data
    $site_post_types = acs_get_all_site_types();
    $site_field_types = acs_get_all_site_fields();
    $site_taxonomy_types = acs_get_all_site_taxonomies();

	// Get settings option
	$settings = ACS::get_instance()->get_settings();
	?>
	<div class="wrap">
		<h2><?php _e( 'Settings', ACS::PREFIX ) ?></h2>

		<form action="" method="post" id="acs_form_setting" name="acs_form_setting">
			<input type="hidden" name="acs_form_action_command" value="save" />

			<p>
				<?php _e( 'In this page you can manage all the plugin configurations. There are many sections, the AWS setting, the index settings, the schema setting, the frontpage setting, the result settings and other generic settings.', ACS::PREFIX ) ?>
				<br />
				<span class="description">
					<?php _e( 'Note: There are only two required sections, the AWS and the index settings (only fields marked with "*" are mandatory). Without these configuration the plugin can not read/write to the AWS services. You can find a detailed support in the help pages. As mentioned before, other settings are not mandatory but is very important to set up them if you want a little bit of customization of your search page.', ACS::PREFIX ) ?>
				</span>
			</p>

			<?php if ( ! is_null( $message ) && $message->get_type() == ACS_Message::TYPE_ERROR ) : ?><div id="message" class="error"><p><?php echo $message->get_message() ?></p></div><?php endif ?>
			<?php if ( ! is_null( $message ) && $message->get_type() == ACS_Message::TYPE_INFO ) : ?><div id="message" class="updated notice"><p><?php echo $message->get_message() ?></p></div><?php endif ?>

			<h4><?php _e( 'Amazon settings', ACS::PREFIX ) ?></h4>
			<table class="form-table">
				<tbody>
	                <tr valign="top">
						<th scope="row"><label for="acs_aws_access_key_id"><?php _e( 'AWS Access key ID', ACS::PREFIX ) ?></label></th>
						<td><input type="text" id="acs_aws_access_key_id" name="acs_aws_access_key_id" value="<?php echo $settings->acs_aws_access_key_id ?? '' ?>" /></td>
					</tr>
	                <tr valign="top">
						<th scope="row"><label for="acs_aws_secret_access_key"><?php _e( 'AWS Secret access key', ACS::PREFIX ) ?></label></th>
						<td><input type="text" id="acs_aws_secret_access_key" name="acs_aws_secret_access_key" value="<?php echo $settings->acs_aws_secret_access_key ?? '' ?>" /></td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_aws_session_token"><?php _e( 'AWS Session token', ACS::PREFIX ) ?></label></th>
                        <td><input type="text" id="acs_aws_session_token" name="acs_aws_session_token" value="<?php echo $settings->acs_aws_session_token ?? '' ?>" /></td>
                    </tr>
	                <tr valign="top">
						<th scope="row"><label for="acs_aws_region"><?php _e( 'AWS Region', ACS::PREFIX ) ?> *</label></th>
						<td>
                            <select id="acs_aws_region" name="acs_aws_region">
                                <option <?php echo ( $settings->acs_aws_region == 'us-east-1' )  ? 'selected' : '' ?> value="us-east-1">US East (N. Virginia)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'us-east-2' )  ? 'selected' : '' ?> value="us-east-2">US East (Ohio)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'us-west-1' )  ? 'selected' : '' ?> value="us-west-1">US West (N. California)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'us-west-2' )  ? 'selected' : '' ?> value="us-west-2">US West (Oregon)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'ca-central-1' )  ? 'selected' : '' ?> value="ca-central-1">Canada (Central)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'eu-central-1' )  ? 'selected' : '' ?> value="eu-central-1">EU (Frankfurt)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'eu-west-1' )  ? 'selected' : '' ?> value="eu-west-1">EU (Ireland)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'eu-west-2' )  ? 'selected' : '' ?> value="eu-west-2">EU (London)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'eu-west-3' )  ? 'selected' : '' ?> value="eu-west-3">EU (Paris)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'ap-southeast-1' )  ? 'selected' : '' ?> value="ap-southeast-1">Asia Pacific (Singapore)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'ap-southeast-2' )  ? 'selected' : '' ?> value="ap-southeast-2">Asia Pacific (Sydney)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'ap-northeast-1' )  ? 'selected' : '' ?> value="ap-northeast-1">Asia Pacific (Tokyo)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'ap-northeast-2' )  ? 'selected' : '' ?> value="ap-northeast-2">Asia Pacific (Seoul)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'ap-south-1' )  ? 'selected' : '' ?> value="ap-south-1">Asia Pacific (Mumbai)</option>
                                <option <?php echo ( $settings->acs_aws_region == 'sa-east-1' )  ? 'selected' : '' ?> value="sa-east-1">South America (Sao Paulo)</option>
                            </select>
                        </td>
					</tr>
				</tbody>
			</table>

			<hr />

			<h4><?php _e( 'Index settings', ACS::PREFIX ) ?></h4>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="acs_search_endpoint"><?php _e( 'Search endpoint', ACS::PREFIX ) ?> *</label></th>
						<td><input type="text" id="acs_search_endpoint" name="acs_search_endpoint" value="<?php echo $settings->acs_search_endpoint ?? '' ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_search_domain_name"><?php _e( 'Domain name', ACS::PREFIX ) ?> *</label></th>
						<td><input type="text" id="acs_search_domain_name" name="acs_search_domain_name" value="<?php echo $settings->acs_search_domain_name ?? '' ?>" /></td>
					</tr>
				</tbody>
			</table>

			<hr />

			<h4><?php _e( 'Schema settings', ACS::PREFIX ) ?></h4>
			<p>
				<?php _e( 'In this section you can choose the post, field and taxonomy types that you want to export to the index CloudSearch. Keep in mind that after every schema changes you have to update and re-index your CloudSearch index for use the "modified" schema configuration. Built-in taxonomies (category, tag and format) are added by default to the schema. Also you can find some other schema configurations.', ACS::PREFIX ) ?>
                <br/>
				<?php _e( 'Removing an entry within the settings, in the CloudSearch index no field/property will be deleted and values will be maintained without any modification.', ACS::PREFIX ) ?>
            </p>
            <table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e( 'Post types', ACS::PREFIX ) ?></th>
						<td>
                            <?php
                            $acs_schema_types = $settings->acs_schema_types ?? array();
                            $acs_schema_types = $acs_schema_types ? explode( ACS::SEPARATOR, $acs_schema_types ) : array();
                            $count = 0;

                            foreach ( $site_post_types as $site_post_type_key => $site_post_type_value ) {
                                // Check if current site post type is selected or set post as default
                                $selected = ( ! empty( $acs_schema_types ) ) ? in_array( $site_post_type_key, $acs_schema_types ) : ( ( $site_post_type_key == 'post') ? true : false );
                                $count = $count + 1;
                                ?>
                                <input type="checkbox" id="acs_schema_types_option_<?php echo $count ?>" <?php echo $selected ? 'checked="checked"' : '' ?> name="acs_schema_types[]" value="<?php echo $site_post_type_key ?>" />
                                <label for="acs_schema_types_option_<?php echo $count ?>" class="acs_schema_label"><?php echo $site_post_type_value ?></label>&nbsp;
                                <?php
                            }
                            ?>
                        </td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Field types', ACS::PREFIX ) ?></th>
                        <td>
                            <?php
                            $acs_schema_fields = $settings->acs_schema_fields ?? array();
                            $acs_schema_fields = $acs_schema_fields ? explode( ACS::SEPARATOR, $acs_schema_fields ) : array();
                            $count = 0;

                            foreach ( $site_field_types as $site_field_type ) {
                                // Check if current site field type is selected
								$selected = in_array( $site_field_type, $acs_schema_fields );
                                $count = $count + 1;
                                ?>
                                <input type="checkbox" id="acs_schema_fields_option_<?php echo $count ?>" <?php echo $selected ? 'checked="checked"' : '' ?> name="acs_schema_fields[]" value="<?php echo $site_field_type ?>" />
                                <label for="acs_schema_fields_option_<?php echo $count ?>" class="acs_schema_label"><?php echo $site_field_type ?></label>&nbsp;
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Taxonomy types', ACS::PREFIX ) ?></th>
                        <td>
                            <?php
                            $acs_schema_taxonomies = $settings->acs_schema_taxonomies ?? array();
                            $acs_schema_taxonomies = $acs_schema_taxonomies ? explode( ACS::SEPARATOR, $acs_schema_taxonomies ) : array();
                            $count = 0;

                            foreach ( $site_taxonomy_types as $site_taxonomy_type ) {
                                // Check if current site taxonomy type is selected
								$selected = in_array( $site_taxonomy_type, $acs_schema_taxonomies );
                                $count = $count + 1;
                                ?>
                                <input type="checkbox" id="acs_schema_taxonomies_option_<?php echo $count ?>" <?php echo $selected ? 'checked="checked"' : '' ?> name="acs_schema_taxonomies[]" value="<?php echo $site_taxonomy_type ?>" />
                                <label for="acs_schema_taxonomies_option_<?php echo $count ?>" class="acs_schema_label"><?php echo $site_taxonomy_type ?></label>&nbsp;
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Searchable taxonomies', ACS::PREFIX ) ?></th>
                        <td>
                            <?php
							$acs_schema_searchable_taxonomies = $settings->acs_schema_searchable_taxonomies ?? array();
							$acs_schema_searchable_taxonomies = $acs_schema_searchable_taxonomies ? explode( ACS::SEPARATOR, $acs_schema_searchable_taxonomies ) : array();
							$count = 1;
							?>
                            <input type="checkbox" id="acs_schema_searchable_taxonomies_option_0" <?php echo in_array( 'category', $acs_schema_searchable_taxonomies ) ? 'checked="checked"' : '' ?> name="acs_schema_searchable_taxonomies[]" value="category" />
                            <label for="acs_schema_searchable_taxonomies_option_0" class="acs_schema_label">category</label>&nbsp;
                            <?php
							foreach ( $site_taxonomy_types as $site_taxonomy_type ) {
								// Check if current site taxonomy type is selected
								$selected = in_array( $site_taxonomy_type, $acs_schema_searchable_taxonomies );
								$count = $count + 1;
								?>
                                <input type="checkbox" id="acs_schema_searchable_taxonomies_option_<?php echo $count ?>" <?php echo $selected ? 'checked="checked"' : '' ?> name="acs_schema_searchable_taxonomies[]" value="<?php echo $site_taxonomy_type ?>" />
                                <label for="acs_schema_searchable_taxonomies_option_<?php echo $count ?>" class="acs_schema_label"><?php echo $site_taxonomy_type ?></label>&nbsp;
								<?php
							}
							?>
                            <div class="acs_row_tips">
                                <span><?php _e( 'With these options you can choose if you want to perform searches also in the selected taxonomies.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_schema_prevent_deletion"><?php _e( 'Prevent fields deletion', ACS::PREFIX ) ?></label></th>
                        <td colspan="5">
                            <input type="checkbox" id="acs_schema_prevent_deletion" name="acs_schema_prevent_deletion" value="1" <?php echo ( $settings->acs_schema_prevent_deletion == 1 ) ? 'checked="checked"' : '' ?> />
                            <div class="acs_row_tips">
                                <span><?php _e( 'With this option you can choose if you want to prevent (added by your hand) fields deletion when you create/update the index. Use this option if you have added to your CloudSearch schema some extra fields (by your hand) not using the plugin. Enabling "prevent deletion flag", when plugin run a indexing, do not manage all the "external" fields.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" colspan="6">
                            <h5><?php _e( 'Custom fields management', ACS::PREFIX ) ?></h5>
	                        <p>
                                <?php _e( 'You have the opportunity to mark as sortable some custom fields. In this sub-section you have to configure your custom fields forcing their type, if int, double, date or literal, and defining which fields should be defined as "Sortable" in CloudSearch (by default custom fields are created as text not sortable). This operation is important because text fields don\'t always sort well. For this reason, you have to provide a comma separated list of custom fields (especially if the original values are integers, doubles or dates).', ACS::PREFIX ) ?><br />
		                        <?php _e( 'This management works also if the fields have not been already created, but remember to perform an update index after any change.', ACS::PREFIX ) ?>
                            </p>
                        </th>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_schema_fields_int"><?php _e( 'Integer fields', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_schema_fields_int" name="acs_schema_fields_int" value="<?php echo $settings->acs_schema_fields_int ?>" class="max" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Enter comma separated list of fields to be converted to "int" in CloudSearch.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_schema_fields_double"><?php _e( 'Double fields', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_schema_fields_double" name="acs_schema_fields_double" value="<?php echo $settings->acs_schema_fields_double ?>" class="max" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Enter comma separated list of fields to be converted to "double" in CloudSearch.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_schema_fields_date"><?php _e( 'Date fields', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_schema_fields_date" name="acs_schema_fields_date" value="<?php echo $settings->acs_schema_fields_date ?>" class="max" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Enter comma separated list of fields to be converted to "date" in CloudSearch.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_schema_fields_literal"><?php _e( 'Literal fields', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_schema_fields_literal" name="acs_schema_fields_literal" value="<?php echo $settings->acs_schema_fields_literal ?>" class="max" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Enter comma separated list of fields to be converted to "literal" in CloudSearch.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_schema_fields_sortable"><?php _e( 'Sortable fields', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_schema_fields_sortable" name="acs_schema_fields_sortable" value="<?php echo $settings->acs_schema_fields_sortable ?>" class="max" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Enter comma separated list of fields to be created/managed as "Sortable" in CloudSearch.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
				</tbody>
			</table>

			<hr />

			<h4><?php _e( 'Frontpage settings', ACS::PREFIX ) ?></h4>
			<p>
				<?php _e( 'In this section you can choose the content box used to display search results and choose if you want to use the default plugin search page (or one created by your own), the jQuery support and if you want to show filter fields in the frontend search page.', ACS::PREFIX ) ?>
			</p>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="acs_frontpage_content_box_type"><?php _e( 'Content box type', ACS::PREFIX ) ?></label></th>
						<td>
							<?php echo acs_draw_content_box_type_select( $settings->acs_frontpage_content_box_type ) ?>
							<div id="acs_frontpage_content_box_tips" class="acs_row_tips">
								<span>&nbsp;</span><br/>
                                <label for="acs_frontpage_content_box_value"></label><input type="text" id="acs_frontpage_content_box_value" name="acs_frontpage_content_box_value" value="<?php echo $settings->acs_frontpage_content_box_value ?>" class="medium" />
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_frontpage_use_plugin_search_page"><?php _e( 'Use plugin search page', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="checkbox" id="acs_frontpage_use_plugin_search_page" name="acs_frontpage_use_plugin_search_page" value="1" <?php echo ( $settings->acs_frontpage_use_plugin_search_page == 1 ) ? 'checked="checked"' : '' ?> />
							<div class="acs_row_tips">
								<span>
									<?php _e( 'Selecting this value you choose to use the plugin search page instead the default WordPress search page, otherwise if you deselect this value you have to create your own search page.', ACS::PREFIX ) ?>
									<?php _e( 'For more details click', ACS::PREFIX ) ?> <a href="/wp-admin/admin.php?page=acs_menu_help#acs_help_how_plugin_works_customize_your_pages"><?php _e( 'here', ACS::PREFIX ) ?></a>.
									<?php _e( 'If you choose to use the plugin search page it\'s highly recommended to use the jQuery support and the \'Plugin based\' content box type.', ACS::PREFIX ) ?>
                                </span>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_frontpage_use_jquery"><?php _e( 'Use jQuery', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="checkbox" id="acs_frontpage_use_jquery" name="acs_frontpage_use_jquery" value="1" <?php echo ( $settings->acs_frontpage_use_jquery == 1 ) ? 'checked="checked"' : '' ?> />
							<div class="acs_row_tips">
								<span>
									<?php _e( 'Plugin needs to used jQuery to get search result asynchronously. If you don\'t want to use jQuery uncheck this flag to avoid plugin and jQuery scripts inclusion, but in this way you need to redefine a custom way to get search results. Look at the plugin REST endpoint to create your own search page.', ACS::PREFIX ) ?>
									<?php _e( 'For more details click', ACS::PREFIX ) ?> <a href="/wp-admin/admin.php?page=acs_menu_help#acs_help_api"><?php _e( 'here', ACS::PREFIX ) ?></a>.
								</span>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_frontpage_show_filters"><?php _e( 'Show filters', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="checkbox" id="acs_frontpage_show_filters" name="acs_frontpage_show_filters" value="1" <?php echo ( $settings->acs_frontpage_show_filters == 1 ) ? 'checked="checked"' : '' ?> />
							<div class="acs_row_tips">
								<span><?php _e( 'With this option you can choose if you want to show filter select boxes in your frontend search page. Using filters you can change the default results order and you can also apply a filter to the search query. Customizing your CSS you can easily change the filters layout.', ACS::PREFIX ) ?></span>
							</div>
						</td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_frontpage_custom_css"><?php _e( 'Custom CSS', ACS::PREFIX ) ?></label></th>
                        <td>
                            <textarea id="acs_frontpage_custom_css" name="acs_frontpage_custom_css" rows="5"><?php echo $settings->acs_frontpage_custom_css ?? '' ?></textarea>
                            <div class="acs_row_tips">
                                <span><?php _e( 'Customize search page CSS here.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
				</tbody>
			</table>

			<hr />

			<h4><?php _e( 'Results settings', ACS::PREFIX ) ?></h4>
			<p>
				<?php _e( 'In this section you can customize your result pages choosing fields to display in every result item (further than the standard fields such as title, permalink, etc), default messages (for load more and no results), search max items and default filter values.', ACS::PREFIX ) ?>
			</p>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e( 'Showed fields', ACS::PREFIX ) ?><br />(<?php _e( 'for posts', ACS::PREFIX ) ?>)</th>
						<td>
							<input type="checkbox" id="acs_results_show_fields_sticky" name="acs_results_show_fields_sticky" value="1" <?php echo ( $settings->acs_results_show_fields_sticky == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_sticky"><?php _e( 'Sticky flag', ACS::PREFIX ) ?></label>&nbsp;
							<input type="checkbox" id="acs_results_show_fields_formats" name="acs_results_show_fields_formats" value="1" <?php echo ( $settings->acs_results_show_fields_formats == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_formats"><?php _e( 'Post formats', ACS::PREFIX ) ?></label>&nbsp;
							<input type="checkbox" id="acs_results_show_fields_categories" name="acs_results_show_fields_categories" value="1" <?php echo ( $settings->acs_results_show_fields_categories == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_categories"><?php _e( 'Categories', ACS::PREFIX ) ?></label>&nbsp;
							<input type="checkbox" id="acs_results_show_fields_tags" name="acs_results_show_fields_tags" value="1" <?php echo ( $settings->acs_results_show_fields_tags == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_tags"><?php _e( 'Tags', ACS::PREFIX ) ?></label>&nbsp;
							<input type="checkbox" id="acs_results_show_fields_comments" name="acs_results_show_fields_comments" value="1" <?php echo ( $settings->acs_results_show_fields_comments == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_comments"><?php _e( 'Comments link', ACS::PREFIX ) ?></label>&nbsp;
							<input type="checkbox" id="acs_results_show_fields_content" name="acs_results_show_fields_content" value="1" <?php echo ( $settings->acs_results_show_fields_content == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_content"><?php _e( 'Content', ACS::PREFIX ) ?></label>
                            <input type="checkbox" id="acs_results_show_fields_excerpt" name="acs_results_show_fields_excerpt" value="1" <?php echo ( $settings->acs_results_show_fields_excerpt == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_excerpt"><?php _e( 'Excerpt', ACS::PREFIX ) ?></label>

							<br />
							<br />

                            <input type="checkbox" id="acs_results_show_fields_custom" name="acs_results_show_fields_custom" value="1" <?php echo ( $settings->acs_results_show_fields_custom == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_custom"><?php _e( 'Custom field', ACS::PREFIX ) ?></label>,&nbsp;
                            <label for="acs_results_custom_field" class="acs_inline_tips"><?php _e( 'show a custom field in the results (after content and excerpt)', ACS::PREFIX ) ?></label>:&nbsp;<input type="text" id="acs_results_custom_field" name="acs_results_custom_field" value="<?php echo $settings->acs_results_custom_field ?>" />

                            <br />

							<input type="checkbox" id="acs_results_show_fields_image" name="acs_results_show_fields_image" value="1" <?php echo ( $settings->acs_results_show_fields_image == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_image"><?php _e( 'Image', ACS::PREFIX ) ?></label>,&nbsp;
							<label for="acs_results_format_image" class="acs_inline_tips"><?php _e( 'use a custom image format (leave empty for default)', ACS::PREFIX ) ?></label>:&nbsp;<input type="text" id="acs_results_format_image" name="acs_results_format_image" value="<?php echo $settings->acs_results_format_image ?>" />
							<span class="acs_inline_tips"><?php _e( 'For more details click', ACS::PREFIX ) ?> <a href="https://codex.wordpress.org/Function_Reference/add_image_size" target="_blank"><?php _e( 'here', ACS::PREFIX ) ?></a></span>

							<br />

							<input type="checkbox" id="acs_results_show_fields_date" name="acs_results_show_fields_date" value="1" <?php echo ( $settings->acs_results_show_fields_date == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_date"><?php _e( 'Date', ACS::PREFIX ) ?></label>,&nbsp;
							<label for="acs_results_format_date" class="acs_inline_tips"><?php _e( 'use a custom date format (leave empty for default)', ACS::PREFIX ) ?></label>:&nbsp;<input type="text" id="acs_results_format_date" name="acs_results_format_date" value="<?php echo $settings->acs_results_format_date ?>" />
							<span class="acs_inline_tips"><?php _e( 'For more details click', ACS::PREFIX ) ?> <a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php _e( 'here', ACS::PREFIX ) ?></a></span>

							<br />

							<input type="checkbox" id="acs_results_show_fields_author" name="acs_results_show_fields_author" value="1" <?php echo ( $settings->acs_results_show_fields_author == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_author"><?php _e( 'Author', ACS::PREFIX ) ?></label>,&nbsp;
							<label for="acs_results_format_author" class="acs_inline_tips"><?php _e( 'use a custom author format', ACS::PREFIX ) ?></label>:&nbsp;<?php echo acs_draw_author_format_select( $settings->acs_results_format_author ) ?>

						</td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Showed fields', ACS::PREFIX ) ?><br />(<?php _e( 'for terms', ACS::PREFIX ) ?>)</th>
                        <td>
                            <input type="checkbox" id="acs_results_show_fields_terms_content" name="acs_results_show_fields_terms_content" value="1" <?php echo ( $settings->acs_results_show_fields_terms_content == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_terms_content"><?php _e( 'Content', ACS::PREFIX ) ?></label>

                            <br />
                            <br />

                            <input type="checkbox" id="acs_results_show_fields_terms_custom" name="acs_results_show_fields_terms_custom" value="1" <?php echo ( $settings->acs_results_show_fields_terms_custom == 1 ) ? 'checked="checked"' : '' ?> /><label for="acs_results_show_fields_terms_custom"><?php _e( 'Custom field', ACS::PREFIX ) ?></label>,&nbsp;
                            <label for="acs_results_custom_terms_field" class="acs_inline_tips"><?php _e( 'show a custom field in the results (after content)', ACS::PREFIX ) ?></label>:&nbsp;<input type="text" id="acs_results_custom_terms_field" name="acs_results_custom_terms_field" value="<?php echo $settings->acs_results_custom_terms_field ?>" />
                        </td>
                    </tr>
					<tr valign="top">
						<th scope="row"><label for="acs_results_no_results_msg"><?php _e( 'No results message', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="text" id="acs_results_no_results_msg" name="acs_results_no_results_msg" value="<?php echo htmlspecialchars( stripslashes( $settings->acs_results_no_results_msg ) ) ?>" class="big" />
							<div class="acs_row_tips">
								<label for="acs_results_no_results_box_value">
									<span><?php _e( 'Provide a custom name for the no results content box (eg: content-none). The \'##YOUR-VALUE##.php\' template in your theme root folder will be used for no results box (eg: content-none.php). If the template is not in the theme root folder, add the folder path in the value field. If you set a custom box value it will be used instead of the default text message in case of no results.', ACS::PREFIX ) ?></span><br />
								</label>
								<input type="text" id="acs_results_no_results_box_value" name="acs_results_no_results_box_value" value="<?php echo $settings->acs_results_no_results_box_value ?>" />
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_results_load_more_msg"><?php _e( 'Load more message', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="text" id="acs_results_load_more_msg" name="acs_results_load_more_msg" value="<?php echo htmlspecialchars( stripslashes( $settings->acs_results_load_more_msg ) ) ?>" class="big" />
						</td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_filter_text_length"><?php _e( 'Length of the text', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_filter_text_length" name="acs_filter_text_length" value="<?php echo (empty($settings->acs_filter_text_length)) ? ACS::SEARCH_TEXT_LENGTH : $settings->acs_filter_text_length ?>" class="small" />
                            <label for="acs_filter_text_length_type"></label>
                            <select id="acs_filter_text_length_type" name="acs_filter_text_length_type">
                                <option value="words" <?php echo ($settings->acs_filter_text_length_type == 'words') ? 'selected="selected"' : '' ?>><?php _e( 'words', ACS::PREFIX ) ?></option>
                                <option value="chars" <?php echo ($settings->acs_filter_text_length_type == 'chars') ? 'selected="selected"' : '' ?>><?php _e( 'chars', ACS::PREFIX ) ?></option>
                            </select>
                        </td>
                    </tr>
					<tr valign="top">
						<th scope="row"><label for="acs_results_max_items"><?php _e( 'Search max items', ACS::PREFIX ) ?></label></th>
						<td><input type="text" id="acs_results_max_items" name="acs_results_max_items" value="<?php echo $settings->acs_results_max_items ?>" class="small" /></td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_results_field_weights"><?php _e( 'Field weights', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_results_field_weights" name="acs_results_field_weights" value="<?php echo htmlspecialchars( stripslashes( $settings->acs_results_field_weights ) ) ?>" class="big" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'You can assign weights to selected fields so you can boost the relevance _score of documents with matches in key fields and minimize the impact of matches in less important fields. For example, if you want matches within the post_title field to score higher than matches within the post_content field, you could set the weight of the post_title field to 2 and the weight of the post_content field to 0.5, like the following example (use "," as separator without inserting spaces between values):', ACS::PREFIX ) ?></span><br />
                                <pre>post_title^2,post_content^0.5</pre>
                            </div>
                        </td>
                    </tr>
					<tr valign="top">
						<th scope="row"><label for="acs_filter_sort_field"><?php _e( 'Default sort field', ACS::PREFIX ) ?></label></th>
						<td><?php echo acs_draw_filter_sort_fields( $settings->acs_filter_sort_field, false ) ?></td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_filter_sort_order"><?php _e( 'Default sort order', ACS::PREFIX ) ?></label></th>
                        <td><?php echo acs_draw_filter_sort_orders( $settings->acs_filter_sort_order, false ) ?></td>
                    </tr>
				</tbody>
			</table>

			<hr />

            <h4><?php _e( 'Highlighting settings', ACS::PREFIX ) ?></h4>
            <p>
                <?php _e( 'In this section you can find a list of settings to highlight search results. You can choose a style type between: strong, italic and underline. Furthermore, you can choose if highlights titles and you can also customize the styles, giving a color (to the text and to the background), a CSS inline style and a CSS class to the highlighted word. Note that, by default, these settings are used in post content and post excerpt fields.', ACS::PREFIX ) ?>
            </p>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="acs_highlight_type"><?php _e( 'Style type', ACS::PREFIX ) ?></label></th>
                        <td>
                            <select id="acs_highlight_type" name="acs_highlight_type">
                                <option value="none" <?php echo ( $settings->acs_highlight_type == 'none' ) ? 'selected="selected"' : '' ?>><?php _e( 'None', ACS::PREFIX ) ?></option>
                                <option value="strong" <?php echo ( $settings->acs_highlight_type == 'strong' ) ? 'selected="selected"' : '' ?>><?php _e( 'Strong', ACS::PREFIX ) ?></option>
                                <option value="italic" <?php echo ( $settings->acs_highlight_type == 'italic' ) ? 'selected="selected"' : '' ?>><?php _e( 'Italic', ACS::PREFIX ) ?></option>
                                <option value="underline" <?php echo ( $settings->acs_highlight_type == 'underline' ) ? 'selected="selected"' : '' ?>><?php _e( 'Underline', ACS::PREFIX ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_highlight_titles"><?php _e( 'Highlight in titles?', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="checkbox" id="acs_highlight_titles" name="acs_highlight_titles" value="1" <?php echo ( $settings->acs_highlight_titles == 1 ) ? 'checked="checked"' : '' ?> />&nbsp;
                            <span class="acs_inline_tips"><?php _e( 'Highlight also in search result titles.', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_highlight_color_text"><?php _e( 'Text color', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_highlight_color_text" name="acs_highlight_color_text" value="<?php echo $settings->acs_highlight_color_text ?>" class="small" />
                            <span class="acs_inline_tips"><?php _e( 'Use HTML color codes (#rgb or #rrggbb).', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_highlight_color_background"><?php _e( 'Background color', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_highlight_color_background" name="acs_highlight_color_background" value="<?php echo $settings->acs_highlight_color_background ?>" class="small" />
                            <span class="acs_inline_tips"><?php _e( 'Use HTML color codes (#rgb or #rrggbb).', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_highlight_style"><?php _e( 'CSS style', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_highlight_style" name="acs_highlight_style" value="<?php echo htmlspecialchars( stripslashes( $settings->acs_highlight_style ) ) ?>" class="big" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Provide a CSS inline style, style will be inserted in a span (eg: text-decoration:underline; color:#FF0000;)', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_highlight_class"><?php _e( 'CSS class', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_highlight_class" name="acs_highlight_class" value="<?php echo htmlspecialchars( stripslashes( $settings->acs_highlight_class ) ) ?>" class="big" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Insert a CSS class name, search results will be wrapped in a span with the provided class.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr />

            <h4><?php _e( 'Suggestions settings', ACS::PREFIX ) ?></h4>
            <p>
                <?php _e( 'In this section you can find a list of settings to set up the search suggestions. First of all you can activate the suggestions by turning on the "Active" flag. The other fields give you the opportunity to customize the suggestion output style (for generic and focused rows), the suggestion results order and max items, the click behavior when you click a suggested value, the jQuery selector for the search field and the typed chars before the suggestion starts. If active, after you have typed the number of your configured chars, the plugin makes a request to a CloudSearch API and gives you back the search results.', ACS::PREFIX ) ?>
            </p>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="acs_suggest_active"><?php _e( 'Active', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="checkbox" id="acs_suggest_active" name="acs_suggest_active" value="1" <?php echo ( $settings->acs_suggest_active == 1 ) ? 'checked="checked"' : '' ?> />&nbsp;
                            <span class="acs_inline_tips"><?php _e( 'Enable search field suggestions (the "Use jQuery" flag is mandatory if you want to activate suggestions).', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_suggest_selector"><?php _e( 'Search field selector', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_suggest_selector" name="acs_suggest_selector" value="<?php echo htmlspecialchars( stripslashes( $settings->acs_suggest_selector ) ) ?>" class="medium" />
                            <span class="acs_inline_tips"><?php _e( 'Any valid CSS selector will work. The default search box for WordPress is: input[name=\'s\']', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_suggest_only_title"><?php _e( 'Search only in titles?', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="checkbox" id="acs_suggest_only_title" name="acs_suggest_only_title" value="1" <?php echo ( $settings->acs_suggest_only_title == 1 ) ? 'checked="checked"' : '' ?> />&nbsp;
                            <span class="acs_inline_tips"><?php _e( 'Enable if you want to search suggested items using only titles. If disabled, suggestion engine searches in all fields but may be slower.', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Styles (for all rows)', ACS::PREFIX ) ?></th>
                        <td>
                            <label for="acs_suggest_all_font_size"><?php _e( 'Font size', ACS::PREFIX ) ?></label>
                            <input type="text" id="acs_suggest_all_font_size" name="acs_suggest_all_font_size" value="<?php echo $settings->acs_suggest_all_font_size ?>" class="tiny" />&nbsp;-&nbsp;
                            <label for="acs_suggest_all_color"><?php _e( 'Color', ACS::PREFIX ) ?></label>
                            <input type="text" id="acs_suggest_all_color" name="acs_suggest_all_color" value="<?php echo $settings->acs_suggest_all_color ?>" class="small" />&nbsp;-&nbsp;
                            <label for="acs_suggest_all_background"><?php _e( 'Background', ACS::PREFIX ) ?></label>
                            <input type="text" id="acs_suggest_all_background" name="acs_suggest_all_background" value="<?php echo $settings->acs_suggest_all_background ?>" class="small" />
                            <span class="acs_inline_tips"><?php _e( 'Use HTML color codes (#rgb or #rrggbb).', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e( 'Styles (for focused row)', ACS::PREFIX ) ?></th>
                        <td>
                            <label for="acs_suggest_focused_font_size"><?php _e( 'Font size', ACS::PREFIX ) ?></label>
                            <input type="text" id="acs_suggest_focused_font_size" name="acs_suggest_focused_font_size" value="<?php echo $settings->acs_suggest_focused_font_size ?>" class="tiny" />&nbsp;-&nbsp;
                            <label for="acs_suggest_focused_color"><?php _e( 'Color', ACS::PREFIX ) ?></label>
                            <input type="text" id="acs_suggest_focused_color" name="acs_suggest_focused_color" value="<?php echo $settings->acs_suggest_focused_color ?>" class="small" />&nbsp;-&nbsp;
                            <label for="acs_suggest_focused_background"><?php _e( 'Background', ACS::PREFIX ) ?></label>
                            <input type="text" id="acs_suggest_focused_background" name="acs_suggest_focused_background" value="<?php echo $settings->acs_suggest_focused_background ?>" class="small" />
                            <span class="acs_inline_tips"><?php _e( 'Use HTML color codes (#rgb or #rrggbb).', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_suggest_trigger"><?php _e( 'Chars trigger', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="number" id="acs_suggest_trigger" name="acs_suggest_trigger" value="<?php echo $settings->acs_suggest_trigger ?>" class="tiny" />
                            <span class="acs_inline_tips"><?php _e( 'The minimum number of chars before the suggestions starts.', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_suggest_results"><?php _e( 'Number of results', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="number" id="acs_suggest_results" name="acs_suggest_results" value="<?php echo $settings->acs_suggest_results ?>" class="tiny" />
                            <span class="acs_inline_tips"><?php _e( 'The maximum number of suggested items.', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_suggest_order"><?php _e( 'Suggested items order', ACS::PREFIX ) ?></label></th>
                        <td>
                            <select id="acs_suggest_order" name="acs_suggest_order">
                                <option value="<?php echo ACS::SUGGEST_ORDER_TYPE_1 ?>" <?php echo ( $settings->acs_suggest_order == ACS::SUGGEST_ORDER_TYPE_1 ) ? 'selected="selected"' : '' ?>><?php _e( 'Relevance', ACS::PREFIX ) ?></option>
                                <option value="<?php echo ACS::SUGGEST_ORDER_TYPE_2 ?>" <?php echo ( $settings->acs_suggest_order == ACS::SUGGEST_ORDER_TYPE_2 ) ? 'selected="selected"' : '' ?>><?php _e( 'Alphabetically', ACS::PREFIX ) ?></option>
                                <option value="<?php echo ACS::SUGGEST_ORDER_TYPE_3 ?>" <?php echo ( $settings->acs_suggest_order == ACS::SUGGEST_ORDER_TYPE_3 ) ? 'selected="selected"' : '' ?>><?php _e( 'Alphabetically reverse', ACS::PREFIX ) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_suggest_click"><?php _e( 'Click behaviour', ACS::PREFIX ) ?></label></th>
                        <td>
                            <select id="acs_suggest_click" name="acs_suggest_click">
                                <option value="<?php echo ACS::SUGGEST_CLICK_TYPE_1 ?>" <?php echo ( $settings->acs_suggest_click == ACS::SUGGEST_CLICK_TYPE_1 ) ? 'selected="selected"' : '' ?>><?php _e( 'Do nothing', ACS::PREFIX ) ?></option>
                                <option value="<?php echo ACS::SUGGEST_CLICK_TYPE_2 ?>" <?php echo ( $settings->acs_suggest_click == ACS::SUGGEST_CLICK_TYPE_2 ) ? 'selected="selected"' : '' ?>><?php _e( 'Go to link', ACS::PREFIX ) ?></option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr />

            <h4><?php _e( 'Network settings', ACS::PREFIX ) ?></h4>
            <p>
				<?php _e( 'In this section you can find a set of network settings to change default configurations. You can provide an alternative value for "site_id" and "blog_id". Modifying these options you can use different values from the default ones (eg: if you are using the same CloudSearch index from two different WordPress environment (localhost and staging), providing different values gives you the opportunity to use different keys for the same CloudSearch row without the risk of override your data).', ACS::PREFIX ) ?>
            </p>
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row"><label for="acs_network_site_id"><?php _e( 'Site ID', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_network_site_id" name="acs_network_site_id" value="<?php echo $settings->acs_network_site_id ?>" class="" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Leave empty for default.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_network_blog_id"><?php _e( 'Blog ID', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_network_blog_id" name="acs_network_blog_id" value="<?php echo $settings->acs_network_blog_id ?>" class="" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Leave empty for default.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr />

			<h4><?php _e( 'Other settings', ACS::PREFIX ) ?></h4>
			<p>
				<?php _e( 'In this section you can find a set of other settings to improve the plugin configuration. First of all you can provide a set (separated by ",") of prefix or suffix that will be used to create the field types list you can find in the "Schema settings" section. Further than a set of default fields, with this option, you can tell the plugin which other fields you want to manage. Also, you can choose the separator to use for text-array values and you can provide a custom image size name to sync to the CloudSearch index (attention that this is not the image showed in the result box but it\'s only used to save in the schema the featured image URL, it\'s not mandatory, you can leave this field empty. This field is designed to be read by other applications).', ACS::PREFIX ) ?>
			</p>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="acs_schema_fields_prefix"><?php _e( 'Custom field types prefix/suffix', ACS::PREFIX ) ?></label></th>
						<td>
                            <input type="text" id="acs_schema_fields_prefix" name="acs_schema_fields_prefix" value="<?php echo $settings->acs_schema_fields_prefix ?>" class="big" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Add also private fields to avoid automatically exclusion (e.g. add "_price" to show private field "price" in the field types list).', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_schema_fields_separator"><?php _e( 'Field values separator', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="text" id="acs_schema_fields_separator" name="acs_schema_fields_separator" value="<?php echo $settings->acs_schema_fields_separator ?>" class="" />
							<div class="acs_row_tips">
								<span><?php _e( 'Provide a field char to use as values separator (e.g. for category values use "ID|NAME", where "|" is the separator char).', ACS::PREFIX ) ?></span>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_schema_fields_image_size"><?php _e( 'Item image size name', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="text" id="acs_schema_fields_image_size" name="acs_schema_fields_image_size" value="<?php echo $settings->acs_schema_fields_image_size ?>" class="big" />
							<div class="acs_row_tips">
								<span><?php _e( 'If you want to save an image for every indexed post provide the image size name (e.g. if you are using an "add_image_size()" custom format fill the name of the registered new image size). This value is not necessary to retrieve and display results, it\'s only used for extra features.', ACS::PREFIX ) ?></span>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="acs_schema_fields_custom_image_id"><?php _e( 'Custom item image ID', ACS::PREFIX ) ?></label></th>
						<td>
							<input type="text" id="acs_schema_fields_custom_image_id" name="acs_schema_fields_custom_image_id" value="<?php echo $settings->acs_schema_fields_custom_image_id ?>" class="big" />
							<div class="acs_row_tips">
								<span><?php _e( 'If you are using "Multiple Post Thumbnails" plugin, provide here the ID to retrieve the custom image.', ACS::PREFIX ) ?></span>
							</div>
						</td>
					</tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_schema_fields_legacy_types"><?php _e( 'List of legacy post types to be included in searches', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="text" id="acs_schema_fields_legacy_types" name="acs_schema_fields_legacy_types" value="<?php echo $settings->acs_schema_fields_legacy_types ?>" class="big" />
                            <div class="acs_row_tips">
                                <span><?php _e( 'Enter comma separated list of types if you want to include some legacy post types in searches.', ACS::PREFIX ) ?></span>
                            </div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_hide_section_help"><?php _e( 'Hide "Help" section from menu', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="checkbox" id="acs_hide_section_help" name="acs_hide_section_help" value="1" <?php echo ( $settings->acs_hide_section_help == 1 ) ? 'checked="checked"' : '' ?> />&nbsp;
                            <span class="acs_inline_tips"><?php _e( 'Hide page from menu.', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_hide_section_docs"><?php _e( 'Hide "Documentation" section from menu', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="checkbox" id="acs_hide_section_docs" name="acs_hide_section_docs" value="1" <?php echo ( $settings->acs_hide_section_docs == 1 ) ? 'checked="checked"' : '' ?> />&nbsp;
                            <span class="acs_inline_tips"><?php _e( 'Hide page from menu.', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="acs_hide_section_import"><?php _e( 'Hide "Import / Export" section from menu', ACS::PREFIX ) ?></label></th>
                        <td>
                            <input type="checkbox" id="acs_hide_section_import" name="acs_hide_section_import" value="1" <?php echo ( $settings->acs_hide_section_import == 1 ) ? 'checked="checked"' : '' ?> />&nbsp;
                            <span class="acs_inline_tips"><?php _e( 'Hide page from menu.', ACS::PREFIX ) ?></span>
                        </td>
                    </tr>
				</tbody>
			</table>

			<hr />

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save', ACS::PREFIX ) ?>" />
            </p>
		</form>

	</div>
	<?php
}

/**
 * Manage admin menu settings page actions
 *
 * @return ACS_Message|null
 */
function acs_manage_menu_page_settings_actions() {
	$message = null;

	if ( isset( $_POST[ 'acs_form_action_command' ] ) ) {
		// Get form params
		$command = filter_var ( $_POST[ 'acs_form_action_command' ], FILTER_SANITIZE_STRING );

		switch ( $command ) {
			case 'save':
				// Save settings action
				$settings = new stdClass();

				// Read post data
				$settings->acs_aws_access_key_id = ( !empty( $_POST[ 'acs_aws_access_key_id' ] ) ) ? wp_kses_post( $_POST[ 'acs_aws_access_key_id' ] ) : '';
				$settings->acs_aws_secret_access_key = ( !empty( $_POST[ 'acs_aws_secret_access_key' ] ) ) ? wp_kses_post( $_POST[ 'acs_aws_secret_access_key' ] ) : '';
				$settings->acs_aws_session_token = ( !empty( $_POST[ 'acs_aws_session_token' ] ) ) ? wp_kses_post( $_POST[ 'acs_aws_session_token' ] ) : '';
				$settings->acs_aws_region = ( !empty( $_POST[ 'acs_aws_region' ] ) ) ? wp_kses_post( $_POST[ 'acs_aws_region' ] ) : '';
				$settings->acs_search_endpoint = ( !empty( $_POST[ 'acs_search_endpoint' ] ) ) ? wp_kses_post( $_POST[ 'acs_search_endpoint' ] ) : '';
				$settings->acs_search_domain_name = ( !empty( $_POST[ 'acs_search_domain_name' ] ) ) ? wp_kses_post( $_POST[ 'acs_search_domain_name' ] ) : '';
				$settings->acs_frontpage_content_box_type = ( !empty( $_POST[ 'acs_frontpage_content_box_type' ] ) ) ? wp_kses_post( $_POST[ 'acs_frontpage_content_box_type' ] ) : 'default';
				$settings->acs_frontpage_content_box_value = ( !empty( $_POST[ 'acs_frontpage_content_box_value' ] ) ) ? wp_kses_post( $_POST[ 'acs_frontpage_content_box_value' ] ) : '';
				$settings->acs_frontpage_use_plugin_search_page = ( !empty( $_POST[ 'acs_frontpage_use_plugin_search_page' ] ) ) ? 1 : 0;
				$settings->acs_frontpage_use_jquery = ( !empty( $_POST[ 'acs_frontpage_use_jquery' ] ) ) ? 1 : 0;
				$settings->acs_frontpage_show_filters = ( !empty( $_POST[ 'acs_frontpage_show_filters' ] ) ) ? 1 : 0;
				$settings->acs_frontpage_custom_css = ( !empty( $_POST[ 'acs_frontpage_custom_css' ] ) ) ? wp_kses_post( $_POST[ 'acs_frontpage_custom_css' ] ) : '';
				$settings->acs_results_show_fields_sticky = ( !empty( $_POST[ 'acs_results_show_fields_sticky' ] ) ) ? 1 : 0;
				$settings->acs_results_show_fields_formats = ( !empty( $_POST[ 'acs_results_show_fields_formats' ] ) ) ? 1 : 0;
				$settings->acs_results_show_fields_categories = ( !empty( $_POST[ 'acs_results_show_fields_categories' ] ) ) ? 1 : 0;
				$settings->acs_results_show_fields_tags = ( !empty( $_POST[ 'acs_results_show_fields_tags' ] ) ) ? 1 : 0;
				$settings->acs_results_show_fields_comments = ( !empty( $_POST[ 'acs_results_show_fields_comments' ] ) ) ? 1 : 0;
				$settings->acs_results_show_fields_content = ( !empty( $_POST[ 'acs_results_show_fields_content' ] ) ) ? 1 : 0;
                $settings->acs_results_show_fields_excerpt = ( !empty( $_POST[ 'acs_results_show_fields_excerpt' ] ) ) ? 1 : 0;
				$settings->acs_results_show_fields_custom = ( !empty( $_POST[ 'acs_results_show_fields_custom' ] ) ) ? 1 : 0;
				$settings->acs_results_custom_field = ( !empty( $_POST[ 'acs_results_custom_field' ] ) ) ? wp_kses_post( $_POST[ 'acs_results_custom_field' ] ) : '';
				$settings->acs_results_show_fields_image = ( !empty( $_POST[ 'acs_results_show_fields_image' ] ) ) ? 1 : 0;
				$settings->acs_results_format_image = ( !empty( $_POST[ 'acs_results_format_image' ] ) ) ? wp_kses_post( $_POST[ 'acs_results_format_image' ] ) : '';
				$settings->acs_results_show_fields_date = ( !empty( $_POST[ 'acs_results_show_fields_date' ] ) ) ? 1 : 0;
				$settings->acs_results_format_date = ( !empty( $_POST[ 'acs_results_format_date' ] ) ) ? wp_kses_post( $_POST[ 'acs_results_format_date' ] ) : '';
				$settings->acs_results_show_fields_author = ( !empty( $_POST[ 'acs_results_show_fields_author' ] ) ) ? 1 : 0;
				$settings->acs_results_format_author = ( !empty( $_POST[ 'acs_results_format_author' ] ) ) ? wp_kses_post( $_POST[ 'acs_results_format_author' ] ) : '';
				$settings->acs_results_show_fields_terms_content = ( !empty( $_POST[ 'acs_results_show_fields_terms_content' ] ) ) ? 1 : 0;
				$settings->acs_results_show_fields_terms_custom = ( !empty( $_POST[ 'acs_results_show_fields_terms_custom' ] ) ) ? 1 : 0;
				$settings->acs_results_custom_terms_field = ( !empty( $_POST[ 'acs_results_custom_terms_field' ] ) ) ? wp_kses_post( $_POST[ 'acs_results_custom_terms_field' ] ) : '';
				$settings->acs_results_no_results_msg = ( !empty( $_POST[ 'acs_results_no_results_msg' ] ) ) ? stripslashes( wp_kses_post( $_POST[ 'acs_results_no_results_msg' ] ) ) : __( 'No results', ACS::PREFIX );
				$settings->acs_results_no_results_box_value = ( !empty( $_POST[ 'acs_results_no_results_box_value' ] ) ) ? wp_kses_post( $_POST[ 'acs_results_no_results_box_value' ] ) : '';
				$settings->acs_results_load_more_msg = ( !empty( $_POST[ 'acs_results_load_more_msg' ] ) ) ? stripslashes( wp_kses_post( $_POST[ 'acs_results_load_more_msg' ] ) ) : __( 'Load more', ACS::PREFIX );
				$settings->acs_results_max_items = ( !empty( $_POST[ 'acs_results_max_items' ] ) ) ? intval( wp_kses_post( $_POST[ 'acs_results_max_items' ] ) ) : ACS::SEARCH_RETURN_FULL_ITEMS;
				$settings->acs_results_field_weights = ( !empty( $_POST[ 'acs_results_field_weights' ] ) ) ? stripslashes( wp_kses_post( $_POST[ 'acs_results_field_weights' ] ) ) : '';
				$settings->acs_filter_sort_field = ( !empty( $_POST[ 'acs_filter_sort_field' ] ) ) ? wp_kses_post( $_POST[ 'acs_filter_sort_field' ] ) : ACS::SORT_FIELD_DEFAULT;
				$settings->acs_filter_sort_order = ( !empty( $_POST[ 'acs_filter_sort_order' ] ) ) ? wp_kses_post( $_POST[ 'acs_filter_sort_order' ] ) : ACS::SORT_ORDER_DEFAULT;
                $settings->acs_filter_text_length = ( !empty( $_POST[ 'acs_filter_text_length' ] ) ) ? intval( wp_kses_post( $_POST[ 'acs_filter_text_length' ] ) ) : ACS::SEARCH_TEXT_LENGTH;
                $settings->acs_filter_text_length_type = ( !empty( $_POST[ 'acs_filter_text_length_type' ] ) ) ? wp_kses_post( $_POST[ 'acs_filter_text_length_type' ] ) : ACS::SEARCH_TEXT_LENGTH_TYPE;
				$settings->acs_schema_fields_int = ( !empty( $_POST[ 'acs_schema_fields_int' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_int' ] ) : '';
                $settings->acs_schema_fields_double = ( !empty( $_POST[ 'acs_schema_fields_double' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_double' ] ) : '';
				$settings->acs_schema_fields_date = ( !empty( $_POST[ 'acs_schema_fields_date' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_date' ] ) : '';
				$settings->acs_schema_fields_literal = ( !empty( $_POST[ 'acs_schema_fields_literal' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_literal' ] ) : '';
                $settings->acs_schema_fields_sortable = ( !empty( $_POST[ 'acs_schema_fields_sortable' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_sortable' ] ) : '';
                $settings->acs_schema_fields_prefix = ( !empty( $_POST[ 'acs_schema_fields_prefix' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_prefix' ] ) : '';
				$settings->acs_schema_fields_separator = ( !empty( $_POST[ 'acs_schema_fields_separator' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_separator' ] ) : ACS::FIELD_SEPARATOR_DEFAULT;
				$settings->acs_schema_fields_image_size = ( !empty( $_POST[ 'acs_schema_fields_image_size' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_image_size' ] ) : '';
				$settings->acs_schema_fields_custom_image_id = ( !empty( $_POST[ 'acs_schema_fields_custom_image_id' ] ) ) ? wp_kses_post( $_POST[ 'acs_schema_fields_custom_image_id' ] ) : '';
				$settings->acs_schema_fields_invalid_chars = ( !empty( $_POST[ 'acs_schema_fields_invalid_chars' ] ) ) ? stripslashes( $_POST[ 'acs_schema_fields_invalid_chars' ] ) : '';
				$settings->acs_schema_fields_legacy_types = ( !empty( $_POST[ 'acs_schema_fields_legacy_types' ] ) ) ? stripslashes( $_POST[ 'acs_schema_fields_legacy_types' ] ) : '';
				$settings->acs_schema_prevent_deletion =  ( !empty( $_POST[ 'acs_schema_prevent_deletion' ] ) ) ? 1 : 0;
				$settings->acs_network_site_id = ( !empty( $_POST[ 'acs_network_site_id' ] ) ) ? wp_kses_post( $_POST[ 'acs_network_site_id' ] ) : '';
				$settings->acs_network_blog_id = ( !empty( $_POST[ 'acs_network_blog_id' ] ) ) ? wp_kses_post( $_POST[ 'acs_network_blog_id' ] ) : '';
				$settings->acs_highlight_type = ( !empty( $_POST[ 'acs_highlight_type' ] ) ) ? wp_kses_post( $_POST[ 'acs_highlight_type' ] ) : ACS::HIGHLIGHT_TYPE_DEFAULT;
                $settings->acs_highlight_titles = ( !empty( $_POST[ 'acs_highlight_titles' ] ) ) ? 1 : 0;
                $settings->acs_highlight_color_text = ( !empty( $_POST[ 'acs_highlight_color_text' ] ) ) ? wp_kses_post( $_POST[ 'acs_highlight_color_text' ] ) : '';
                $settings->acs_highlight_color_background = ( !empty( $_POST[ 'acs_highlight_color_background' ] ) ) ? wp_kses_post( $_POST[ 'acs_highlight_color_background' ] ) : '';
                $settings->acs_highlight_style = ( !empty( $_POST[ 'acs_highlight_style' ] ) ) ? stripslashes( wp_kses_post( $_POST[ 'acs_highlight_style' ] ) ) : '';
                $settings->acs_highlight_class = ( !empty( $_POST[ 'acs_highlight_class' ] ) ) ? stripslashes( wp_kses_post( $_POST[ 'acs_highlight_class' ] ) ) : '';
                $settings->acs_suggest_active =  ( !empty( $_POST[ 'acs_suggest_active' ] ) ) ? 1 : 0;
				$settings->acs_suggest_only_title =  ( !empty( $_POST[ 'acs_suggest_only_title' ] ) ) ? 1 : 0;
                $settings->acs_suggest_selector = ( !empty( $_POST[ 'acs_suggest_selector' ] ) ) ? stripslashes( wp_kses_post( $_POST[ 'acs_suggest_selector' ] ) ) : ACS::SUGGEST_DEFAULT_SELECTOR;
                $settings->acs_suggest_trigger = ( !empty( $_POST[ 'acs_suggest_trigger' ] ) ) ? intval( wp_kses_post( $_POST[ 'acs_suggest_trigger' ] ) ) : ACS::SUGGEST_DEFAULT_TRIGGER;
                $settings->acs_suggest_results = ( !empty( $_POST[ 'acs_suggest_results' ] ) ) ? intval( wp_kses_post( $_POST[ 'acs_suggest_results' ] ) ) : ACS::SUGGEST_DEFAULT_RESULTS;
                $settings->acs_suggest_order = ( !empty( $_POST[ 'acs_suggest_order' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_order' ] ) : ACS::SUGGEST_ORDER_TYPE_1;
                $settings->acs_suggest_click = ( !empty( $_POST[ 'acs_suggest_click' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_click' ] ) : ACS::SUGGEST_CLICK_TYPE_1;
                $settings->acs_suggest_all_font_size = ( !empty( $_POST[ 'acs_suggest_all_font_size' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_all_font_size' ] ) : ACS::SUGGEST_DEFAULT_ALL_FONT_SIZE;
                $settings->acs_suggest_all_color = ( !empty( $_POST[ 'acs_suggest_all_color' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_all_color' ] ) : ACS::SUGGEST_DEFAULT_ALL_COLOR;
                $settings->acs_suggest_all_background = ( !empty( $_POST[ 'acs_suggest_all_background' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_all_background' ] ) : ACS::SUGGEST_DEFAULT_ALL_BACKGROUND;
                $settings->acs_suggest_focused_font_size = ( !empty( $_POST[ 'acs_suggest_focused_font_size' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_focused_font_size' ] ) : ACS::SUGGEST_DEFAULT_FOCUSED_FONT_SIZE;
                $settings->acs_suggest_focused_color = ( !empty( $_POST[ 'acs_suggest_focused_color' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_focused_color' ] ) : ACS::SUGGEST_DEFAULT_FOCUSED_COLOR;
                $settings->acs_suggest_focused_background = ( !empty( $_POST[ 'acs_suggest_focused_background' ] ) ) ? wp_kses_post( $_POST[ 'acs_suggest_focused_background' ] ) : ACS::SUGGEST_DEFAULT_FOCUSED_BACKGROUND;
				$settings->acs_hide_section_help =  ( !empty( $_POST[ 'acs_hide_section_help' ] ) ) ? 1 : 0;
				$settings->acs_hide_section_docs =  ( !empty( $_POST[ 'acs_hide_section_docs' ] ) ) ? 1 : 0;
				$settings->acs_hide_section_import =  ( !empty( $_POST[ 'acs_hide_section_import' ] ) ) ? 1 : 0;

                // Normalize acs_search_endpoint field for adding HTTPS protocol
                if ( str_starts_with( $settings->acs_search_endpoint, 'http://' ) ) {
                    $settings->acs_search_endpoint = str_replace( 'http://', 'https://', $settings->acs_search_endpoint);
                }
                if ( ! str_starts_with( $settings->acs_search_endpoint, 'https://' ) ) {
                    $settings->acs_search_endpoint = 'https://' . $settings->acs_search_endpoint;
                }
                
				// Remove '.php' occurrences from boxes value
				$settings->acs_frontpage_content_box_value = str_replace( '.php', '', $settings->acs_frontpage_content_box_value );
				$settings->acs_results_no_results_box_value = str_replace( '.php', '', $settings->acs_results_no_results_box_value );
				$settings->acs_frontpage_content_box_value = str_replace( '.PHP', '', $settings->acs_frontpage_content_box_value );
				$settings->acs_results_no_results_box_value = str_replace( '.PHP', '', $settings->acs_results_no_results_box_value );

				$acs_schema_types = ! empty( $_POST[ 'acs_schema_types' ] ) ? $_POST[ 'acs_schema_types' ] : array();
				$settings->acs_schema_types = implode( ACS::SEPARATOR, $acs_schema_types );

				$acs_schema_fields = ! empty( $_POST[ 'acs_schema_fields' ] ) ? $_POST[ 'acs_schema_fields' ] : array();
				$settings->acs_schema_fields = implode( ACS::SEPARATOR, $acs_schema_fields );

				$acs_schema_taxonomies = ! empty( $_POST[ 'acs_schema_taxonomies' ] ) ? $_POST[ 'acs_schema_taxonomies' ] : array();
				$settings->acs_schema_taxonomies = implode( ACS::SEPARATOR, $acs_schema_taxonomies );

				$acs_schema_searchable_taxonomies = ! empty( $_POST[ 'acs_schema_searchable_taxonomies' ] ) ? $_POST[ 'acs_schema_searchable_taxonomies' ] : array();
				$settings->acs_schema_searchable_taxonomies = implode( ACS::SEPARATOR, $acs_schema_searchable_taxonomies );

				// Save option on database
				update_option( ACS::OPTION_SETTINGS, $settings );

				// Reload settings option to refresh settings data after POST
				ACS::get_instance()->reload_settings();

				$message = new ACS_Message( '<strong>Success</strong>: Settings updated', '', ACS_Message::TYPE_INFO );
				break;
		}
	}

	return $message;
}
