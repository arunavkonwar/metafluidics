<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

get_header(); ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div id="blog" class="content-area" role="main">

		<div class="site-main" role="main">
			<?php
				$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				$query = new WP_Query( array( 'post_type' => 'post', 'paged' => $paged ) );

				if ( $query->have_posts() ) :

					while ( $query->have_posts() ) : $query->the_post();

						get_template_part( 'template-parts/blog', 'archive' );

					endwhile;

					// post navigation
					echo '<div class="nav-links cf"><span class="nav-previous">';
						next_posts_link('&#9668; Older Posts', $query->max_num_pages);
					echo '</span><span class="nav-next">';
						previous_posts_link('Newer Posts &#9658;');
					echo '</span></div>';

				else :

					 get_template_part( 'template-parts/content', 'none' );

				endif;

				wp_reset_postdata();
			?>

		</div><!-- #blog -->
	</div><!-- #primary -->
<?php get_footer(); ?>
