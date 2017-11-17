<?php
/**
 * Maeve functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package ClientTheme
 */

add_action( 'after_setup_theme', 'client_theme_setup' );
if ( ! function_exists( 'client_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function client_theme_setup() {

	// Add default posts and comments RSS feed links to head.
	// add_theme_support( 'automatic-feed-links' );

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
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'client-theme' ),
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
}
endif;

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'client_theme_scripts' );
function client_theme_scripts() {
	wp_enqueue_style( 'client-theme-style', get_stylesheet_uri() );

	wp_enqueue_script( 'client-theme-script', get_template_directory_uri() . '/js/script.js', array('jquery'), '20171116', true );

}

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
add_action( 'widgets_init', 'claps_widgets_init' );
function claps_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer spot 1', 'client-theme' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add widgets here.', 'client-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget__title">',
		'after_title'   => '</h4>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer spot 2', 'client-theme' ),
		'id'            => 'footer-2',
		'description'   => esc_html__( 'Add widgets here.', 'client-theme' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h4 class="widget__title">',
		'after_title'   => '</h4>',
	) );
}
