<?php
/*
  Plugin Name: Metafluidics Media Functions
  Description: Admin functionality for the Media Library
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

add_filter( 'posts_where', function( $where ){
	global $current_user;

	if( is_user_logged_in() && !is_admin() ){
		if( isset( $_POST['action'] ) ){
	  	if( $_POST['action'] === 'query-attachments' ){
				$where .= ' AND post_author='.$current_user->data->ID;
			}
		}
	}

	return $where;
});
