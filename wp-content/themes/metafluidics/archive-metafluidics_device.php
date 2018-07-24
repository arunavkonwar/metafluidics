<?php
/**
 * The template for displaying project archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

// device stream query
$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$deviceStreamArgs = array(
 'post_type' => 'metafluidics_device',
 'paged' => $paged
);

// set orderby and meta key depending on get params
$likeSelected = '';
$latestSelected = '';
$viewsSelected = '';
$downloadsSelected = '';

if ( isset( $_GET['sortPostsBy'] ) && $_GET['sortPostsBy'] === 'most-liked' ) {
  $likeSelected = 'selected';
  $deviceStreamArgs['orderby'] = 'meta_value_num';
  $deviceStreamArgs['meta_key'] = 'metafluidics-device-likes';
}
else if ( isset( $_GET['sortPostsBy'] ) && $_GET['sortPostsBy'] === 'most-views' ) {
    $viewsSelected = 'selected';
    $deviceStreamArgs['orderby'] = 'meta_value_num';
    $deviceStreamArgs['meta_key'] = 'metafluidics-device-views';
  }
  else if ( isset( $_GET['sortPostsBy'] ) && $_GET['sortPostsBy'] === 'most-downloads' ) {
      $downloadsSelected = 'selected';
      $deviceStreamArgs['orderby'] = 'meta_value_num';
      $deviceStreamArgs['meta_key'] = 'metafluidics-device-downloads-total';
    }
    else if ( isset( $_GET['sortPostsBy'] ) && $_GET['sortPostsBy'] === 'latest' ) {
        $latestSelected = 'selected';
        $deviceStreamArgs['orderby'] = 'date';
      }

$deviceQuery = new WP_Query( $deviceStreamArgs );

get_header(); ?>

  <header class="page-header">
    <h1>Browse Parts</h1>
  </header><!-- .page-header -->

	<form id="sorting" class="tool-area" method="GET" action="">
		<select name="sortPostsBy" id="sort-devices">
			<option name="sortPostsBy" value="latest" <?php echo $latestSelected; ?>>Latest</option>
      <option name="sortPostsBy" value="most-liked" <?php echo $likeSelected; ?>>Most Liked</option>
      <option name="sortPostsBy" value="most-views" <?php echo $viewsSelected; ?>>Most Views</option>
      <option name="sortPostsBy" value="most-downloads" <?php echo $downloadsSelected; ?>>Most Downloads</option>
		</select>
	</form>


  <div id="posts" class="devices content-area box-area site-main" role="main">

    <?php
      if ( $deviceQuery->have_posts() ) :

        while ( $deviceQuery->have_posts() ) : $deviceQuery->the_post();

          get_template_part( 'template-parts/device', 'box' );

        endwhile;

        // post navigation
        echo '<nav class="navigation posts-navigation"><div class="nav-links cf"><span class="nav-previous">';
          previous_posts_link('&#9668; Previous Page', $deviceQuery->max_num_pages);
        echo '</span><span class="nav-next">';
          next_posts_link('Next Page &#9658;', $deviceQuery->max_num_pages);
        echo '</span></div></div>';

      else :

         get_template_part( 'template-parts/content', 'none' );

      endif;

    ?>

  </div><!-- #primary -->

<?php get_footer(); ?>
