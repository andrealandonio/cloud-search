jQuery(document).ready(function() {

    /**
     * Document ready operations
     */

    // Read initial values
    var content_box_type = jQuery('select#acs_frontpage_content_box_type');
    var content_box_type_value = content_box_type.val();
    acs_load_content_box_tips(content_box_type_value);

    /**
     * Submit checks
     */
    jQuery('#acs_form_setting').submit(function() {
        // If suggestion is checked, alert to select jquery if not checked
        if (jQuery('#acs_suggest_active').is(':checked')) {
            if (!jQuery('#acs_frontpage_use_jquery').is(':checked')) {
                alert(ACS_LOCALE.suggestion_activate_alert);
                return false;
            }
        }

        // If plugin search page is checked, suggest to select jquery and plugin based content box
        if (jQuery('#acs_frontpage_use_plugin_search_page').is(':checked')) {
            if (jQuery('select#acs_frontpage_content_box_type').val() != 'plugin' || !jQuery('#acs_frontpage_use_jquery').is(':checked')) {
                return confirm(ACS_LOCALE.plugin_search_page_suggest);
            }
        }
    });

    /**
     * Manage change content box type select
     */
    content_box_type.change(function() {
        var value = jQuery(this).val();
        acs_load_content_box_tips(value);
    });

    /**
     * Load content box tips
     */
    function acs_load_content_box_tips(value) {
        var message = '';
        var hide = false;

        // Customize message according to the selected one
        switch (value) {
            case 'custom':
                message = ACS_LOCALE.content_box_type_type_custom;
                break;
            case 'format':
                message = ACS_LOCALE.content_box_type_type_format;
                break;
            case 'plugin':
                message = ACS_LOCALE.content_box_type_type_plugin;
                hide = true;
                break;
            default:
                message = ACS_LOCALE.content_box_type_type_default;
                hide = true;
                break;
        }

        // Show message and manage input box
        jQuery('div#acs_frontpage_content_box_tips span').html(message);
        if (hide) {
            jQuery('div#acs_frontpage_content_box_tips input').hide();
        }
        else {
            jQuery('div#acs_frontpage_content_box_tips input').show();
        }
    }
});
