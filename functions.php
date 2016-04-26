<?php
/**
 * Clean Blog functions and definitions
 *
 * @package Clean Blog
 */

if ( ! function_exists( 'cleanblog_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cleanblog_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Clean Blog, use a find and replace
	 * to change 'cleanblog' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'cleanblog', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'cleanblog' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	//	add_theme_support( 'post-formats', array(
	//		'aside',
	//		'image',
	//		'video',
	//		'quote',
	//		'link',
	//	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'cleanblog_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // cleanblog_setup
add_action( 'after_setup_theme', 'cleanblog_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cleanblog_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cleanblog_content_width', 750 );
}
add_action( 'after_setup_theme', 'cleanblog_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function cleanblog_scripts() {
	wp_enqueue_style( 'cleanblog-style', get_stylesheet_uri() );
	wp_enqueue_style( 'cleanblog-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css' );
	wp_enqueue_style( 'cleanblog-theme', get_template_directory_uri() . '/css/clean-blog.min.css' );
	wp_enqueue_style( 'cleanblog-fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css' );
	wp_enqueue_style( 'cleanblog-lora', '//fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' );
	wp_enqueue_style( 'cleanblog-opensans', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' );

	wp_enqueue_script( 'cleanblog-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20150803', true );
	wp_enqueue_script( 'cleanblog-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20150803', true );
	wp_enqueue_script( 'cleanblog-jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), '20150803', true );
	wp_enqueue_script( 'cleanblog-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '20150803', true );
	wp_enqueue_script( 'cleanblog-theme', get_template_directory_uri() . '/js/clean-blog.min.js', array(), '20150803', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cleanblog_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Remove container DIV from navigation menu in header.
 */
function my_wp_nav_menu_args( $args = '' ) {
	$args['container'] = false;
	return $args;
}
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

/**
 * Customizing the excerpt
 */

// Customize the excerpt length
function custom_excerpt_length( $length ) {
	return 60;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

// Add a Read More link to the end of the excerpt
function custom_excerpt_more( $more ) {
	return ' ... <a class="read-more" href="' . get_permalink( get_the_ID() ) . '">' . __( 'Read More', 'cleanblog' ) . '</a>';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

// Add a class to the <p> wrap around the excerpt
function add_class_to_excerpt( $excerpt ) {
    return str_replace('<p', '<p class="excerpt"', $excerpt);
}
add_filter( "the_excerpt", "add_class_to_excerpt" );

/**
 * Require Github Updater plugin for theme update checks
 */
require get_template_directory() . '/inc/install-github-updater.php';