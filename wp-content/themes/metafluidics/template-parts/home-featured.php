<?php
/**
 * Template part for displaying a homepage featured Device box
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

 	$featureQuery = $wp_query->query_vars['feature_query'];

	if ( $featureQuery->have_posts() ) :
		echo '<h3>Featured Parts</h3>';

		while ( $featureQuery->have_posts() ) : $featureQuery->the_post();

			get_template_part( 'template-parts/device', 'box' );

		endwhile;
	endif;
