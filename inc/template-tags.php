<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Clean Blog
 */

if ( ! function_exists( 'cleanblog_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function cleanblog_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<ul class="pager">
		<?php if ( get_next_posts_link() ) : ?>
		<li class="next"><?php next_posts_link( esc_html__( 'Older posts', 'cleanblog' ) ); ?></li>
		<?php endif; ?>
		<?php if ( get_previous_posts_link() ) : ?>
		<li class="previous"><?php previous_posts_link( esc_html__( 'Newer posts', 'cleanblog' ) ); ?></li>
		<?php endif; ?>
	</ul>
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'cleanblog' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'cleanblog_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function cleanblog_posted_on() {
	$author_id;
	if (is_singular()) {
		$author_id = get_queried_object()->post_author;
	}

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'on %s', 'post date', 'cleanblog' ),
		$time_string
	);

	$byauthor = sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta('ID', $author_id) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'cleanblog' ), get_the_author_meta("display_name", $author_id) ) ),
		get_the_author_meta("display_name", $author_id)
	);

	$byline = sprintf(
		esc_html_x( 'Posted by %s', 'posted by', 'cleanblog' ),
		$byauthor
	);

	echo '<span class="byline"> ' . $byline . '</span> <span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'cleanblog_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function cleanblog_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'cleanblog' ) );
		if ( $categories_list && cleanblog_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s &nbsp;&middot;&nbsp; ', 'cleanblog' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'cleanblog' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'cleanblog' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'cleanblog' ), esc_html__( '1 Comment', 'cleanblog' ), esc_html__( '% Comments', 'cleanblog' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'cleanblog' ), '<span class="edit-link"> &nbsp;&middot;&nbsp; ', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'cleanblog' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'cleanblog' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'cleanblog' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'cleanblog' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'cleanblog' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'cleanblog' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'cleanblog' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'cleanblog' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'cleanblog' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = esc_html_x( 'Asides', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = esc_html_x( 'Galleries', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = esc_html_x( 'Images', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = esc_html_x( 'Videos', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = esc_html_x( 'Quotes', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = esc_html_x( 'Links', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = esc_html_x( 'Statuses', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = esc_html_x( 'Audio', 'post format archive title', 'cleanblog' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = esc_html_x( 'Chats', 'post format archive title', 'cleanblog' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'cleanblog' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( esc_html__( '%1$s: %2$s', 'cleanblog' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'cleanblog' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;  // WPCS: XSS OK.
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;  // WPCS: XSS OK.
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function cleanblog_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'cleanblog_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'cleanblog_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so cleanblog_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so cleanblog_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in cleanblog_categorized_blog.
 */
function cleanblog_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'cleanblog_categories' );
}
add_action( 'edit_category', 'cleanblog_category_transient_flusher' );
add_action( 'save_post',     'cleanblog_category_transient_flusher' );

if ( ! function_exists( 'cleanblog_header' ) ) :
/**
 * Custom header codes for the home page, single posts and pages
 */
