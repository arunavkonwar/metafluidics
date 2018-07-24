<?php
/*
  Plugin Name: Metafluidics Taxonomy - Device Hardware
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

add_action( 'init', function() {

  // device supporting hardware
  $labelsHardware = array(
    'name'              => _x( 'Device Supporting Hardware', 'taxonomy general name' ),
    'singular_name'     => _x( 'Device Supporting Hardware', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Device Supporting Hardware' ),
    'all_items'         => __( 'All Device Supporting Hardware' ),
    'parent_item'       => __( 'Parent Device Supporting Hardware' ),
    'parent_item_colon' => __( 'Parent Device Supporting Hardware:' ),
    'edit_item'         => __( 'Edit Device Supporting Hardware' ),
    'update_item'       => __( 'Update Device Supporting Hardware' ),
    'add_new_item'      => __( 'Add New Device Supporting Hardware' ),
    'new_item_name'     => __( 'New Device Supporting Hardware Name' ),
    'menu_name'         => __( 'Device Supporting Hardware' ),
  );

  $argsHardware = array(
    'hierarchical'      => true,
    'labels'            => $labelsHardware,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'device-hardware' ),
    'capabilities'      => array( 'assign_terms' => 'read' )
  );

  $postTypesHardware = array('metafluidics_device');
  register_taxonomy( 'metafluidics_device_hardware', $postTypesHardware, $argsHardware );

}, 0);
