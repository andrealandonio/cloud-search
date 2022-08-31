<?php
/**
 * Render admin menu manage page
 */
function acs_menu_page_manage() {
    // Manage actions
    $message = acs_manage_menu_page_manage_actions();

	// Read options
	$option_status = get_option( ACS::OPTION_STATUS, ACS::STATUS_NO_OPERATION );
	$option_items = get_option( ACS::OPTION_ITEMS, 0 );

    // Get WordPress version
    $wp_version = get_bloginfo( 'version' );

    // Check WordPress version, apply dashicons only for WordPress 4.3 or higher
    if ( $wp_version >= 4.3 ) {
        $dashicons_reload = 'dashicons-image-rotate';
	    $dashicons_stop = 'dashicons-no';
    }
    else {
        $dashicons_reload = 'dashicons-backup';
	    $dashicons_stop = 'dashicons-no';
    }
    ?>
    <div class="wrap">
        <h2><?php _e( 'Manage', ACS::PREFIX ) ?></h2>

	    <form action="" method="post" id="acs_form_manage" name="acs_form_manage">

			<input type="hidden" id="acs_option_status" value="<?php echo $option_status ?>" />
			<input type="hidden" id="acs_option_items" value="<?php echo $option_items ?>" />

			<?php if ( ! is_null( $message ) && $message->get_type() == ACS_Message::TYPE_ERROR ) : ?><div id="message" class="error"><p><?php echo $message->get_message() ?></p></div><?php endif ?>
			<?php if ( ! is_null( $message ) && $message->get_type() == ACS_Message::TYPE_INFO ) : ?><div id="message" class="updated notice"><p><?php echo $message->get_message() ?></p></div><?php endif ?>

			<p>
	            <?php _e( 'In this page you can view the index status, in particular, you can find how many documents you have in the index (divided by all searchable documents and documents of the current site), the defined index fields number and the index status (if running, if needs an indexing, if there is a pending operation). After the status section you have a list of actions that you can perform in the index.', ACS::PREFIX ) ?>
				<br />
				<span class="description"><?php _e( 'Note: There could be a time delay between index status page data and the real AWS data.', ACS::PREFIX ) ?></span>
	        </p>

			<h4><?php _e( 'Index status', ACS::PREFIX ) ?></h4>
			<table class="form-table acs_status">
				<tbody>
					<?php
					// Check connection with client
					if ( acs_check_connection() ) {
						?>
						<tr valign="top">
							<th scope="row">
								<?php _e( 'Searchable documents', ACS::PREFIX ) ?>
								<span class="acs_reload dashicons <?php echo $dashicons_reload ?>" data-fn="searchable_documents" title="<?php _e( 'Reload', ACS::PREFIX ) ?>"></span>
							</th>
							<td class="field_index_searchable_documents">
								<span class="message"></span>
								<span class="loading"><img src="<?php echo admin_url() . '/images/loading.gif' ?>" width="16" height="16" title="<?php _e( 'Loading', ACS::PREFIX ) ?>" /></span>
							</td>
						</tr>
                        <tr valign="top">
                            <th scope="row">
                                <?php _e( 'Site documents', ACS::PREFIX ) ?>
                                <span class="acs_reload dashicons <?php echo $dashicons_reload ?>" data-fn="site_documents" title="<?php _e( 'Reload', ACS::PREFIX ) ?>"></span>
                            </th>
                            <td class="field_index_site_documents">
                                <span class="message"></span>
                                <span class="loading"><img src="<?php echo admin_url() . '/images/loading.gif' ?>" width="16" height="16" title="<?php _e( 'Loading', ACS::PREFIX ) ?>" /></span>
                            </td>
                        </tr>
						<tr valign="top">
							<th scope="row">
								<?php _e( 'Index fields', ACS::PREFIX ) ?>
								<span class="acs_reload dashicons <?php echo $dashicons_reload ?>" data-fn="fields" title="<?php _e( 'Reload', ACS::PREFIX ) ?>"></span>
							</th>
							<td class="field_index_fields">
								<span class="message"></span>
								<span class="loading"><img src="<?php echo admin_url() . '/images/loading.gif' ?>" width="16" height="16" title="<?php _e( 'Loading', ACS::PREFIX ) ?>" /></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<?php _e( 'Status', ACS::PREFIX ) ?>
								<span class="acs_reload dashicons <?php echo $dashicons_reload ?>" data-fn="status" title="<?php _e( 'Reload', ACS::PREFIX ) ?>"></span>
							</th>
							<td class="field_index_status">
								<span class="message"></span>
								<span class="loading"><img src="<?php echo admin_url() . '/images/loading.gif' ?>" width="16" height="16" title="<?php _e( 'Loading', ACS::PREFIX ) ?>" /></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="field_index_operation_head">
								<?php _e( 'Current operation', ACS::PREFIX ) ?>
								<span class="stop acs_stop dashicons <?php echo $dashicons_stop ?>" data-fn="stop_operations" title="<?php _e( 'Stop', ACS::PREFIX ) ?>"></span>
                                <span class="loading"><img src="<?php echo admin_url() . '/images/loading.gif' ?>" width="16" height="16" title="<?php _e( 'Operation in progress', ACS::PREFIX ) ?>" /></span>
                            </th>
							<td class="field_index_operation">
								<span class="message">-</span>
							</td>
						</tr>
						<?php
					}
					else {
						?>
						<tr valign="top">
							<th scope="row"><?php _e( 'Status', ACS::PREFIX ) ?></th>
							<td class="field_index_error"><?php _e( 'Connection with CloudSearch index is not working or incomplete', ACS::PREFIX ) ?></td>
						</tr>
						<?php
					}
					?>

				</tbody>
			</table>

			<hr />

			<?php
			// Check connection with client
			if ( acs_check_connection() ) {
				?>
				<h4><?php _e( 'Actions', ACS::PREFIX ) ?></h4>
				<table class="form-table acs_actions">
					<tbody>
						<tr valign="top">
							<td>
								<p class="acs_action">
									<strong><?php _e( 'Index management', ACS::PREFIX ) ?></strong>:
									<?php _e( 'use this action to create or update the index fields in CloudSearch (usually after every schema settings changes, e.g. if you add a new custom field type to your schema). After the create/update operation is completed, you need to run an indexing over your CloudSearch index. If your schema need an indexing you will be notified in the index status panel as explained before. The "test connection" action simply tells you if the index works correctly. ', ACS::PREFIX ) ?>
								</p>
								<form action="" method="post" id="acs_form_action_index_create" class="acs_sync_action check_confirm" data-message_check_confirm="<?php _e( 'Are you sure to create/update the index?', ACS::PREFIX ) ?>">
									<input type="hidden" id="acs_form_action_command" name="acs_form_action_command" value="index-create" />
									<input type="submit" id="acs_form_action_index_create_submit" class="button button-primary" value="<?php _e( 'Create/update index', ACS::PREFIX ) ?>" />
								</form>
								&nbsp;
								<form action="" method="post" id="acs_form_action_run_indexing" class="acs_sync_action check_confirm" data-message_check_confirm="<?php _e( 'Are you sure to run indexing?', ACS::PREFIX ) ?>">
									<input type="hidden" id="acs_form_action_command" name="acs_form_action_command" value="run-indexing" />
									<input type="submit" id="acs_form_action_run_indexing_submit" class="button button-primary" value="<?php _e( 'Run indexing', ACS::PREFIX ) ?>" />
								</form>
								&nbsp;
								<form action="" method="post" id="acs_form_action_connection_test" class="acs_sync_action">
									<input type="hidden" id="acs_form_action_command" name="acs_form_action_command" value="connection-test" />
									<input type="submit" id="acs_form_action_connection_test_submit" class="button button-primary" value="<?php _e( 'Test connection', ACS::PREFIX ) ?>" />
								</form>
							</td>
						</tr>
                        <tr valign="top">
                            <td>
                                <p class="acs_action">
                                    <strong><?php _e( 'Update documents', ACS::PREFIX ) ?></strong>:
									<?php _e( 'use this action to perform an update operation to a specific document field of your CloudSearch index. You have to provide the unique document ID, a field name and a field value. If all data are valid, a synchronous task will update the document (if founded) modifying the field value according to the provided one.', ACS::PREFIX ) ?>
                                </p>
                                <form action="" method="post" id="acs_form_action_index_update_document" class="acs_sync_action check_update_keys_empty check_confirm" data-message_check_update_keys_empty="<?php _e( 'Invalid values', ACS::PREFIX ) ?>" data-message_check_confirm="<?php _e( 'Are you sure to update the document?', ACS::PREFIX ) ?>">
                                    <input type="hidden" id="acs_form_action_command" name="acs_form_action_command" value="index-document-update" />
									<label for="acs_form_action_update_id"><?php _e( 'ID', ACS::PREFIX ) ?>:&nbsp;</label>
                                    <input type="text" id="acs_form_action_update_id" name="acs_form_action_update_id" class="acs_form_action_update_id" value="" />&nbsp;
                                    <label for="acs_form_action_update_key"><?php _e( 'Field', ACS::PREFIX ) ?>:&nbsp;</label>
                                    <input type="text" id="acs_form_action_update_key" name="acs_form_action_update_key" class="acs_form_action_update_key" value="" />&nbsp;
                                    <label for="acs_form_action_update_value"><?php _e( 'Value', ACS::PREFIX ) ?>:&nbsp;</label>
                                    <input type="text" id="acs_form_action_update_value" name="acs_form_action_update_value" class="acs_form_action_update_value" value="" />
                                    <input type="submit" id="acs_form_action_index_update_document_submit" class="button button-primary" value="<?php _e( 'Update document', ACS::PREFIX ) ?>" />
                                </form>
                            </td>
                        </tr>
						<tr valign="top">
							<td>
								<p class="acs_action">
									<strong><?php _e( 'Sync documents', ACS::PREFIX ) ?></strong>:
									<?php _e( 'use this action to perform a sync operation from your WordPress database to your CloudSearch index. You can put a single document to the index (providing the post ID and selecting "post" as type for generic posts or custom post types, otherwise providing the term ID and selecting the taxonomy name for the provided ID) or the entire set of your site documents according with the post, field and taxonomy types configured in the settings section.', ACS::PREFIX ) ?>
								</p>
								<form action="" method="post" id="acs_form_action_index_sync_document" class="acs_sync_action check_key_empty check_confirm" data-message_check_key_empty="<?php _e( 'Invalid value', ACS::PREFIX ) ?>" data-message_check_confirm="<?php _e( 'Are you sure to sync the document?', ACS::PREFIX ) ?>">
									<input type="hidden" id="acs_form_action_command" name="acs_form_action_command" value="index-document-sync" />
									<?php echo acs_draw_action_entity_type_select() ?>
									<?php echo acs_draw_action_key_type_select() ?>
                                    <label for="acs_form_action_key"></label><input type="text" id="acs_form_action_key" name="acs_form_action_key" class="acs_form_action_key" value="" />
									<input type="submit" id="acs_form_action_index_sync_document_submit" class="button button-primary" value="<?php _e( 'Sync document', ACS::PREFIX ) ?>" />
								</form>
								&nbsp;
								<input type="button"
									   id="acs_action_index_sync_documents"
									   class="button button-primary acs_async_action"
									   value="<?php _e( 'Sync all index documents', ACS::PREFIX ) ?>"
									   data-action="acs_index_documents_sync"
									   data-method="POST"
									   data-message="<?php _e( 'Are you sure to sync all documents?', ACS::PREFIX ) ?>"
								/>
							</td>
						</tr>
						<tr valign="top">
							<td>
								<p class="acs_action">
                                    <strong><?php _e( 'Delete documents', ACS::PREFIX ) ?></strong>:
									<?php _e( 'use this action to perform a delete operation from your CloudSearch index. You can delete a single document (providing the CloudSearch ID) or the entire set of your site documents according with the post, field and taxonomy types configured in the settings section.', ACS::PREFIX ) ?>
								</p>
								<form action="" method="post" id="acs_form_action_index_delete_document" class="acs_sync_action check_key_empty check_confirm" data-message_check_key_empty="<?php _e( 'Invalid value', ACS::PREFIX ) ?>" data-message_check_confirm="<?php _e( 'Are you sure to delete the document?', ACS::PREFIX ) ?>">
									<input type="hidden" id="acs_form_action_command" name="acs_form_action_command" value="index-document-delete" />
									<?php echo acs_draw_action_key_type_select() ?>
                                    <label for="acs_form_action_key"></label><input type="text" id="acs_form_action_key" name="acs_form_action_key" class="acs_form_action_key" value="" />
									<input type="submit" id="acs_form_action_index_delete_document_submit" class="button button-primary" value="<?php _e( 'Delete document', ACS::PREFIX ) ?>" />
								</form>
								&nbsp;
								<input type="button"
									   id="acs_action_index_delete_documents"
									   class="button button-primary acs_async_action"
									   value="<?php _e( 'Delete all index documents', ACS::PREFIX ) ?>"
									   data-action="acs_index_documents_delete"
									   data-method="POST"
									   data-message="<?php _e( 'Are you sure to delete all documents?', ACS::PREFIX ) ?>"
								/>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
			}
			?>
        </form>

    </div>
    <?php
}

