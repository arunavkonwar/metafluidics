<?php
/**
 * Template Name: Log-in Page
 * The template for displaying the login page.
 *
 * @package metafluidics
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php
				      if ( !is_user_logged_in() ) {
				        $args = array(
				          'redirect' => '/user-dashboard',
				          'remember' => true
				        );
				        wp_login_form($args);
				      }
				      else {
				        wp_loginout( '/log-in' );
				        echo ' | <a href="/user-dashboard">Dashboard</a>';
				      }
				    ?>
					</div><!-- .entry-content -->

				</article><!-- #post-## -->

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
