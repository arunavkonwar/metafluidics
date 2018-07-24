<?php
/*
  Plugin Name: Metafluidics Taxonomy - Device Keywords
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

add_action( 'init', function() {

  // device keywords
  $labelsKeywords = array(
    'name'              => _x( 'Device Keywords', 'taxonomy general name' ),
    'singular_name'     => _x( 'Device Keyword', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Device Keywords' ),
    'all_items'         => __( 'All Device Keywords' ),
    'parent_item'       => __( 'Parent Device Keywords' ),
    'parent_item_colon' => __( 'Parent Device Keywords:' ),
    'edit_item'         => __( 'Edit Device Keywords' ),
    'update_item'       => __( 'Update Device Keywords' ),
    'add_new_item'      => __( 'Add New Device Keywords' ),
    'new_item_name'     => __( 'New Device Keywords Name' ),
    'menu_name'         => __( 'Device Keywords' ),
  );

  $argsKeywords = array(
    'hierarchical'      => true,
    'labels'            => $labelsKeywords,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'device-keywords' ),
    'capabilities'      => array( 'assign_terms' => 'read' )
  );

  $postTypesKeywords = array('metafluidics_device');

  register_taxonomy( 'metafluidics_device_keywords', $postTypesKeywords, $argsKeywords );

}, 0);
