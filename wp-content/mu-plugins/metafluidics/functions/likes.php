<?php
/**
 * Functions and actions for the "Like" functionality of device posts
 * POST should return boolean 'liked' and string 'postID' values
 */

add_action( 'wp_ajax_metafluidics_likes_update', function(){

  // check if user liked this or not and upate accordingly
  global $current_user;
  get_currentuserinfo();
  $userLikes = explode( ',', get_user_meta( $current_user->ID, 'user-device-likes', true) );
  $userLikedAlready = array_search( $_POST['postID'], $userLikes );

  // get current likes of this post
  $likes = get_post_meta( $_POST['postID'], 'metafluidics-device-likes', true );

  if ( is_wp_error( $likes ) ) {
    return false;
  }
  else {

    // if like status true, increment and update likesj
    $liked = $_POST['liked'];

    if ( $liked === 'true' && !$userLikedAlready ) {
      $likes++;
      update_post_meta( $_POST['postID'], 'metafluidics-device-likes', $likes );

      // update user's likes meta
      array_push( $userLikes, $_POST['postID'] );
      update_user_meta( $current_user->ID, 'user-device-likes', implode(',', $userLikes) );
    }
    else {
      if ( $likes > 0 && $userLikedAlready ) {
        $likes--;
        update_post_meta( $_POST['postID'], 'metafluidics-device-likes', $likes );

        // update user's likes meta
        unset( $userLikes[$userLikedAlready]);
        update_user_meta( $current_user->ID, 'user-device-likes', implode(',', $userLikes) );
      }
    }

    echo json_encode( array( 'liked' => $liked, 'likes' => $likes ) );
  }

  wp_die();
});
