jQuery(document).ready(function() {

    /**
     * Click event to scroll to bottom
     */
    jQuery(".acs_to_bottom").click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop:jQuery(this.hash).offset().top - 50}, 700);
    });

    /**
     * Click event to scroll to top
     */
    jQuery(".acs_to_top").click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({scrollTop : 0}, 700);
    });

    /**
     * Click event to enlarge images
     */
    jQuery(".acs_enlarge_step_image").click(function(event) {
        var image_src = jQuery(this).attr('src');

        window.open(image_src, "Step image", "width=1440, height=694, scrollbars=no");
        return false;
    });

});
