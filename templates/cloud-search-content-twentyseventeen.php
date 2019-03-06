<?php
/**
 * The twentyseventeen template for displaying ACS content
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
			<?php
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

				echo '<div class="entry-meta">';

				printf( '<a href="%1$s" rel="bookmark">%2$s</a>',
					esc_url( get_permalink() ),
					$time_string
				);

				twentyseventeen_edit_link();

				echo '</div>';
			}
			?>

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
				<?php echo @acs_highlight_text( strip_shortcodes( get_the_excerpt() ) , ACS::get_instance()->get_key() ) ?>
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

            <div class="entry-meta">
				<?php
				if ( 'post' === get_post_type() ) {
					if ( ACS::get_instance()->get_settings()->acs_results_show_fields_categories ) {
						$categories_list = get_the_category_list(_x(', ', 'Used between list items, there is a space after the comma.', 'twentyseventeen'));
						if ($categories_list) {
							printf('<br/><span class="cat-links acs-cat-links" style="padding-left: 0"><span class="screen-reader-text">%1$s </span>%2$s</span>',
								_x('Categories', 'Used before category names.', 'twentyseventeen'),
								$categories_list
							);
						}
					}

					if ( ACS::get_instance()->get_settings()->acs_results_show_fields_tags ) {
						$tags_list = get_the_tag_list('', _x(', ', 'Used between list items, there is a space after the comma.', 'twentyseventeen'));
						if ($tags_list) {
							printf('<br/><span class="tags-links acs-tags-links" style="padding-left: 0"><span class="screen-reader-text">%1$s </span>%2$s</span>',
								_x('Tags', 'Used before tag names.', 'twentyseventeen'),
								$tags_list
							);
						}
					}
				}

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

					$author_avatar_size = apply_filters( 'twentyseventeen_author_avatar_size', 49 );
					printf( '<br/><br/><span class="author vcard">%1$s<span class="screen-reader-text">%2$s </span><br/><a class="url fn n" href="%3$s">%4$s</a></span>',
						get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size ),
						_x( 'Author', 'Used before post author name.', 'twentyseventeen' ),
						esc_url( ( ! empty( $author_url ) ) ? esc_url( $author_url ) : 'javascript:void(0)' ),
						$author_name
					);
				}

				if ( ACS::get_instance()->get_settings()->acs_results_show_fields_formats ) {
					$format = get_post_format();
					if ( current_theme_supports( 'post-formats', $format ) ) {
						printf('<br/><span">%1$s<a href="%2$s">%3$s</a></span>',
							sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentyseventeen' ) ),
							esc_url( get_post_format_link( $format ) ),
							get_post_format_string( $format )
						);
					}
				}

				if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) && ACS::get_instance()->get_settings()->acs_results_show_fields_comments ) {
					echo '<br/><span class="entry-meta comments-link">';
					comments_popup_link( sprintf( __( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'twentyseventeen' ), get_the_title() ) );
					echo '</span><br/><br/>';
				}
				?>
            </div>

		<?php endif ?>
    </article>
	<?php
}
?>
