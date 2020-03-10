ACS = {
    init: function(params) {
        // Initialize search manager
        ACS.params = {};
        ACS.params.keyword = params.keyword;
        ACS.params.start = params.start;
        ACS.params.size = params.size;
        ACS.params.type_field = params.type_field;
        ACS.params.sort_field = params.sort_field;
        ACS.params.sort_order = params.sort_order;
        ACS.params.container_result_items = params.container_result_items;
        ACS.params.container_load_more = params.container_load_more;
        ACS.params.container_ajax_loader = params.container_ajax_loader;

        // Perform first search
        ACS.search();

        // Add load more event listener
        jQuery(this.params.container_load_more).click(function(e) {
            // Prevent defaults
            e.preventDefault();

            // Perform new search
            ACS.search();
        });

        // Add type field filter change event listener
        jQuery(this.params.type_field).change(function(e) {
            // Prevent defaults
            e.preventDefault();

            // Perform a reset and new search
            ACS.reset();
            ACS.search();
        });

        // Add sort field filter change event listener
        jQuery(this.params.sort_field).change(function(e) {
            // Prevent defaults
            e.preventDefault();

            // Perform a reset and new search
            ACS.reset();
            ACS.search();
        });

        // Add sort order filter change event listener
        jQuery(this.params.sort_order).change(function(e) {
            // Prevent defaults
            e.preventDefault();

            // Perform a reset and new search
            ACS.reset();
            ACS.search();
        });
    },
    reset: function() {
        // Reset results and pagination value
        jQuery(ACS.params.container_result_items).html('');
        ACS.params.start = 0;
    },
    search: function() {
        // Hide load more and show AJAX loader
        jQuery(ACS.params.container_load_more).css('visibility', 'hidden');
        jQuery(ACS.params.container_ajax_loader).show();

        // Read filters
        var filter_type_field = jQuery(ACS.params.type_field).val();
        var filter_sort_field = jQuery(ACS.params.sort_field).val();
        var filter_sort_order = jQuery(ACS.params.sort_order).val();

        // Perform AJAX call
        jQuery.ajax({
            url: acs_config.ajax_url,
            type: 'get',
            dataType: 'json',
            data: {
                action: 'acs_search_documents',
                keyword: ACS.params.keyword,
                start: ACS.params.start,
                size: ACS.params.size,
                type_field: filter_type_field,
                sort_field: filter_sort_field,
                sort_order: filter_sort_order
            },
            success: function (result) {
                // Update start point
                ACS.params.start = result.data.start;

                // Hide AJAX loader
                jQuery(ACS.params.container_ajax_loader).hide();

                if (result.data.load_more) {
                    // Show load more if there are other results
                    jQuery(ACS.params.container_load_more).css('visibility', 'visible');
                }

                if (result.data.found > 0) {
                    // Append search results
                    jQuery(ACS.params.container_result_items).append(result.data.items);
                }
                else {
                    // No results found
                    jQuery(ACS.params.container_result_items).append(result.data.items);

                    // Add 'no_results' CSS class
                    jQuery(ACS.params.container_result_items).addClass('no_results');
                }

                // Update items found
                var acs_found_items = jQuery('#acs_found_items');
                if (acs_found_items.length > 0) {
                    var acs_found_items_message = '';

                    switch (result.data.found) {
                        case 0: acs_found_items_message = ACS_LOCALE.found_items_none;
                            break;
                        case 1: acs_found_items_message = ACS_LOCALE.found_items_one;
                            break;
                        default: acs_found_items_message = result.data.found + ' ' + ACS_LOCALE.found_items_many;
                            break;
                    }

                    // Show message
                    acs_found_items.html(acs_found_items_message);
                }
            }
        });
    }
};