/**
 * Manage admin menu manage page actions
 *
 * @return ACS_Message|null
 */
function acs_manage_menu_page_manage_actions() {
	$message = null;

	if ( isset( $_POST[ 'acs_form_action_command' ] ) ) {
		// Get form params
		$command = filter_var ( $_POST[ 'acs_form_action_command' ], FILTER_SANITIZE_STRING );
		$key_type = ( empty( $_POST[ 'acs_form_action_key_type' ] ) ) ? ACS::SEARCH_KEY_TYPE_DEFAULT : filter_var ( $_POST[ 'acs_form_action_key_type' ], FILTER_SANITIZE_STRING );
		$entity_type = ( empty( $_POST[ 'acs_form_action_entity_type' ] ) ) ? ACS::SEARCH_ENTITY_TYPE_DEFAULT : filter_var ( $_POST[ 'acs_form_action_entity_type' ], FILTER_SANITIZE_STRING );
		$key = ( empty( $_POST[ 'acs_form_action_key' ] ) ) ? '*' : filter_var ( $_POST[ 'acs_form_action_key' ], FILTER_SANITIZE_STRING );

		switch ( $command ) {
			case 'index-create':
				// Create index action
				try {
					$result = acs_index_create();
					if ( count( $result->get_data()[ 'fields_with_error' ] ) > 0 && $result->get_data()[ 'fields_managed' ] == 0) {
						// Found only error fields
						$message = new ACS_Message( '<strong>Error</strong>: Fields with errors: %s', implode( ACS::SEPARATOR, $result->get_data()[ 'fields_with_error' ] ), ACS_Message::TYPE_ERROR );
					}
					elseif ( count( $result->get_data()[ 'fields_with_error' ] ) > 0 && $result->get_data()[ 'fields_managed' ] > 0) {
						// Found error fields but one or more index fields created
						$message = new ACS_Message( '<strong>Error</strong>: Some index fields created/updated correctly, but there are fields with errors: %s', implode( ACS::SEPARATOR, $result->get_data()[ 'fields_with_error' ] ), ACS_Message::TYPE_ERROR );
					}
					elseif ( $result->get_data()[ 'fields_managed' ] == 0 ) {
						// Schema already defined (no new index fields)
						$message = new ACS_Message( '<strong>Success</strong>: No index fields created/updated, all index fields are already defined', '', ACS_Message::TYPE_INFO );
					}
					else {
						// Schema created
						$message = new ACS_Message( '<strong>Success</strong>: %s index fields created/updated', $result->get_data()[ 'fields_managed' ], ACS_Message::TYPE_INFO );
					}
				}
				catch ( Exception $e ) {
					$message = new ACS_Message( '<strong>Error</strong>: %s', $e->getMessage(), ACS_Message::TYPE_ERROR );
				}
				break;
			case 'run-indexing':
				// Run indexing action
				try {
					$result = acs_run_indexing();
					$message = new ACS_Message( '<strong>Success</strong>: Run indexing started for %s fields', $result->get_data()[ 'fields_managed' ], ACS_Message::TYPE_INFO );
				}
				catch ( Exception $e ) {
					$message = new ACS_Message( '<strong>Error</strong>: %s', $e->getMessage(), ACS_Message::TYPE_ERROR );
				}
				break;
			case 'connection-test':
				// Connection test action
				try {
					$result = acs_index_documents_search( '*', ACS::SEARCH_FIELD_DEFAULT, 0, ACS::SEARCH_RETURN_TEST_ITEMS );
					$message = new ACS_Message( '<strong>Success</strong>: Index works correctly', $result->get_data()[ 'found' ], ACS_Message::TYPE_INFO );
				}
				catch ( Exception $e ) {
					$message = new ACS_Message( '<strong>Error</strong>: %s', $e->getMessage(), ACS_Message::TYPE_ERROR );
				}
				break;
			case 'index-document-update':
				// Update index document action
				$update_id = ( empty( $_POST[ 'acs_form_action_update_id' ] ) ) ? '' : filter_var ( $_POST[ 'acs_form_action_update_id' ], FILTER_SANITIZE_STRING );
				$update_key = ( empty( $_POST[ 'acs_form_action_update_key' ] ) ) ? '' : filter_var ( $_POST[ 'acs_form_action_update_key' ], FILTER_SANITIZE_STRING );
				$update_value = ( empty( $_POST[ 'acs_form_action_update_value' ] ) ) ? '' : filter_var ( $_POST[ 'acs_form_action_update_value' ], FILTER_SANITIZE_STRING );

				try {
					if ( ! empty( $update_id ) && ! empty( $update_key ) && ! empty( $update_value ) ) {
						$result = acs_index_documents_update( $update_id, $update_key, $update_value );
						$message = new ACS_Message( '<strong>Success</strong>: Updated %s documents on index', $result->get_data()[ 'found' ], ACS_Message::TYPE_INFO );
					}
					else {
						$message = new ACS_Message( '<strong>Error</strong>: Invalid document data', '', ACS_Message::TYPE_ERROR );
					}
				}
				catch ( Exception $e ) {
					$message = new ACS_Message( '<strong>Error</strong>: %s', $e->getMessage(), ACS_Message::TYPE_ERROR );
				}
				break;
			case 'index-document-sync':
				// Sync index document action
				try {
					if ( intval( $key ) > 0 ) {
						$result = acs_index_documents_sync( $key, $key_type, $entity_type );
						$message = new ACS_Message( '<strong>Success</strong>: Synced %s documents on index', $result->get_data()[ 'found' ], ACS_Message::TYPE_INFO );
					}
					else {
						$message = new ACS_Message( '<strong>Error</strong>: Invalid document ID', '', ACS_Message::TYPE_ERROR );
					}
				}
				catch ( Exception $e ) {
					$message = new ACS_Message( '<strong>Error</strong>: %s', $e->getMessage(), ACS_Message::TYPE_ERROR );
				}
				break;
			case 'index-document-delete':
				// Delete index document action
				try {
                    acs_index_documents_delete( $key, $key_type );
                    $message = new ACS_Message( '<strong>Success</strong>: Index document deleted', '', ACS_Message::TYPE_INFO );
				}
				catch ( Exception $e ) {
					$message = new ACS_Message( '<strong>Error</strong>: %s', $e->getMessage(), ACS_Message::TYPE_ERROR );
				}
				break;
		}
	}

	return $message;
}
