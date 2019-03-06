jQuery(function() {
    jQuery(acs_config_suggest.acs_suggest_selector).autocomplete({
        source: function(request, response) {
            jQuery.ajax({
                url: acs_config_suggest.ajax_url,
                dataType: 'json',
                data: {
                    action: 'acs_suggest_callback',
                    keyword: this.term
                },
                timeout: 5000,
                success: function(data) {
                    response(jQuery.map(data.results, function(item) {
                        // Found suggestions
                        return {
                            label: item.title,
                            value: item.title,
                            url: item.url
                        };
                    }));
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
        },
        delay: 400,
        timeout: 5000,
        minLength: acs_config_suggest.acs_suggest_trigger,
        autoFocus: false,
        create: function() {
            // Do nothing
        },
        search: function(event, ui) {
            // Append loading
            jQuery(event.currentTarget).addClass('acs_suggesting');
        },
        open: function(event, ui) {
            // Remove loading
            jQuery(event.target).removeClass('acs_suggesting');
        },
        response: function(event, ui) {
            // Remove loading
            jQuery(event.target).removeClass('acs_suggesting');
        },
        select: function(event, ui) {
            if (acs_config_suggest.acs_suggest_click == 'goto') {
                if (ui.item.url !== '#') {
                    location.href = ui.item.url;
                }
                else {
                    return true;
                }
            }
            else {
                return true;
            }
        },
        close: function() {
            // Do nothing
        }
    });
});