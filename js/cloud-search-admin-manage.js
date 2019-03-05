jQuery(document).ready(function() {

    /**
     * Global vars
     */
    var action_in_progress = false;
    var operation_name = '-';

    var timeout_load_searchable_documents = 60000;
    var timeout_load_site_documents = 60000;
    var timeout_load_fields = 90000;
    var timeout_load_status = 40000;
    var timeout_async_action = 2000;

    /**
     * Document ready operations
     */

    // Read options
    var option_status = jQuery('#acs_option_status').val();
    var option_items = jQuery('#acs_option_items').val();

    // Check if there is a pending action
    if (option_status === 10 && option_items !== -1) {
        // Load sync documents action
        acs_load_async_action('acs_index_documents_sync', true);
    }
    else if (option_status === 20 && option_items !== -1) {
        // Load delete documents action
        acs_load_async_action('acs_index_documents_delete', true);
    }
    else {
        // No operation in progress, hide loading and stop button
        jQuery('th.field_index_operation_head span.loading').hide();
        jQuery('th.field_index_operation_head span.stop').hide();
    }

    /**
     * Check confirm before form submit
     */
    jQuery('.check_confirm').submit(function() {
        var message = jQuery(this).data('message_check_confirm');
        return confirm(message);
    });

    /**
     * Check if key field is empty
     */
    jQuery('.check_key_empty').submit(function() {
        var message = jQuery(this).data('message_check_key_empty');
        var key = jQuery(this).find('.acs_form_action_key').val();

        if (key === '') {
            // If key is empty provide an alert message and stop form submit
            alert(message);
            return false;
        }
    });

    /**
     * Check if update keys field is empty
     */
    jQuery('.check_update_keys_empty').submit(function() {
        var message = jQuery(this).data('message_check_update_keys_empty');
        var id = jQuery(this).find('.acs_form_action_update_id').val();
        var key = jQuery(this).find('.acs_form_action_update_key').val();
        var value = jQuery(this).find('.acs_form_action_update_value').val();

        if (id === '' || key === '' || value === '' ) {
            // If al least one update keys is empty provide an alert message and stop form submit
            alert(message);
            return false;
        }
    });

    /**
     * Manage reload button action
     */
    jQuery('.acs_reload').click(function() {
        var function_name = jQuery(this).data('fn');
        switch (function_name) {
            case 'searchable_documents': acs_load_searchable_documents();
                break;
            case 'site_documents': acs_load_site_documents();
                break;
            case 'fields': acs_load_fields();
                break;
            case 'status': acs_load_status();
                break;
            default:
                break;
        }
    });

    /**
     * Manage stop button action
     */
    jQuery('.acs_stop').click(function() {
        var function_name = jQuery(this).data('fn');
        switch (function_name) {
            case 'stop_operations': acs_stop_sync_action();
                break;
            default:
                break;
        }
    });

    /**
     * Manage sync button action
     */
    jQuery('.acs_sync_action').submit(function() {
        if (action_in_progress) {
            // Action already in progress, block request
            alert(ACS_LOCALE.operation_in_progress);
            return false;
        }
        return true;
    });

    /**
     * Manage async button action
     */
    jQuery('.acs_async_action').click(function() {
        if (!action_in_progress) {
            // No actions in progress

            // Get confirm message
            var message = jQuery(this).data('message');

            if (confirm(message)) {
                // Check if user confirm is valid
                var action = jQuery(this).data('action');

                // Load async action (first time)
                acs_load_async_action(action, true);
            }
        }
        else {
            // Action already in progress, block other requests
            alert(ACS_LOCALE.operation_in_progress);
        }
    });

    // Check if there is no index configuration error, then schedule async functions
    if (!jQuery('.field_index_error').length) {
        // Load index status (first time)
        acs_load_searchable_documents();
        acs_load_site_documents();
        acs_load_fields();
        acs_load_status();

        // Reload searchable documents every 60s
        var acs_load_searchable_documents_function = setInterval(function() {
            acs_load_searchable_documents();
        }, timeout_load_searchable_documents);

        // Reload site documents every 60s
        var acs_load_site_documents_function = setInterval(function() {
            acs_load_site_documents();
        }, timeout_load_site_documents);

        // Reload fields every 90s
        var acs_load_fields_function = setInterval(function() {
            acs_load_fields();
        }, timeout_load_fields);

        // Reload status every 40s
        var acs_load_status_function = setInterval(function() {
            acs_load_status();
        }, timeout_load_status);
    }

    /**
     * Load searchable documents
     */
    function acs_load_searchable_documents() {
        var message = '';

        // Hide message and show loading
        jQuery('td.field_index_searchable_documents span.message').hide();
        jQuery('td.field_index_searchable_documents span.loading').show();

        // Call index searchable documents hooks
        jQuery.ajax({
            url: acs_config.ajax_url,
            type: 'get',
            dataType: 'json',
            data: {
                action: 'acs_index_searchable_documents',
                nonce: acs_config.nonce
            },
            success: function(response) {
                // Check if response is valid
                if (response !== undefined && response.data && response.actions && response.code === 'ok') {
                    message = response.data.found;

                    // Hide loading and show message
                    jQuery('td.field_index_searchable_documents span.loading').hide();
                    jQuery('td.field_index_searchable_documents span.message').html(message).show();
                }
                else if (response !== undefined && response.data && response.actions && response.code === 'error') {
                    message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_searchable_documents + ': ' +  response.message + '</span>';

                    // Hide loading and show message
                    jQuery('td.field_index_searchable_documents span.loading').hide();
                    jQuery('td.field_index_searchable_documents span.message').html(message).show();

                    // Clear reload interval
                    clearInterval(acs_load_searchable_documents_function);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_searchable_documents + ': ' +  textStatus + '</span>';

                // Hide loading and show message
                jQuery('td.field_index_searchable_documents span.loading').hide();
                jQuery('td.field_index_searchable_documents span.message').html(message).show();
            }
        });
    }

    /**
     * Load site documents
     */
    function acs_load_site_documents() {
        var message = '';

        // Hide message and show loading
        jQuery('td.field_index_site_documents span.message').hide();
        jQuery('td.field_index_site_documents span.loading').show();

        // Call index site documents hooks
        jQuery.ajax({
            url: acs_config.ajax_url,
            type: 'get',
            dataType: 'json',
            data: {
                action: 'acs_index_site_documents',
                nonce: acs_config.nonce
            },
            success: function(response) {
                // Check if response is valid
                if (response !== undefined && response.data && response.actions && response.code === 'ok') {
                    message = response.data.found;

                    // Hide loading and show message
                    jQuery('td.field_index_site_documents span.loading').hide();
                    jQuery('td.field_index_site_documents span.message').html(message).show();
                }
                else if (response !== undefined && response.data && response.actions && response.code === 'error') {
                    message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_site_documents + ': ' +  response.message + '</span>';

                    // Hide loading and show message
                    jQuery('td.field_index_site_documents span.loading').hide();
                    jQuery('td.field_index_site_documents span.message').html(message).show();

                    // Clear reload interval
                    clearInterval(acs_load_site_documents_function);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_site_documents + ': ' +  textStatus + '</span>';

                // Hide loading and show message
                jQuery('td.field_index_site_documents span.loading').hide();
                jQuery('td.field_index_site_documents span.message').html(message).show();
            }
        });
    }

    /**
     * Load index fields
     */
    function acs_load_fields() {
        var message = '';

        // Hide message and show loading
        jQuery('td.field_index_fields span.message').hide();
        jQuery('td.field_index_fields span.loading').show();

        // Call index fields hooks
        jQuery.ajax({
            url: acs_config.ajax_url,
            type: 'get',
            dataType: 'json',
            data: {
                action: 'acs_index_fields',
                nonce: acs_config.nonce
            },
            success: function(response) {
                // Check if response is valid
                if (response !== undefined && response.data && response.actions && response.code === 'ok') {
                    message = response.data.found;

                    // Hide loading and show message
                    jQuery('td.field_index_fields span.loading').hide();
                    jQuery('td.field_index_fields span.message').html(message).show();
                }
                else if (response !== undefined && response.data && response.actions && response.code === 'error') {
                    message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_fields + ': ' +  response.message + '</span>';

                    // Hide loading and show message
                    jQuery('td.field_index_fields span.loading').hide();
                    jQuery('td.field_index_fields span.message').html(message).show();

                    // Clear reload interval
                    clearInterval(acs_load_fields_function);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_fields + ': ' +  textStatus + '</span>';

                // Hide loading and show message
                jQuery('td.field_index_fields span.loading').hide();
                jQuery('td.field_index_fields span.message').html(message).show();
            }
        });
    }

    /**
     * Load index status
     */
    function acs_load_status() {
        var message = '';

        // Hide message and show loading
        jQuery('td.field_index_status span.message').hide();
        jQuery('td.field_index_status span.loading').show();

        // Call index status hooks
        jQuery.ajax({
            url: acs_config.ajax_url,
            type: 'get',
            dataType: 'json',
            data: {
                action: 'acs_index_status',
                nonce: acs_config.nonce
            },
            success: function(response) {
                // Check if response is valid
                if (response !== undefined && response.data && response.actions && response.code === 'ok') {
                    if ( response.data.processing ) {
                        message = '<span class="acs_status_warning">' + ACS_LOCALE.status_warning + '</span>';
                    }
                    else if ( response.data.requires_index_documents ) {
                        message = '<span class="acs_status_error">' + ACS_LOCALE.status_error + '</span>';
                    }
                    else {
                        message = '<span class="acs_status_ok">' + ACS_LOCALE.status_ok + '</span>';
                    }

                    // Hide loading and show message
                    jQuery('td.field_index_status span.loading').hide();
                    jQuery('td.field_index_status span.message').html(message).show();
                }
                else if (response !== undefined && response.data && response.actions && response.code === 'error') {
                    message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_status + ': ' +  response.message + '</span>';

                    // Hide loading and show message
                    jQuery('td.field_index_status span.loading').hide();
                    jQuery('td.field_index_status span.message').html(message).show();

                    // Clear reload interval
                    clearInterval(acs_load_status_function);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                message = '<span class="acs_status_error">' + ACS_LOCALE.error_loading_status + ': ' +  textStatus + '</span>';

                // Hide loading and show message
                jQuery('td.field_index_status span.loading').hide();
                jQuery('td.field_index_status span.message').html(message).show();
            }
        });
    }

    /**
     * Load async action
     *
     * @param action
     * @param start
     */
    function acs_load_async_action(action, start) {
        action_in_progress = true;

        // Show loading and stop button
        jQuery('th.field_index_operation_head span.loading').show();
        jQuery('th.field_index_operation_head span.stop').show();

        // Call async action fields hooks
        jQuery.ajax({
            url: acs_config.ajax_url,
            type: 'get',
            dataType: 'json',
            data: {
                action: action,
                nonce: acs_config.nonce,
                start: start
            },
            success: function(response) {
                // Check if response is valid
                if (response !== undefined && (response.data.status === 10 || response.data.status === 20) && response.data.found !== -1 && response.code === 'ok') {
                    // A sync is in progress (update operation message)
                    switch (response.data.status) {
                        case 10: operation_name = ACS_LOCALE.action_sync_in_progress;
                            break;
                        case 20: operation_name = ACS_LOCALE.action_delete_in_progress;
                            break;
                        default: operation_name = '-';
                            break;
                    }
                    jQuery('td.field_index_operation span.message').html(operation_name + ' (' + ACS_LOCALE.items + ': <strong>' + response.data.found + '</strong>)').show();

                    // Other operation (continue setInterval)
                    action_in_progress = true;

                    // Show loading and stop button
                    jQuery('th.field_index_operation_head span.loading').show();
                    jQuery('th.field_index_operation_head span.stop').show();
                }
                else {
                    // No sync is in progress (update operation message)
                    jQuery('td.field_index_operation span.message').html('-').show();

                    // No other operation (stop setInterval)
                    action_in_progress = false;

                    // Hide loading and stop button
                    jQuery('th.field_index_operation_head span.loading').hide();
                    jQuery('th.field_index_operation_head span.stop').hide();
                }

                // Hide notice box
                jQuery('div.notice').hide();

                // If an action is in progress go on with execution
                if (action_in_progress) {
                    // Wait a timeout before start call
                    setTimeout(function(){
                        acs_load_async_action(action, false);
                    }, timeout_async_action);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                action_in_progress = false;

                // Hide loading and stop button
                jQuery('th.field_index_operation_head span.loading').hide();
                jQuery('th.field_index_operation_head span.stop').hide();
            }
        });
    }

    /**
     * Stop sync action
     */
    function acs_stop_sync_action() {
        action_in_progress = true;

        // Hide loading and stop button
        jQuery('th.field_index_operation_head span.loading').hide();
        jQuery('th.field_index_operation_head span.stop').hide();

        // Update operation message
        jQuery('td.field_index_operation span.message').html(ACS_LOCALE.action_stop_in_progress).show();

        // Call stop sync action fields hooks
        jQuery.ajax({
            url: acs_config.ajax_url,
            type: 'get',
            dataType: 'json',
            data: {
                action: 'acs_index_documents_stop',
                nonce: acs_config.nonce
            },
            success: function(response) {
                // Check if response is valid
                if (response !== undefined && response.code === 'ok') {
                    // No other sync is in progress (update operation message)
                    jQuery('td.field_index_operation span.message').html('-').show();

                    // No other operation (stop setInterval)
                    action_in_progress = false;

                    // Hide loading and stop button
                    jQuery('th.field_index_operation_head span.loading').hide();
                    jQuery('th.field_index_operation_head span.stop').hide();
                }
                else {
                    // A sync is in progress (update operation message)
                    action_in_progress = true;

                    // Show loading and stop button
                    jQuery('th.field_index_operation_head span.loading').show();
                    jQuery('th.field_index_operation_head span.stop').show();
                }

                // Hide notice box
                jQuery('div.notice').hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                action_in_progress = true;

                // Show loading and stop button
                jQuery('th.field_index_operation_head span.loading').show();
                jQuery('th.field_index_operation_head span.stop').show();
            }
        });
    }
});
