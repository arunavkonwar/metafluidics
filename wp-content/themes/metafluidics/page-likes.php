<?php
/**
 * The template for displaying all likes.
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
    <div id="likes" class="devices content-area box-area site-main" role="main">

      <?php
        $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        $likesArray = array_filter( explode( ',', get_user_meta( $current_user->ID, 'user-device-likes', true) ) );

        if ( count( $likesArray ) ) :
          $likesQuery = new WP_Query( array( 'post_type' => 'metafluidics_device', 'post__in' => $likesArray, 'paged' => $paged ) );

          if ( $likesQuery->have_posts() ) :

            while ( $likesQuery->have_posts() ) : $likesQuery->the_post();

              get_template_part( 'template-parts/device', 'box' );

            endwhile;

						// post navigation
						echo '<nav class="navigation posts-navigation"><div class="nav-links cf"><span class="nav-previous">';
							previous_posts_link('&#9668; Previous Page', $likesQuery->max_num_pages);
						echo '</span><span class="nav-next">';
							next_posts_link('Next Page &#9658;', $likesQuery->max_num_pages);
						echo '</span></div></div>';

          else :

             get_template_part( 'template-parts/content', 'none' );

          endif;

          wp_reset_postdata();

        endif;

      ?>

    </div>
  <?php } ?>

<?php get_footer(); ?>
