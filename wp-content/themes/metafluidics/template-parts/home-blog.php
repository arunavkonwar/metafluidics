<?php
/**
 * Template part for displaying a homepage "on the blog" section
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

	$blogQuery = $wp_query->query_vars['blog_query'];

	if ( $blogQuery->have_posts() ) :
		echo '<h3>On the Blog</h3>';
		$count = 0;

		while ( $blogQuery->have_posts() ) : $blogQuery->the_post();

			if ( $count === 0 ) { ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('featured-first'); ?>>
					<header class="entry-header">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<div class="entry-meta">
							<?php metafluidics_device_posted_on(); ?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->
					<div class="entry-excerpt">
						<?php the_excerpt(); ?>
					</div>
					<a class="button" href="<?php echo get_permalink(); ?>">Read More</a>
				</article><!-- #post-## -->
<?php
			}
			else { ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class('featured-list'); ?>>
					<header class="entry-header">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<div class="entry-meta">
							<?php metafluidics_device_posted_on(); ?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->
				</article><!-- #post-## -->

<?php
			}

			$count++;

		endwhile;
	endif;