function cleanblog_header() { ?>

	<?php if( is_single() ) { ?>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
	<?php
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	?>
    <header class="intro-header" style="background-color: <?php echo get_theme_mod( 'cleanblog_header_background_color' ); ?>; background-image: url('<?php echo $feat_image; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1><?php single_post_title(); ?></h1>
			<?php if ( function_exists( 'the_subtitle' ) ) {
				the_subtitle( '<h2 class="subheading">', '</h2>' );
			} ?>
                        <span class="meta"><?php cleanblog_posted_on(); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

	<?php } elseif ( is_page_template( 'page-builder.php' ) ) { ?>

	<?php } elseif ( is_page() ) { ?>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
	<?php
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	?>
    <header class="intro-header" style="background-color: <?php echo get_theme_mod( 'cleanblog_header_background_color' ); ?>; background-image: url('<?php echo $feat_image; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1><?php single_post_title(); ?></h1>
                        <hr class="small">
			<?php if ( function_exists( 'the_subtitle' ) ) {
				the_subtitle( '<span class="subheading">', '</span>' );
			} ?>
                    </div>
					<!-- /.site-heading -->
                </div>
				<!-- /.col-lg-8.col-lg-offset-2.col-md-10.col-md-offset-1 -->
            </div>
			<!-- /.row -->
        </div>
		<!-- /.container -->
    </header>

	<?php } elseif( is_search() ) { ?>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-color: <?php echo get_theme_mod( 'cleanblog_header_background_color' ); ?>;')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1><?php esc_html_e( 'Search Results', 'cleanblog' ); ?></h1>
                        <hr class="small">
                        <span class="subheading"><?php printf( esc_html__( 'You searched for: "%s"', 'cleanblog' ), '<span>' . get_search_query() . '</span>' ); ?></span>
                    </div>
		    <!-- /.site-heading -->
                </div>
		<!-- /.col-lg-8.col-lg-offset-2.col-md-10.col-md-offset-1 -->
            </div>
	<!-- /.row -->
        </div>
	<!-- /.container -->
    </header>

	<?php } else { ?>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
	<?php if ( get_theme_mod('cleanblog_homeintro_image') !='' ) { ?>
	<?php $headerimg = get_theme_mod( 'cleanblog_homeintro_image' ); ?>
	<?php } else { ?>
	<?php $headerimg = get_template_directory_uri() . '/img/home-bg.jpg'; ?>
	<?php } ?>
    <header class="intro-header" style="background-color: <?php echo get_theme_mod( 'cleanblog_header_background_color' ); ?>; background-image: url('<?php echo $headerimg; ?>')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
			<?php if ( get_theme_mod('cleanblog_homeintro_title') !='' ) { ?>
			<h1 class="homeintro"><?php echo get_theme_mod( 'cleanblog_homeintro_title' ); ?></h1>
			<?php } else { ?>
                        <h1><?php esc_html_e( 'Clean Blog', 'cleanblog' ); ?></h1>
			<?php } ?>
                        <hr class="small">
			<?php if (get_theme_mod('cleanblog_homeintro_subtitle') !='') { ?>
                        <span class="subheading"><?php echo get_theme_mod( 'cleanblog_homeintro_subtitle' ); ?></span>
			<?php } else { ?>
                        <span class="subheading"><?php esc_html_e( 'A Clean Blog Theme by Devio Digital', 'cleanblog' ); ?></span>
			<?php } ?>
                    </div>
		    <!-- /.site-heading -->
                </div>
		<!-- /.col-lg-8.col-lg-offset-2.col-md-10.col-md-offset-1 -->
            </div>
	    <!-- /.row -->
        </div>
	<!-- /.container -->
    </header>

	<?php } ?>

<?php }
endif;

if ( ! function_exists( 'cleanblog_social' ) ) :
/**
 * Adds the social profile links into the theme's footer.php file
 */
function cleanblog_social() { ?>

	<ul class="list-inline text-center">
		<?php if ( get_theme_mod( 'cleanblog_social_twitter' ) !='' ) { ?>
		<li id="social-twitter">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_twitter' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_facebook' ) !='' ) { ?>
		<li id="social-facebook">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_facebook' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_google' ) !='' ) { ?>
		<li id="social-google">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_google' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-google fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_pinterest' ) !='' ) { ?>
		<li id="social-pinterest">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_pinterest' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-pinterest fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_linkedin' ) !='' ) { ?>
		<li id="social-linkedin">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_linkedin' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-linkedin fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_github' ) !='' ) { ?>
		<li id="social-github">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_github' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-github fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_instagram' ) !='' ) { ?>
		<li id="social-instagram">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_instagram' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-instagram fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_medium' ) !='' ) { ?>
		<li id="social-medium">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_medium' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-medium fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_vine' ) !='' ) { ?>
		<li id="social-vine">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_vine' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-vine fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_tumblr' ) !='' ) { ?>
		<li id="social-tumblr">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_tumblr' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-tumblr fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
		<?php if ( get_theme_mod( 'cleanblog_social_youtube' ) !='' ) { ?>
		<li id="social-youtube">
			<a href="<?php echo get_theme_mod( 'cleanblog_social_youtube' ); ?>" target="_blank">
				<span class="fa-stack fa-lg">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-youtube fa-stack-1x fa-inverse"></i>
				</span>
			</a>
		</li>
		<?php } ?>
	</ul>
<?php }
endif;
