<?php
/**
 * metafluidics functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package metafluidics
 */

if ( ! function_exists( 'metafluidics_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function metafluidics_setup() {
  /*
   * Make theme available for translation.
   * Translations can be filed in the /languages/ directory.
   * If you're building a theme based on metafluidics, use a find and replace
   * to change 'metafluidics' to the name of your theme in all the template files.
   */
  load_theme_textdomain( 'metafluidics', get_template_directory() . '/languages' );

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
   * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
   */
  add_theme_support( 'post-thumbnails' );

  add_image_size( 'archive-thumbnail', 400, 300, true );
  add_image_size( 'carousel', 800, 600, true );
  add_image_size( 'carousel-thumb', 300, 200, true );

  // This theme uses wp_nav_menu() in one location.
  register_nav_menus( array(
    'footer-menu' => esc_html__( 'Footer Menu', 'metafluidics' ),
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
endif; // metafluidics_setup
add_action( 'after_setup_theme', 'metafluidics_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function metafluidics_content_width() {
  $GLOBALS['content_width'] = apply_filters( 'metafluidics_content_width', 640 );
}
add_action( 'after_setup_theme', 'metafluidics_content_width', 0 );


/**
 * Enqueue scripts and styles.
 */
function metafluidics_scripts() {
  wp_enqueue_style( 'metafluidics-style', get_stylesheet_uri() );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'metafluidics_scripts' );

add_action( 'pre_get_posts', function($query) {
  // if not admin or home, set to 9
  if ( !is_admin() ) {
    if ( $query->get('is_cron') ) {
      $query->set('posts_per_page', -1);
    }
    else if ( $query->get('post_type') === 'metafluidics_device' ) {
        $query->set('posts_per_page', 9);
      }
      else {
        $query->set('posts_per_page', 5);
      }
  }
  return $query;
});

/**
 * Custom function files
 */
require get_template_directory() . '/functions/template-tags.php';
require get_template_directory() . '/functions/views.php';

/**
 * Hide admin bar
 */
show_admin_bar(false);

/*
  Allow Illustrator And Photoshop files
*/

function my_myme_types($mime_types){
  $mime_types['psd'] = 'image/vnd.adobe.photoshop'; //Adding photoshop files
  $mime_types['ai'] = 'application/postscript'; //Adding AI files return $mime_types; }
  return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);
