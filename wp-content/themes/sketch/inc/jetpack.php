<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Sketch
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */

function sketch_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
        'posts_per_page' => 9,
		'footer'         => 'page',
		'render'         => 'sketch_infinite_scroll_render',
	) );

	add_theme_support( 'featured-content', array(
	    'filter'     => 'sketch_get_featured_posts',
	    'max_posts'  => 10,
	    'post_types' => array( 'post', 'page', 'jetpack-portfolio' ),
	) );

    /**
     * Add theme support for Responsive Videos.
     */
    add_theme_support( 'jetpack-responsive-videos' );

    /**
     * Add theme support for Jetpack portfolios
     */
    add_theme_support( 'jetpack-portfolio' );

    /**
     * Add theme support for site logos
     */
     add_theme_support( 'site-logo', array( 'sketch-site-logo' ) );
}
add_action( 'after_setup_theme', 'sketch_jetpack_setup' );


/**
 * Define the code that is used to render the posts added by Infinite Scroll.
 *
 * Includes the whole loop. Used to include the correct template part for the Portfolio CPT.
 */
function sketch_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		
		if ( is_post_type_archive( 'jetpack-portfolio' ) || is_tax( 'jetpack-portfolio-type' ) || is_tax( 'jetpack-portfolio-tag' ) ) {
			get_template_part( 'content', 'portfolio' );
		} else {
			get_template_part( 'content', get_post_format() );
		}
	}
}

function sketch_get_featured_posts() {
    return apply_filters( 'sketch_get_featured_posts', array() );
}

function sketch_has_featured_posts( $minimum = 1 ) {
    if ( is_paged() )
        return false;

    $minimum = absint( $minimum );
    $featured_posts = apply_filters( 'sketch_get_featured_posts', array() );

    if ( ! is_array( $featured_posts ) )
        return false;

    if ( $minimum > count( $featured_posts ) )
        return false;

    return true;
}