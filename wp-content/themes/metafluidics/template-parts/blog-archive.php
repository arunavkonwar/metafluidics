<?php
/**
 * Template part for displaying a blog posts in archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<div class="entry-meta">
			<?php metafluidics_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<section class="entry-content">
		<?php the_excerpt(); ?>
		<a class="button" href="<?php the_permalink(); ?>">Read More</a>
	</section>
	<footer class="entry-footer">
		<!-- tags here -->
		<?php edit_post_link('edit','<div class="edit">','</div>'); ?>
	</footer>
</article><!-- #post-## -->
