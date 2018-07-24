<?php
/**
 * The home page template file.
 * Template Name: Home
 * @package metafluidics
 */

get_header();

	// feature devices query
	$featureQuery = new WP_Query( array(
		'post_type' => 'metafluidics_device',
		'showposts' => 2,
		'tax_query' => array(
			array(
				'taxonomy' => 'metafluidics_feature_status',
				'field'	=> 'slug',
				'terms' => 'feature-on-homepage'
			)
		)
	) );

	// feature blogs query
	$blogQuery = new WP_Query( array(
		'post_type' => 'post',
		'showposts' => 3,
		'tax_query' => array(
			array(
				'taxonomy' => 'metafluidics_feature_status',
				'field'	=> 'slug',
				'terms' => 'feature-on-homepage'
			)
		)
	) );

	// device stream query
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$deviceStreamArgs = array(
		'post_type' => 'metafluidics_device',
		'paged' => $paged,
		'order' => 'DESC'
	);
	$deviceQuery = new WP_Query( $deviceStreamArgs );

	// only show feature section if there is a feature device & blog posts
	if ( $featureQuery->have_posts() && $blogQuery->have_posts() ):
		$wp_query->query_vars['feature_query'] = $featureQuery;
		$wp_query->query_vars['blog_query'] = $blogQuery;
?>

	<div id="featured" class="content-area">

		<section class="device box-area">

			<?php get_template_part( 'template-parts/home', 'featured' ); ?>

		</section>

		<section class="blog">

			<?php get_template_part( 'template-parts/home', 'blog' ); ?>

		</section>

	</div>

<?php endif; ?>

	<div id="posts" class="devices content-area box-area site-main" role="main">

		<h1>Browse Parts</h1>

		<?php
			if ( $deviceQuery->have_posts() ) :

				while ( $deviceQuery->have_posts() ) : $deviceQuery->the_post();

					get_template_part( 'template-parts/device', 'box' );

				endwhile;

				if ( $deviceQuery->max_num_pages > 1 ) :
					echo '<nav class="navigation posts-navigation"><div class="nav-links cf">';
					echo '<a class="button" href="/devices/">View All Parts</a>';
					echo '</div></nav>';
				endif;

			else :

				 get_template_part( 'template-parts/content', 'none' );

  		endif;

		?>

	</div>

<?php get_footer(); ?>
