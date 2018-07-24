<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package metafluidics
 */

get_header(); ?>

  <header class="page-header">
  	<h1 class="page-title"><?php printf( esc_html__( 'Search Results for "%s"', 'metafluidics' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
  </header><!-- .page-header -->

	<section id="primary" class="content-area">

		<main id="main" class="site-main box-area" role="main">

      <?php
    		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    		$query = new WP_Query( array( 'post_type' => 'metafluidics_device', 'paged' => $paged, 's' => get_search_query() ) );

  			if ( $query->have_posts() ) :

  				while ( $query->have_posts() ) : $query->the_post();

  					get_template_part( 'template-parts/device', 'box' );

  				endwhile;

   				the_posts_navigation();

  			else :

  				 get_template_part( 'template-parts/content', 'none' );

        endif;

        wp_reset_postdata();
   		?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
