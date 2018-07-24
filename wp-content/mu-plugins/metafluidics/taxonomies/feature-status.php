<?php
/*
  Plugin Name: Metafluidics Taxonomy - Feature Status
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

add_action( 'init', function() {

  // featured
  $labelsFeatured = array(
    'name'              => _x( 'Feature Status', 'taxonomy general name' ),
    'singular_name'     => _x( 'Feature Statuses', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Feature Statuses' ),
    'all_items'         => __( 'All Feature Statuses' ),
    'parent_item'       => __( 'Parent Feature Status' ),
    'parent_item_colon' => __( 'Parent Feature Status:' ),
    'edit_item'         => __( 'Edit Feature Statuss' ),
    'update_item'       => __( 'Update Feature Status' ),
    'add_new_item'      => __( 'Add New Feature Status' ),
    'new_item_name'     => __( 'New Feature Status' ),
    'menu_name'         => __( 'Feature Statuses' ),
  );

  $argsFeatured = array(
    'hierarchical'      => true,
    'labels'            => $labelsFeatured,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'feature-status' ),
    'capabilities'      => array( 'assign_terms' => 'edit_others_metafluidics_devices' )
  );

  $postTypesFeatured = array('post','metafluidics_device');
  register_taxonomy( 'metafluidics_feature_status', $postTypesFeatured, $argsFeatured );

}, 0);
