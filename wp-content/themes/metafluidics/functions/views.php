<?php
/**
 * Functions and actions for the "Views" functionality of device posts
 */

// schedule the cron job to get views hourly
if ( !wp_next_scheduled( 'metafluidics_get_views_hook' ) ) {
  wp_schedule_event( time(), 'hourly', 'metafluidics_get_views_hook' );
}


// get the views of all device posts and update their meta
add_action( 'metafluidics_get_views_hook', function() {
  $cronArgs = array(
    'is_cron' => true,
    'post_type' => 'metafluidics_device',
  );

  $cronQuery = new WP_Query( $cronArgs );

  if( $cronQuery->have_posts() ) {
    while( $cronQuery->have_posts() ) : $cronQuery->the_post();

      // get views of this post
      $views = getPageViews( '/devices/' . $post->post_name . '/');
      if ( !$views ) {
        $views = 0;
      }

      // update views meta for this post
      update_post_meta( $post->ID, 'metafluidics-device-views', $views );
    endwhile;
  }
  wp_reset_query();
});
