<?php
/**
 * The template for displaying all drafts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

get_header();

// get current logged in user if logged in
if ( is_user_logged_in() ) {
  global $current_user;
  get_currentuserinfo();

  $loggedIn = true;
}
else {
	$loggedIn = false;
}

?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php if ( $loggedIn ) { ?>
    <div id="drafts" class="devices content-area box-area site-main" role="main">

      <?php
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

          $draftsQuery = new WP_Query( array( 'post_type' => 'metafluidics_device', 'author' => $current_user->ID, 'post_status' => 'draft', 'paged' => $paged ) );

          if ( $draftsQuery->have_posts() ) :

            while ( $draftsQuery->have_posts() ) : $draftsQuery->the_post();

              get_template_part( 'template-parts/device', 'box' );

            endwhile;

						// post navigation
						echo '<nav class="navigation posts-navigation"><div class="nav-links cf"><span class="nav-previous">';
							previous_posts_link('&#9668; Previous Page', $draftsQuery->max_num_pages);
						echo '</span><span class="nav-next">';
							next_posts_link('Next Page &#9658;', $draftsQuery->max_num_pages);
						echo '</span></div></div>';

          else :

             get_template_part( 'template-parts/content', 'none' );

          endif;

          wp_reset_postdata();
      ?>

    </div>
  <?php } ?>

<?php get_footer(); ?>
