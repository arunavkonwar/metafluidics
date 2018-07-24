<?php
/**
 * Template part for displaying a blog posts in list pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
		<div class="entry-meta">
			<?php metafluidics_posted_on(); ?>
			<?php edit_post_link('[edit]','<span class="edit">','</span>'); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
</article><!-- #post-## -->
