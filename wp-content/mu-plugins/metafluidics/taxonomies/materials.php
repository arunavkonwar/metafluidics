<?php
/*
  Plugin Name: Metafluidics Taxonomy - Device Materials
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

add_action( 'init', function() {

  // device materials
  $labelsMaterial = array(
    'name'              => _x( 'Device Materials', 'taxonomy general name' ),
    'singular_name'     => _x( 'Device Material', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Device Materials' ),
    'all_items'         => __( 'All Device Materials' ),
    'parent_item'       => __( 'Parent Device Materials' ),
    'parent_item_colon' => __( 'Parent Device Materials:' ),
    'edit_item'         => __( 'Edit Device Materials' ),
    'update_item'       => __( 'Update Device Materials' ),
    'add_new_item'      => __( 'Add New Device Materials' ),
    'new_item_name'     => __( 'New Device Materials Name' ),
    'menu_name'         => __( 'Device Materials' ),
  );

  $argsMaterial = array(
    'hierarchical'      => true,
    'labels'            => $labelsMaterial,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'device-material' ),
    'capabilities'      => array( 'assign_terms' => 'read' )
  );

  $postTypesMaterial = array('metafluidics_device');
  register_taxonomy( 'metafluidics_device_material', $postTypesMaterial, $argsMaterial );

}, 0);
