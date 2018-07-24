<?php
/**
 * Functions and actions for the "Downloads" functionality of device posts
 * POST should return string 'postID' values
 */

add_action( 'wp_ajax_metafluidics_downloads_update', function(){

  // check if user is logged in
  if ( ! is_user_logged_in() ) {
    return;
  }

  // get current likes of this post
  $downloads = get_post_meta( $_POST['postID'], 'metafluidics-device-downloads', true );
  $totalDownloads = get_post_meta( $_POST['parentID'], 'metafluidics-device-downloads-total', true );

  if ( is_wp_error( $downloads ) || is_wp_error( $totalDownloads ) ) {
    return;
  }
  else {
    // increment downloads, save, and return ajax call
    $downloads++;
    $totalDownloads++;
    $updated = update_post_meta( $_POST['postID'], 'metafluidics-device-downloads', $downloads );
    $updatedTotal = update_post_meta( $_POST['parentID'], 'metafluidics-device-downloads-total', $totalDownloads );
    if ( $updated && $updatedTotal ) {
      echo json_encode( array( 'downloads' => $downloads ) );
    }
  }

  wp_die();
});
