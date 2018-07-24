<?php
/*
  Plugin Name: Metafluidics Taxonomy - Device Fabrication Technology
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

add_action( 'init', function() {

  // device fabrication Technology
  $labelsTechnology = array(
    'name'              => _x( 'Device Fabrication Technology', 'taxonomy general name' ),
    'singular_name'     => _x( 'Device Fabrication Technology', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Device Fabrication Technology' ),
    'all_items'         => __( 'All Device Fabrication Technology' ),
    'parent_item'       => __( 'Parent Device Fabrication Technology' ),
    'parent_item_colon' => __( 'Parent Device Fabrication Technology:' ),
    'edit_item'         => __( 'Edit Device Fabrication Technology' ),
    'update_item'       => __( 'Update Device Fabrication Technology' ),
    'add_new_item'      => __( 'Add New Device Fabrication Technology' ),
    'new_item_name'     => __( 'New Device Fabrication Technology Name' ),
    'menu_name'         => __( 'Device Fabrication Technology' ),
  );

  $argsTechnology = array(
    'hierarchical'      => true,
    'labels'            => $labelsTechnology,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'device-technology' ),
    'capabilities'      => array( 'assign_terms' => 'read' )
  );

  $postTypesTechnology = array('metafluidics_device');
  register_taxonomy( 'metafluidics_device_technology', $postTypesTechnology, $argsTechnology );

}, 0);
