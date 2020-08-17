<?php
/**
 * Default plugin search page
 */

// Get header
get_header();

// Get current theme
$current_theme = wp_get_theme();
?>

<?php if ( $current_theme == 'Twenty Fifteen' ) : ?>
    <section id="primary" class="content-area">
    <main id="main" class="site-main acs_twentyfifteen" role="main">
<?php elseif ( $current_theme == 'Twenty Sixteen' ) : ?>
    <section id="primary" class="content-area">
    <main id="main" class="site-main acs_twentysixteen" role="main">
<?php elseif ( $current_theme == 'Twenty Seventeen' ) : ?>
    <div class="wrap acs_twentyseventeen">
<?php else : ?>
    <div id="main-content" class="main-content">
    <div id="primary" class="content-area">
    <div id="content" class="site-content acs_twenty" role="main">
<?php endif ?>

    <header class="entry-header page-header acs_search_header">
        <h1 class="entry-title acs_page_title"><?php printf( __( 'You looked for: %s', ACS::PREFIX ), get_search_query() ) ?></h1>
        <h2 class="entry-title acs_page_subtitle" id="acs_found_items">&nbsp;</h2>
    </header>

    <?php if ( $current_theme == 'Twenty Seventeen' ) : ?>
        <div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
    <?php endif ?>

    <?php if ( ACS::get_instance()->get_settings()->acs_frontpage_show_filters == 1 ): ?>
        <section class="acs_search_results_filters">
            <div class="acs_filters_box acs_filter_type">
                <label for="acs_filter_type_field" class="acs_filters_label"><?php _e( 'Type', ACS::PREFIX ) ?>:&nbsp;</label>
                <?php echo acs_draw_filter_type_fields( isset( ACS::get_instance()->get_settings()->acs_filter_type_field ) ? ACS::get_instance()->get_settings()->acs_filter_type_field : ACS::TYPE_FIELD_DEFAULT ) ?>
            </div>
            <div class="acs_filters_box acs_filter_sort">
                <label for="acs_filter_sort_field" class="acs_filters_label"><?php _e( 'Sort by', ACS::PREFIX ) ?>:&nbsp;</label>
                <?php echo acs_draw_filter_sort_fields( ACS::get_instance()->get_settings()->acs_filter_sort_field ) ?>
                <?php echo acs_draw_filter_sort_orders( ACS::get_instance()->get_settings()->acs_filter_sort_order ) ?>
            </div>
        </section>
    <?php endif ?>

    <section id="acs_search_results_container" class="acs_search_results_container">
        <div id="acs_search_results_items" class="acs_search_results_items">
            <!-- Result items -->
        </div>
        <div class="acs_search_results_status">
            <div class="ajax-loader" style="display: none;">
                <img src="<?php echo '/wp-admin/images/loading.gif' ?>" width="16" height="16" />
            </div>

            <span class="load_more" style="visibility: hidden;"><?php echo ACS::get_instance()->get_settings()->acs_results_load_more_msg ?></span>
        </div>
    </section>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            /**
             * Initialize search
             */
            ACS.init({
                keyword: '<?php echo get_search_query() ?>',
                start: 0,
                size: <?php echo ACS::get_instance()->get_settings()->acs_results_max_items ?>,
                type_field: '#acs_filter_type_field',
                sort_field: '#acs_filter_sort_field',
                sort_order: '#acs_filter_sort_order',
                container_result_items: '#acs_search_results_items',
                container_ajax_loader: '#acs_search_results_container .ajax-loader',
                container_load_more: '#acs_search_results_container .load_more'
            });
        });
    </script>

    <?php if ( $current_theme == 'Twenty Seventeen' ) : ?>
        </main>
        </div>
    <?php endif ?>

<?php if ( $current_theme == 'Twenty Fifteen' ) : ?>
    </main>
    </section>
<?php elseif ( $current_theme == 'Twenty Sixteen' ) : ?>
    </main>
    </section>
    <?php get_sidebar() ?>
<?php elseif ( $current_theme == 'Twenty Seventeen' ) : ?>
    <?php get_sidebar() ?>
    </div>
<?php else : ?>
    </div>
    </div>
    </div>
    <?php get_sidebar() ?>
<?php endif ?>

<?php
// Get footer
get_footer();
