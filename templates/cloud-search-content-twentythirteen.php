<?php
/**
 * The twentythirteen template for displaying ACS content
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
        <header class="entry-header">
			<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_image ): ?>
				<?php acs_post_thumbnail() ?>
			<?php endif ?>

            <h1 class="entry-title">
                <a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
					<?php echo @acs_highlight_title( get_the_title(), ACS::get_instance()->get_key() ) ?>
                </a>
            </h1>

            <div class="entry-meta">
				<?php
				if ( is_sticky() && ACS::get_instance()->get_settings()->acs_results_show_fields_sticky )
					echo '<span class="featured-post">' . __( 'Sticky', 'twentythirteen' ) . '</span>';

				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_date && ACS::get_instance()->get_settings()->acs_results_show_fields_author ) {
					// Use a custom date format or default one
					$date_format = ( ! empty ( $settings->acs_results_format_date ) ) ? $settings->acs_results_format_date : get_option( 'date_format' );

					echo sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
						esc_url( get_permalink() ),
						esc_attr( sprintf( __( 'Permalink to %s', 'twentythirteen' ), the_title_attribute( 'echo=0' ) ) ),
						esc_attr( get_the_date( 'c' ) ),
						esc_html( get_the_date( $date_format ) )
					);
				}

				// Translators: used between list items, there is a space after the comma.
				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_categories ) {
					$categories_list = get_the_category_list( __( ', ', 'twentythirteen' ) );
					if ( $categories_list ) {
						echo '<span class="categories-links">' . $categories_list . '</span>';
					}
				}

				// Translators: used between list items, there is a space after the comma.
				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_tags ) {
					$tag_list = get_the_tag_list( '', __( ', ', 'twentythirteen' ) );
					if ( $tag_list ) {
						echo '<span class="tags-links">' . $tag_list . '</span>';
					}
				}

				// Post author
				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_author ) {
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

					printf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
						esc_url( ( ! empty( $author_url ) ) ? esc_url( $author_url ) : 'javascript:void(0)' ),
						esc_attr( sprintf( __( 'View all posts by %s', 'twentythirteen' ), $author_name ) ),
						$author_name
					);
				}
				?>
				<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ) ?>
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

        <footer class="entry-meta">
			<?php if ( comments_open() && ! is_single() && ACS::get_instance()->get_settings()->acs_results_show_fields_comments ) : ?>
                <div class="comments-link">
					<?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'twentythirteen' ) . '</span>', __( 'One comment so far', 'twentythirteen' ), __( 'View all % comments', 'twentythirteen' ) ) ?>
                </div>
			<?php endif ?>
        </footer>
    </article>
	<?php
}
?>
