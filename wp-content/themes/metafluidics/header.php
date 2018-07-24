<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package metafluidics
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri() . '/assets/img/favicon.ico'; ?>">

<?php wp_head(); ?>

<?php
  // enqueue device page scripts if device page
  if ( is_singular('metafluidics_device') ) {
    wp_enqueue_script('metafluidics-single-device', get_template_directory_uri() . '/assets/js/metafluidics-single-device.js', array('jquery'), '1.0.0', true );
  }

  // enqueue create device page scripts if create device page
  if ( is_page_template('page-create-device.php') ) {
    $muPluginURI = str_replace( '/plugins', '/mu-plugins', plugins_url() );
    wp_enqueue_script('metafluidics-create-device-media', $muPluginURI . '/metafluidics/js/device-media.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script('metafluidics-create-device-urls', $muPluginURI . '/metafluidics/js/device-urls.js', array('jquery'), '1.0.0', true );

    wp_enqueue_script('metafluidics-create-device', get_template_directory_uri() . '/assets/js/metafluidics-create-device.js', array('jquery'), '1.0.0', true );
  }

  // enqueue search scripts if home or search pages
  if ( is_home() || is_search() || is_tag() || is_tax() || is_archive() ) {
    wp_enqueue_script('metafluidics-search-sort', get_template_directory_uri() . '/assets/js/metafluidics-search-sort.js', array('jquery'), '1.0.0', true );
  }

  // ajax + likes
  echo '<script type="text/javascript"> var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"</script>';
  wp_enqueue_script('metafluidics-likes', get_template_directory_uri() . '/assets/js/metafluidics-likes.js', array('jquery'), '1.0.0', true );

?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'metafluidics' ); ?></a>

  <?php
    get_template_part( 'template-parts/header', 'masthead' );
  ?>

	<div id="content" class="site-content">

  <?php
    // if home page or search page, show header search section
    if ( is_home() || is_search() || is_tag() || is_tax() ) {
        get_template_part( 'template-parts/header', 'search' );
    }
  ?>
