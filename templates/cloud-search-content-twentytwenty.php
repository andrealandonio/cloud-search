<?php
/**
 * The twentytwenty template for displaying ACS content
 */

global $post, $is_post_item, $term, $is_term_item;

if ($is_term_item) {
    ?>
    <article id="acs-term-<?php echo get_term_field( 'term_id',  $term ) ?>" class="term type-term status-publish">
        <header class="entry-header has-text-align-center">
            <div class="entry-header-inner section-inner medium">
                <h2 class="entry-title heading-size-1">
                    <a href="<?php echo esc_url( get_term_link( $term ) ) ?>" rel="bookmark">
	                    <?php echo @acs_highlight_title( get_term_field( 'name',  $term ), ACS::get_instance()->get_key() ) ?>
                    </a>
                </h2>

				<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_content ): ?>
                    <div class="intro-text section-inner max-percentage thin">
						<?php echo @acs_highlight_text( strip_shortcodes( get_the_content() ) , ACS::get_instance()->get_key() ) ?>
                    </div>
				<?php endif; ?>

				<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_excerpt ): ?>
                    <div class="intro-text section-inner max-percentage thin">
						<?php echo @acs_highlight_text( strip_shortcodes( get_the_excerpt() ) , ACS::get_instance()->get_key() ) ?>
                    </div>
				<?php endif; ?>

				<?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_custom && ! empty( ACS::get_instance()->get_settings()->acs_results_custom_field ) ): ?>
                    <div class="intro-text section-inner max-percentage thin">
						<?php echo @acs_highlight_text( strip_shortcodes( get_post_meta( get_the_ID() , ACS::get_instance()->get_settings()->acs_results_custom_field, true ) ) , ACS::get_instance()->get_key() ) ?>
                    </div>
				<?php endif; ?>
            </div>
        </header>
    </article>
    <?php
}
else {
	?>
    <article id="acs-post-<?php the_ID() ?>" <?php post_class( 'post' ) ?>>
        <header class="entry-header has-text-align-center">
            <div class="entry-header-inner section-inner medium">
			    <?php
                if ( ACS::get_instance()->get_settings()->acs_results_show_fields_categories && has_category() ) {
                    ?>
                    <div class="entry-categories">
                        <span class="screen-reader-text"><?php _e( 'Categories', 'twentytwenty' ) ?></span>
                        <div class="entry-categories-inner">
			                <?php the_category( ' ' ) ?>
                        </div>
                    </div>
                    <?php
                }
			    ?>

                <h2 class="entry-title heading-size-1">
                    <a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
                        <?php echo @acs_highlight_title( get_the_title(), ACS::get_instance()->get_key() ) ?>
                    </a>
                </h2>

	            <?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_content ): ?>
                    <div class="intro-text section-inner max-percentage thin">
			            <?php echo @acs_highlight_text( strip_shortcodes( get_the_content() ) , ACS::get_instance()->get_key() ) ?>
                    </div>
	            <?php endif; ?>

                <?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_excerpt ): ?>
                    <div class="intro-text section-inner max-percentage thin">
                        <?php echo @acs_highlight_text( strip_shortcodes( get_the_excerpt() ) , ACS::get_instance()->get_key() ) ?>
                    </div>
	            <?php endif; ?>

	            <?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_custom && ! empty( ACS::get_instance()->get_settings()->acs_results_custom_field ) ): ?>
                    <div class="intro-text section-inner max-percentage thin">
	                    <?php echo @acs_highlight_text( strip_shortcodes( get_post_meta( get_the_ID() , ACS::get_instance()->get_settings()->acs_results_custom_field, true ) ) , ACS::get_instance()->get_key() ) ?>
                    </div>
                <?php endif; ?>

                <div class="post-meta-wrapper post-meta-single post-meta-single-bottom">
                    <ul class="post-meta">
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
				            ?>
                            <li class="post-author meta-wrapper">
                                <span class="meta-icon">
                                    <span class="screen-reader-text"><?php _e( 'Post author', 'twentytwenty' ); ?></span>
                                    <?php twentytwenty_the_theme_svg( 'user' ); ?>
                                </span>
                                <span class="meta-text">
                                    <?php
                                    printf(
                                    /* translators: %s: Author name */
                                        __( 'By %s', 'twentytwenty' ),
                                        '<a href="' . esc_url( $author_url ) . '">' . $author_name . '</a>'
                                    );
                                    ?>
                                </span>
                            </li>
                            <?php
			            }

			            if ( ACS::get_instance()->get_settings()->acs_results_show_fields_date ) {
                            $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

                            // Use a custom date format or default one
                            $date_format = ( ! empty ( ACS::get_instance()->get_settings()->acs_results_format_date ) ) ? ACS::get_instance()->get_settings()->acs_results_format_date : get_option( 'date_format' );

                            $time_string = sprintf( $time_string,
                                esc_attr( get_the_date( 'c' ) ),
                                get_the_date( $date_format ),
                                esc_attr( get_the_modified_date( 'c' ) ),
                                get_the_modified_date( $date_format )
                            );
                            ?>
                            <li class="post-date meta-wrapper">
                                <span class="meta-icon">
                                    <span class="screen-reader-text"><?php _e( 'Post date', 'twentytwenty' ); ?></span>
                                    <?php twentytwenty_the_theme_svg( 'calendar' ); ?>
                                </span>
                                <span class="meta-text">
                                    <a href="<?php the_permalink() ?>"><?php echo $time_string ?></a>
                                </span>
                            </li>
                            <?php
			            }

			            if ( ACS::get_instance()->get_settings()->acs_results_show_fields_tags ) {
				            $tags_list = get_the_tag_list( '', __( ', ', 'twentytwenty' ) );
				            if ( $tags_list ) {
				                ?>
                                <li class="post-tags meta-wrapper">
                                    <span class="meta-icon">
                                        <span class="screen-reader-text"><?php _e( 'Tags', 'twentytwenty' ); ?></span>
                                        <?php twentytwenty_the_theme_svg( 'tag', 16 ); ?>
                                    </span>
                                    <span class="meta-text">
                                        <?php echo $tags_list ?>
                                    </span>
                                </li>
                                <?php
				            }
			            }

			            if ( ACS::get_instance()->get_settings()->acs_results_show_fields_formats ) {
				            $format = get_post_format();
				            if ( current_theme_supports( 'post-formats', $format ) ) {
				                ?>
                                <li class="post-tags meta-wrapper">
                                    <span class="meta-icon">
                                        <span class="screen-reader-text"><?php _e( 'Formats', 'twentytwenty' ); ?></span>
                                        <?php twentytwenty_the_theme_svg( 'tag', 16 ); ?>
                                    </span>
                                    <span class="meta-text">
                                        <?php echo get_post_format_string( $format ) ?>
                                    </span>
                                </li>
                                <?php
				            }
			            }

			            if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) && ACS::get_instance()->get_settings()->acs_results_show_fields_comments ) {
				            ?>
                            <li class="post-comment-link meta-wrapper">
                                <span class="meta-icon">
                                    <?php twentytwenty_the_theme_svg( 'comment' ); ?>
                                </span>

                                <span class="meta-text">
                                    <?php comments_popup_link(); ?>
                                </span>
                            </li>
				            <?php
			            }
			            ?>
                    </ul>
                </div>
            </div>
        </header>

        <?php if ( ACS::get_instance()->get_settings()->acs_results_show_fields_image ): ?>
            <figure class="featured-media">
                <div class="featured-media-inner section-inner medium">
                    <?php acs_post_thumbnail() ?>
                </div>
            </figure>
        <?php endif ?>

        <div class="section-inner">
			<?php
			wp_link_pages(
				array(
					'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__( 'Page', 'twentytwenty' ) . '"><span class="label">' . __( 'Pages:', 'twentytwenty' ) . '</span>',
					'after'       => '</nav>',
					'link_before' => '<span class="page-number">',
					'link_after'  => '</span>',
				)
			);
			edit_post_link();
            ?>
        </div>
    </article>
	<?php
}
?>
