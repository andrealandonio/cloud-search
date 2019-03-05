<?php
/**
 * The twentysixteen template for displaying ACS content
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
    <article id="acs-post-<?php the_ID() ?>" <?php post_class( 'post' ) ?>>
        <header class="entry-header">
            <h2 class="entry-title">
                <a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
					<?php echo @acs_highlight_title( get_the_title(), ACS::get_instance()->get_key() ) ?>
                </a>
            </h2>
        </header>

		<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_image ): ?>
			<?php acs_post_thumbnail() ?>
		<?php endif ?>

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

		<?php if ( 'post' === get_post_type() ) : ?>

            <footer class="entry-footer">
				<?php
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

					$author_avatar_size = apply_filters( 'twentysixteen_author_avatar_size', 49 );
					printf( '<span class="byline"><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span> <a class="url fn n" href="%3$s">%4$s</a></span></span>',
						get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
						_x( 'Author', 'Used before post author name.', 'twentysixteen' ),
						esc_url( ( ! empty( $author_url ) ) ? esc_url( $author_url ) : 'javascript:void(0)' ),
						$author_name
					);
				}

				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_date ) {
					$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

					if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
						$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
					}

					// Use a custom date format or default one
					$date_format = ( ! empty ( ACS::get_instance()->get_settings()->acs_results_format_date ) ) ? ACS::get_instance()->get_settings()->acs_results_format_date : get_option( 'date_format' );

					$time_string = sprintf( $time_string,
						esc_attr( get_the_date( 'c' ) ),
						get_the_date( $date_format ),
						esc_attr( get_the_modified_date( 'c' ) ),
						get_the_modified_date( $date_format )
					);

					printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span> ',
						_x( 'Posted on', 'Used before publish date.', ACS::PREFIX ),
						esc_url( get_permalink() ),
						$time_string
					);
				}

				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_formats ) {
					$format = get_post_format();
					if ( current_theme_supports( 'post-formats', $format ) ) {
						printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
							sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentysixteen' ) ),
							esc_url( get_post_format_link( $format ) ),
							get_post_format_string( $format )
						);
					}
				}

				if ( 'post' === get_post_type() ) {
					if ( ACS::get_instance()->get_settings()->acs_results_show_fields_categories ) {
						$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentysixteen' ) );
						if ( $categories_list && twentysixteen_categorized_blog() ) {
							printf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
								_x( 'Categories', 'Used before category names.', 'twentysixteen' ),
								$categories_list
							);
						}
					}

					if ( ACS::get_instance()->get_settings()->acs_results_show_fields_tags ) {
						$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'twentysixteen' ) );
						if ( $tags_list ) {
							printf('<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
								_x( 'Tags', 'Used before tag names.', 'twentysixteen' ),
								$tags_list
							);
						}
					}
				}

				if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) && ACS::get_instance()->get_settings()->acs_results_show_fields_comments ) {
					echo '<span class="comments-link">';
					comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentysixteen' ), get_the_title() ) );
					echo '</span>';
				}
				?>
				<?php
				edit_post_link(
					sprintf(
					/* translators: %s: Name of current post */
						__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
						get_the_title()
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
            </footer>

		<?php else : ?>

			<?php
			edit_post_link(
				sprintf(
				/* translators: %s: Name of current post */
					__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
					get_the_title()
				),
				'<footer class="entry-footer"><span class="edit-link">',
				'</span></footer>'
			);
			?>

		<?php endif ?>
    </article>
	<?php
}
?>
