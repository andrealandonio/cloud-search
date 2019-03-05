<?php
/**
 * The default template for displaying ACS content
 */

global $post, $is_post_item, $term, $is_term_item;

if ($is_term_item) {
	?>
    <article id="acs-term-<?php echo get_term_field( 'term_id',  $term ) ?>" class="term type-term status-publish">
        <header class="entry-header">
            <h2 class="entry-title">
                <a href="<?php echo esc_url( get_term_link( $term ) ) ?>" rel="bookmark">
					<?php echo @acs_highlight_title( get_term_field( 'name',  $term ), ACS::get_instance()->get_key() ) ?>
                </a>
            </h2>
        </header>

		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_terms_content ): ?>
            <div class="entry-summary">
				<?php echo @acs_highlight_text( acs_truncate( strip_shortcodes( get_term_field( 'description',  $term ) ) ), ACS::get_instance()->get_key() ) ?>
            </div>

            <br/>
		<?php endif ?>

		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_terms_custom && ! empty( ACS::get_instance()->get_settings()->acs_results_custom_terms_field ) && function_exists( 'get_term_meta' ) ): ?>
            <div class="entry-summary">
				<?php echo @strip_shortcodes( get_term_meta( $term->term_id , ACS::get_instance()->get_settings()->acs_results_custom_terms_field, true ) ) ?>
            </div>

            <br/>
		<?php endif ?>
    </article>
	<?php
}
else {
	?>
    <article id="acs-post-<?php the_ID() ?>" <?php post_class() ?>>
        <div class="acs-image">
			<?php acs_post_thumbnail() ?>
        </div>

        <header class="entry-header">
            <h2 class="entry-title">
                <a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
					<?php echo @acs_highlight_title( get_the_title(), ACS::get_instance()->get_key() ) ?>
                </a>
            </h2>
        </header>

		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_content ): ?>
            <div class="entry-summary">
				<?php echo @acs_highlight_text( acs_truncate( strip_shortcodes( get_the_content() ) ), ACS::get_instance()->get_key() ) ?>
            </div>

            <br/>
		<?php endif ?>

		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_excerpt ): ?>
            <div class="entry-summary">
				<?php echo @acs_highlight_text( strip_shortcodes( get_the_excerpt() ), ACS::get_instance()->get_key() ) ?>
            </div>

            <br/>
		<?php endif ?>

		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_custom && ! empty( ACS::get_instance()->get_settings()->acs_results_custom_field ) ): ?>
            <div class="entry-summary">
				<?php echo @acs_highlight_text( strip_shortcodes( get_post_meta( get_the_ID() , ACS::get_instance()->get_settings()->acs_results_custom_field, true ) ) , ACS::get_instance()->get_key() ) ?>
            </div>

            <br/>
		<?php endif ?>

		<?php if ( 'post' == get_post_type() ) : ?>

            <footer class="entry-meta">
				<?php acs_post_meta() ?>
            </footer>

		<?php else : ?>

            <footer class="entry-meta acs-cpt-footer">
				<?php acs_post_meta() ?>
            </footer>

		<?php endif ?>
    </article>
	<?php
}
?>
