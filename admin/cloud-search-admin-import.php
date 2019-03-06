<?php
/**
 * Render admin menu import page
 */
function acs_menu_page_import() {
    // Get settings option
    $settings = ACS::get_instance()->get_settings();
    ?>
    <div class="wrap">
        <h2><?php _e( 'Import / Export', ACS::PREFIX ) ?></h2>

        <p>
            <?php _e( 'In this page you can import / export the plugin settings. This allows you to easily import the configuration into another site.', ACS::PREFIX ) ?>
        </p>

        <h4><?php _e( 'Current settings', ACS::PREFIX ) ?></h4>

        <hr />

		<table class="form-table acs_import">
			<tbody>
				<?php
                if ( isset( $settings ) && $settings != '' ) {
                    // Loop settings
                    foreach ( $settings as $key => $value ) {
                        if ( isset( $value ) && $value != '' ) {
                            echo '<tr>';
                            echo '<th>' . $key . '</th>';
                            if ( $key == 'acs_schema_fields' || $key == 'acs_schema_types' || $key == 'acs_schema_taxonomies'  || $key == 'acs_schema_searchable_taxonomies' ) {
                                $fields_value = explode( ',', $value );
                                $count = 0;
                                echo '<td><table class="group"><tr>';
                                foreach ( $fields_value as $site_field_value ) {
                                    echo '<td>' . $site_field_value . '</td>';
                                    $count ++;
                                    // Wrap columns
                                    if ( $count == 5 ) {
                                        echo '</tr><tr>';
                                        $count = 0;
                                    }
                                }
                                echo '</tr></table></td>';
                            }
                            else if ( $value == 1 ) {
                                echo '<td>' . __( 'Yes', ACS::PREFIX ) . '</td>';
                            }
                            else {
                                echo '<td>' . $value . '</td>';
                            }
                            echo '</tr>';
                        }
                    }
                }
                else {
                    echo '<tr><td>' . __( 'No settings found', ACS::PREFIX ) . '</td></tr>';
                }
				?>
			</tbody>
		</table>

		<hr />

		<div class="metabox-holder">
			<div class="postbox">
				<h3><span><?php _e( 'Export' ) ?></span></h3>
				<div class="inside">
					<p>
                        <?php _e( 'Export plugin settings for this site as a JSON file. This allows you to easily import the configuration into another site.' ) ?>
                    </p>
					<form method="post">
						<p><input type="hidden" name="<?php echo ACS::ACTION_SETTINGS ?>" value="<?php echo ACS::ACTION_EXPORT ?>" /></p>
						<p>
							<?php wp_nonce_field( ACS::NONCE_SETTINGS_EXPORT, ACS::NONCE_SETTINGS_EXPORT ) ?>
							<?php submit_button( __( 'Export' ), 'secondary', 'submit', false ) ?>
						</p>
					</form>
				</div>
			</div>

			<div class="postbox">
				<h3><span><?php _e( 'Import' ) ?></span></h3>
				<div class="inside">
					<p>
                        <?php _e( 'Import plugin settings from a JSON file. This file can be obtained by exporting the settings on another site using the form above.' ) ?>
                    </p>
					<form method="post" enctype="multipart/form-data">
						<p><input type="file" name="import_file" /></p>
						<p>
							<input type="hidden" name="<?php echo ACS::ACTION_SETTINGS ?>" value="<?php echo ACS::ACTION_IMPORT ?>" />
							<?php wp_nonce_field( ACS::NONCE_SETTINGS_IMPORT, ACS::NONCE_SETTINGS_IMPORT ) ?>
							<?php submit_button( __( 'Import' ), 'secondary', 'submit', false ) ?>
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
    <?php
}

/**
 * Manage admin menu search page actions
 *
 * @return ACS_Message|null
 */
function acs_manage_menu_page_import_actions() {
	$message = null;

	if ( isset( $_POST[ 'acs_form_action_command' ] ) ) {
		// Get command value
		$command = filter_var ( $_POST[ 'acs_form_action_command' ], FILTER_SANITIZE_STRING );
		$key_type = filter_var ( $_POST[ 'acs_form_action_key_type' ], FILTER_SANITIZE_STRING );
		$key = filter_var ( $_POST[ 'acs_form_action_key' ], FILTER_SANITIZE_STRING );

		switch ( $command ) {
			case 'index-documents-search':
				// Connection test action
				try {
					$result = acs_index_documents_search( $key, $key_type, 0, ACS::SEARCH_RETURN_FULL_ITEMS );
					$message = new ACS_Message( '<strong>Success</strong>: Founded %s documents on index', $result->get_data()[ 'found' ], ACS_Message::TYPE_INFO );
				}
				catch ( Exception $e ) {
					$message = new ACS_Message( '<strong>Error</strong>: %s', $e->getMessage(), ACS_Message::TYPE_ERROR );
				}
				break;
		}
	}

	return $message;
}
