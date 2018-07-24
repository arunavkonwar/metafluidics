<?php
/**
 * The template for displaying all single  .
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package metafluidics
 */

get_header(); ?>
<?php
	// show edit link and (if draft) draft label
	if ( get_post_status( $post->ID ) === 'draft' ) {
		echo '<p>This is a draft. Only you can see it until you edit and publish it.</p>';
	}
	edit_post_link('[edit this device]','<div class="edit cf">','</div>');
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?>>
			  <header class="device-header">
			    <?php the_title( '<h1 class="device-title">', '</h1>' ); ?>
			    <div class="device-creator-meta">
			      <?php metafluidics_device_posted_on(); ?>
			      <p class="last-updated">Last updated on <?php the_modified_date('F j, Y'); ?> at <?php the_modified_date('g:i a'); ?></p>
			    </div><!-- .device-meta -->
			  </header><!-- .device-header -->

				<?php
					// device carousel
					get_template_part( 'template-parts/device', 'carousel' );
				?>

			  </div><!-- .device-content -->
			</article><!-- #post-## -->

			<?php
				// device carousel
				get_template_part( 'template-parts/device', 'sidebar' );
			?>

			<div id="comment_wrapper">
			<?php
				// comments
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		</div>

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
