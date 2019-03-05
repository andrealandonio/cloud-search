<?php
/**
 * The twentyfourteen template for displaying ACS content
 */

global $post, $is_post_item, $term, $is_term_item;

if ($is_term_item) {
	?>
    <article id="acs-term-<?php echo get_term_field( 'term_id',  $term ) ?>" class="term type-term status-publish">
        <header class="entry-header">
            <h1 class="entry-title">
                <a href="<?php echo esc_url( get_term_link( $term ) ) ?>" rel="bookmark">
					<?php echo @acs_highlight_title( get_term_field( 'name',  $term ), ACS::get_instance()->get_key() ) ?>
                </a>
            </h1>
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
    <article id="acs-post-<?php the_ID() ?>" <?php post_class( 'post' ) ?>>
		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_image ): ?>
			<?php acs_post_thumbnail( 'twentyfourteen-full-width' ) ?>
		<?php endif ?>

        <header class="entry-header">
			<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_categories ) : ?>
                <div class="entry-meta">
                    <span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfourteen' ) ) ?></span>
                </div>
			<?php endif	?>

            <h1 class="entry-title">
                <a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
					<?php echo @acs_highlight_title( get_the_title(), ACS::get_instance()->get_key() ) ?>
                </a>
            </h1>

            <div class="entry-meta">
				<?php
				if ( is_sticky() && ACS::get_instance()->get_settings()->acs_results_show_fields_sticky ) {
					echo '<span class="featured-post">' . __( 'Sticky', 'twentyfourteen' ) . '</span>';
				}

				// Set up and print post meta information.
				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_date && ACS::get_instance()->get_settings()->acs_results_show_fields_author ) {
					// Use a custom date format or default one
					$date_format = ( ! empty ( $settings->acs_results_format_date ) ) ? $settings->acs_results_format_date : get_option( 'date_format' );

					// Use a custom author format or default one
					switch ( ACS::get_instance()->get_settings()->acs_results_format_author ) {
						case 'display_name_with_link':
							$author_name = get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) );
							$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
							break;
						case 'display_name_without_link':
							$author_name = get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) );
							$author_url = '';
							break;
						case 'full_name_with_link':
							$author_name = get_the_author_meta( 'first_name', get_the_author_meta( 'ID' ) ) . ' ' . get_the_author_meta( 'last_name', get_the_author_meta( 'ID' ) );
							$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
							break;
						case 'full_name_without_link':
							$author_name = get_the_author_meta( 'first_name', get_the_author_meta( 'ID' ) ) . ' ' . get_the_author_meta( 'last_name', get_the_author_meta( 'ID' ) );
							$author_url = '';
							break;
						case 'username_with_link':
							$author_name = get_the_author_meta( 'user_login', get_the_author_meta( 'ID' ) );
							$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
							break;
						case 'username_without_link':
							$author_name = get_the_author_meta( 'user_login', get_the_author_meta( 'ID' ) );
							$author_url = '';
							break;
						default:
							$author_name = get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) );
							$author_url = get_author_posts_url( get_the_author_meta( 'ID' ) );
							break;
					}

					printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>',
						esc_url( get_permalink() ),
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date( $date_format ) ),
						esc_url( ( ! empty( $author_url ) ) ? esc_url( $author_url ) : 'javascript:void(0)' ),
						$author_name
					);
				}

				if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) && ACS::get_instance()->get_settings()->acs_results_show_fields_comments ) :
					?>
                    <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '1 Comment', 'twentyfourteen' ), __( '% Comments', 'twentyfourteen' ) ) ?></span>
				<?php
				endif;

				edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
				?>
            </div>
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

		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_tags ): ?>
			<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ) ?>
		<?php endif ?>
    </article>
	<?php
}
?>
