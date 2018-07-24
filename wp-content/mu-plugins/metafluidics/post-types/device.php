<?php
/*
  metafluidics_device custom post type

  * creates the metafluidics_device post type
  * adds custom meta fields to this post type
  * enqueues admin scripts to allow media upload
*/
// edit arunav step 3
//console log function
function debug_to_console( $data ) {
  $output = $data;
  if ( is_array( $output ) )
  $output = implode( ',', $output);

  echo "<script>console.log( 'Debug Objects: " . $output . "' );</script>";
}


// set collection of labels for DRYness
  // edit arunav step 1
$metaBoxCollection = ['description', 'license','thumbnail', 'images', 'design_files', 'bill_of_materials', 'build_instructions', 'software', 'likes','views','downloads_total','publications', 'tutorials', 'remixed', 'parts'];
$metaBoxFileCollection = ['thumbnail', 'images', 'design_files', 'bill_of_materials', 'build_instructions', 'software'];

// allow image types in admin custom posts
add_action('post_edit_form_tag', 'update_post_types_edit_form');
function update_post_types_edit_form() {
  echo 'enctype="multipart/form-data"';
}

// enqueue scripts for use on post/edit of devices
add_action('admin_enqueue_scripts', 'metafluidics_device_admin_enqueue_scripts');
function metafluidics_device_admin_enqueue_scripts($hook) {
  if ( $hook !== 'post.php' && $hook !== 'edit.php') {
    return;
  }
  wp_enqueue_media();
  wp_enqueue_script('metafluidics_device_media', '/wp-content/mu-plugins/metafluidics/js/device-media.js', 'jQuery', '1.0.0', true );
  wp_enqueue_script('metafluidics_device_urls', '/wp-content/mu-plugins/metafluidics/js/device-urls.js', 'jQuery', '1.0.0', true );
  wp_enqueue_style('metafluidics_device_css', '/wp-content/mu-plugins/metafluidics/css/device.css');
}

// custom post type for metafluidics device
add_action( 'init', 'create_post_type_metafluidics_device' );
function create_post_type_metafluidics_device() {
  register_post_type( 'metafluidics_device',
    array(
      'labels' => array(
        'name'               => _x( 'Devices', 'post type general name', 'metafluidics' ),
        'singular_name'      => _x( 'Device', 'post type singular name', 'metafluidics' ),
        'menu_name'          => _x( 'Devices', 'admin menu', 'metafluidics' ),
        'name_admin_bar'     => _x( 'Device', 'add new on admin bar', 'metafluidics' ),
        'add_new'            => _x( 'Add New', 'Device', 'metafluidics' ),
        'add_new_item'       => __( 'Add New Device', 'metafluidics' ),
        'new_item'           => __( 'New Device', 'metafluidics' ),
        'edit_item'          => __( 'Edit Device', 'metafluidics' ),
        'view_item'          => __( 'View Device', 'metafluidics' ),
        'all_items'          => __( 'All Devices', 'metafluidics' ),
        'search_items'       => __( 'Search Devices', 'metafluidics' ),
        'parent_item_colon'  => __( 'Parent Devices:', 'metafluidics' ),
        'not_found'          => __( 'No Devices found.', 'metafluidics' ),
        'not_found_in_trash' => __( 'No Devices found in Trash.', 'metafluidics' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('with_front' => false, 'slug' => 'devices'),
      'capability_type' => array('metafluidics_device', 'metafluidics_devices'),
      'map_meta_cap' => true,
      'supports' => array(
        'title',
        'revisions',
        'comments'
      ),
      'taxonomies' => array( 'metafluidics_device_technology', 'metafluidics_device_skill' ),
    )
  );

  // add meta boxes for device info
  add_action('add_meta_boxes', 'device_add_meta_boxes');
  function device_add_meta_boxes() {
    global $metaBoxCollection;

    foreach ( $metaBoxCollection as $metaBoxLabel ) {
      $metaBoxLabelTitle = str_replace('_', ' ', $metaBoxLabel);
      add_meta_box('metafluidics_device_' . $metaBoxLabel, $metaBoxLabelTitle, 'metafluidics_device_' . $metaBoxLabel . '_callback', 'metafluidics_device', 'normal', 'default', null);
    }
  }


  // ########################
  // Generate Meta Box Forms
  // ########################

  // generate "description" meta box
  function metafluidics_device_description_callback($post) {
    wp_nonce_field( 'metafluidics_device_description_save_data', 'metafluidics_device_description_nonce' );
    $value = get_post_meta( $post->ID, 'metafluidics-device-description', true );

    wp_editor($value, 'metafluidics-device-description', array(
      'wpautop'       => false,
      'media_buttons' => false,
      'textarea_name' => 'metafluidics-device-description',
      'textarea_rows' => 10,
      'teeny'         => true
      )
    );
  }

  // generate "license" meta box
  // edit arunav step 2
  function metafluidics_device_license_callback($post) {

    wp_nonce_field( 'metafluidics_license_save_data', 'metafluidics_license_nonce' );
    $value = get_post_meta( $post->ID, 'metafluidics-license', true );
    //debug_to_console($value);
       ?>



    <label for="meta-box-dropdown">Select License type</label>
    <select id="metafluidics-license" name="metafluidics-license">
    <?php 
    $option_values = array('Attribution: CC BY', 'Attribution-ShareAlike: CC BY-SA', 'Attribution-NoDerivs: CC BY-ND', 'Attribution-NonCommerical: CC BY-NC', 'Attribution-NonCommercial-ShareAlike: CC BY-NC-SA', 'Attribution-NonCommercial-NoDerivs: CC BY-NC-ND');

    foreach($option_values as $key => $value) 
    {
        if($value == get_post_meta($post->ID, "metafluidics-license", true))
        {
            ?>
                <option selected><?php echo $value; ?></option>
            <?php    
        }
        else
        {
            ?>
                <option><?php echo $value; ?></option>
            <?php
        }
    }              
  }


   // generate "thumbnail" meta box
  function metafluidics_device_thumbnail_callback($post) {
    wp_nonce_field( 'metafluidics_device_thumbnail_save_data', 'metafluidics_device_thumbnail_nonce' );

    // show media upload link
    $uploadLink = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
    echo '<p><button href="' . $uploadLink . '" class="upload-items button">Click Here to Upload Device Thumbnail</button></p>';

    // show thumbnail
    $thumbnail = explode(',', get_post_meta($post->ID, 'metafluidics-device-thumbnail', true));

    if ( !is_wp_error($thumbnail) ) {
      $thumbnailMarkup = '';
      foreach ( $thumbnail as $key=>$attachmentID ) {
        $attachmentURL = wp_get_attachment_url($attachmentID);

        if ( $attachmentURL ) {
        $thumbnailMarkup .= '<p class="uploaded-item" id="#attachment-' . $attachmentID . '" style="border:2px solid #ccc;display:inline-block;margin:5px;position:relative;"><img style="max-width:150px;max-height:150px;" src="' . $attachmentURL . '" /><a class="delete-item" href="#" style="position:absolute;top:-2px;right:-2px;background-color:#fff;">[X]</a></p>';
        }
      }

      // list all uploaded thumbnail
      echo '<div class="uploaded-items">';

      if ( $thumbnailMarkup ) {
        echo $thumbnailMarkup;
      }
      else {
        // all thumbnail have been deleted, so update meta to show there are none
        update_post_meta($post->ID, 'metafluidics-device-thumbnail', '');
      }

      echo '</div>';
    }

    ?>

    <input class="upload-input" name="metafluidics-device-thumbnail" type="hidden" value="<?php echo esc_attr( implode($thumbnail, ',') ); ?>" />

    <?php
  }


  // generate "images" meta box
  function metafluidics_device_images_callback($post) {
    wp_nonce_field( 'metafluidics_device_images_save_data', 'metafluidics_device_images_nonce' );

    // show media upload link
    $uploadLink = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
    echo '<p><button href="' . $uploadLink . '" class="upload-items button">Click Here to Upload Device Images</button></p>';

    // show all images
    $images = explode(',', get_post_meta($post->ID, 'metafluidics-device-images', true));

    if ( !is_wp_error($images) ) {
      $thumbnailMarkup = '';
      foreach ( $images as $key=>$attachmentID ) {
        $attachmentURL = wp_get_attachment_url($attachmentID);

        if ( $attachmentURL ) {
        $thumbnailMarkup .= '<p class="uploaded-item" id="#attachment-' . $attachmentID . '" style="border:2px solid #ccc;display:inline-block;margin:5px;position:relative;"><img style="max-width:150px;max-height:150px;" src="' . $attachmentURL . '" /><a class="delete-item" href="#" style="position:absolute;top:-2px;right:-2px;background-color:#fff;">[X]</a></p>';
        }
      }

      // list all uploaded images
      echo '<div class="uploaded-items">';

      if ( $thumbnailMarkup ) {
        echo $thumbnailMarkup;
      }
      else {
        // all images have been deleted, so update meta to show there are none
        update_post_meta($post->ID, 'metafluidics-device-images', '');
      }

      echo '</div>';
    }

    ?>

    <input class="upload-input" name="metafluidics-device-images" type="hidden" value="<?php echo esc_attr( implode($images, ',') ); ?>" />

    <?php
  }


  // generate "design files" meta box
  function metafluidics_device_design_files_callback($post) {
    wp_nonce_field( 'metafluidics_device_design_files_save_data', 'metafluidics_device_design_files_nonce' );

    // show media upload link
    $uploadLink = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
    echo '<p><button href="' . $uploadLink . '" class="upload-items button">Click Here to Upload Device Design Files</button></p>';

    // show all images
    $images = explode(',', get_post_meta($post->ID, 'metafluidics-device-design-files', true));

    if ( !is_wp_error($images) ) {
      $thumbnailMarkup = '';
      foreach ( $images as $key=>$attachmentID ) {
        $attachmentURL = wp_get_attachment_url($attachmentID);

        if ( $attachmentURL ) {
        $thumbnailMarkup .= '<li class="uploaded-item" id="#attachment-' . $attachmentID . '"><a href="' . $attachmentURL . '" />' . get_the_title($attachmentID) . '</a> <a class="delete-item" href="#">[X]</a></li>';
        }
      }

      // list all uploaded items
      echo '<ul class="uploaded-items">';

      if ( $thumbnailMarkup ) {
        echo $thumbnailMarkup;
      }
      else {
        // all design files have been deleted, so update meta to show there are none
        update_post_meta($post->ID, 'metafluidics-device-design-files', '');
      }

      echo '</ul>';
    }

    ?>

    <input class="upload-input" name="metafluidics-device-design-files" type="hidden" value="<?php echo esc_attr( implode($images, ',') ); ?>" />

    <?php
  }


  // generate "bill of materials" meta box
  function metafluidics_device_bill_of_materials_callback($post) {
    wp_nonce_field( 'metafluidics_device_bill_of_materials_save_data', 'metafluidics_device_bill_of_materials_nonce' );

    // show media upload link
    $uploadLink = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
    echo '<p><button href="' . $uploadLink . '" class="upload-items button">Click Here to Upload Device Bill of Materials</button></p>';

    // show materials
    $materials = explode(',', get_post_meta($post->ID, 'metafluidics-device-bill-of-materials', true));

    if ( !is_wp_error($materials) ) {
      $materialsMarkup = '';
      foreach ( $materials as $key=>$attachmentID ) {
        $attachmentURL = wp_get_attachment_url($attachmentID);

        if ( $attachmentURL ) {
        $materialsMarkup .= '<li class="uploaded-item" id="#attachment-' . $attachmentID . '"><a href="' . $attachmentURL . '" />' . get_the_title($attachmentID) . '</a> <a class="delete-item" href="#">[X]</a></li>';
        }
      }

      // list all uploaded materials
      echo '<ul class="uploaded-items">';

      if ( $materialsMarkup ) {
        echo $materialsMarkup;
      }
      else {
        // all materials have been deleted, so update meta to show there are none
        update_post_meta($post->ID, 'metafluidics-device-bill-of-materials', '');
      }

      echo '</ul>';
    }

    ?>

    <input class="upload-input" name="metafluidics-device-bill-of-materials" type="hidden" value="<?php echo esc_attr( implode($materials, ',') ); ?>" />

    <?php
  }


  // generate "build instructions" meta box
  function metafluidics_device_build_instructions_callback($post) {
    wp_nonce_field( 'metafluidics_device_build_instructions_save_data', 'metafluidics_device_build_instructions_nonce' );

    // show media upload link
    $uploadLink = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
    echo '<p><button href="' . $uploadLink . '" class="upload-items button">Click Here to Upload Device Build Instructions</button></p>';

    // show instructions
    $instructions = explode(',', get_post_meta($post->ID, 'metafluidics-device-build-instructions', true));

    if ( !is_wp_error($instructions) ) {
      $instructionsMarkup = '';
      foreach ( $instructions as $key=>$attachmentID ) {
        $attachmentURL = wp_get_attachment_url($attachmentID);

        if ( $attachmentURL ) {
        $instructionsMarkup .= '<li class="uploaded-item" id="#attachment-' . $attachmentID . '"><a href="' . $attachmentURL . '" />' . get_the_title($attachmentID) . '</a> <a class="delete-item" href="#">[X]</a></li>';
        }
      }

      // list all uploaded instructions
      echo '<ul class="uploaded-items">';

      if ( $instructionsMarkup ) {
        echo $instructionsMarkup;
      }
      else {
        // all instructions have been deleted, so update meta to show there are none
        update_post_meta($post->ID, 'metafluidics-device-build-instructions', '');
      }

      echo '</ul>';
    }

    ?>

    <input class="upload-input" name="metafluidics-device-build-instructions" type="hidden" value="<?php echo esc_attr( implode($instructions, ',') ); ?>" />

    <?php
  }


  // generate "software" meta box
  function metafluidics_device_software_callback($post) {
    wp_nonce_field( 'metafluidics_device_software_save_data', 'metafluidics_device_software_nonce' );

    // show media upload link
    $uploadLink = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
    echo '<p><button href="' . $uploadLink . '" class="upload-items button">Click Here to Upload Device Software</button></p>';

    // show all images
    $images = explode(',', get_post_meta($post->ID, 'metafluidics-device-software', true));

    if ( !is_wp_error($images) ) {
      $softwareMarkup = '';
      foreach ( $images as $key=>$attachmentID ) {
        $attachmentURL = wp_get_attachment_url($attachmentID);

        if ( $attachmentURL ) {
        $softwareMarkup .= '<li class="uploaded-item" id="#attachment-' . $attachmentID . '"><a href="' . $attachmentURL . '" />' . get_the_title($attachmentID) . '</a> <a class="delete-item" href="#">[X]</a></li>';
        }
      }

      // list all uploaded items
      echo '<ul class="uploaded-items">';

      if ( $softwareMarkup ) {
        echo $softwareMarkup;
      }
      else {
        // all software files have been deleted, so update meta to show there are none
        update_post_meta($post->ID, 'metafluidics-device-software', '');
      }

      echo '</ul>';
    }

    ?>

    <input class="upload-input" name="metafluidics-device-software" type="hidden" value="<?php echo esc_attr( implode($images, ',') ); ?>" />

    <?php
  }


  // generate "likes" meta box
  function metafluidics_device_likes_callback($post) {
    wp_nonce_field( 'metafluidics_device_likes_save_data', 'metafluidics_device_likes_nonce' );
    $value = get_post_meta( $post->ID, 'metafluidics-device-likes', true );
    if ( !$value || is_wp_error( $value ) ) {
      $value = 0;
      update_post_meta( $post->ID, 'metafluidics-device-likes', $value );
  	}
    echo '<p>' . $value . ' Likes</p>';
  }

  // generate "views" meta box
  function metafluidics_device_views_callback($post) {
    wp_nonce_field( 'metafluidics_device_views_save_data', 'metafluidics_device_views_nonce' );
    $value = get_post_meta( $post->ID, 'metafluidics-device-views', true );
    if ( !$value || is_wp_error( $value ) ) {
      $value = 0;
      update_post_meta( $post->ID, 'metafluidics-device-views', $value );
    }
    echo '<p>' . $value . ' Views</p>';
  }

  // generate "downloads" meta box
  function metafluidics_device_downloads_total_callback($post) {
    wp_nonce_field( 'metafluidics_device_downloads_save_data', 'metafluidics_device_downloads_total_nonce' );
    $value = get_post_meta( $post->ID, 'metafluidics-device-downloads-total', true );
    if ( !$value || is_wp_error( $value ) ) {
      $value = 0;
      update_post_meta( $post->ID, 'metafluidics-device-downloads-total', $value );
    }
    echo '<p>' . $value . ' Total Downloads</p>';
  }

  // generate "publication" meta box
  function metafluidics_device_publications_callback($post) {
    // [ title => 'site title', url => 'http://...' ],
    wp_nonce_field( 'metafluidics_device_publications_save_data', 'metafluidics_device_publications_nonce' );
    $value = get_post_meta( $post->ID, 'metafluidics-device-publications', true );
    echo '<ul>';

    if ( $value || !is_wp_error( $value ) ) {
      $json = json_decode( $value );

      if ( $json ) {
        foreach ( $json as $key=>$item ) {
          echo '<li><input type="text" class="url-input name" placeholder="Title" value="' . $item->name .'" /> ' .
               '<input type="text" class="url-input url" placeholder="URL" value="' . $item->url . '" />' .
               '<a href="#" class="url-delete">[X]</a></li>';
        }
      }
    }

    echo '</ul><input type="hidden" class="url-value" name="metafluidics-device-publications" value="' . htmlspecialchars($value) . '" />' .
         '<p><a href="#" class="url-add">[+] add publication</a></p>';
  }

  // generate "tutorials" meta box
  function metafluidics_device_tutorials_callback($post) {
    wp_nonce_field( 'metafluidics_device_tutorials_save_data', 'metafluidics_device_tutorials_nonce' );
    $value = htmlspecialchars_decode(get_post_meta( $post->ID, 'metafluidics-device-tutorials', true ));
    echo '<ul>';

    if ( $value || !is_wp_error( $value ) ) {
      $json = json_decode( $value );

      if ( $json ) {
        foreach ( $json as $key=>$item ) {
          echo '<li><input type="text" class="url-input name" placeholder="Title" value="' . $item->name .'" /> ' .
               '<input type="text" class="url-input url" placeholder="URL" value="' . $item->url . '" />' .
               '<a href="#" class="url-delete">[X]</a></li>';
        }
      }
    }

    echo '</ul><input type="hidden" class="url-value" name="metafluidics-device-tutorials" value="' . htmlspecialchars($value)  . '" />' .
         '<p><a href="#" class="url-add">[+] add tutorial</a></p>';
  }

  // generate "remix" meta box
  function metafluidics_device_remixed_callback($post) {
    wp_nonce_field( 'metafluidics_device_remixed_save_data', 'metafluidics_device_remixed_nonce' );
    $value = htmlspecialchars_decode(get_post_meta( $post->ID, 'metafluidics-device-remixed', true ));
    echo '<ul>';

    if ( $value || !is_wp_error( $value ) ) {
      $json = json_decode( $value );

      if ( $json ) {
        foreach ( $json as $key=>$item ) {
          echo '<li><input type="text" class="url-input name" placeholder="Title" value="' . $item->name .'" /> ' .
               '<input type="text" class="url-input url" placeholder="URL" value="' . $item->url . '" />' .
               '<a href="#" class="url-delete">[X]</a></li>';
        }
      }
    }

    echo '</ul><input type="hidden" class="url-value" name="metafluidics-device-remixed" value="' . htmlspecialchars($value)  . '" />' .
         '<p><a href="#" class="url-add">[+] add remix origin</a></p>';
  }

  // generate "parts" meta box
  function metafluidics_device_parts_callback($post) {
    wp_nonce_field( 'metafluidics_device_parts_save_data', 'metafluidics_device_parts_nonce' );
    $value = htmlspecialchars_decode(get_post_meta( $post->ID, 'metafluidics-device-parts', true ));
    echo '<ul>';

    if ( $value || !is_wp_error( $value ) ) {
      $json = json_decode( $value );

      if ( $json ) {
        foreach ( $json as $key=>$item ) {
          echo '<li><input type="text" class="url-input name" placeholder="Title" value="' . $item->name .'" /> ' .
               '<input type="text" class="url-input url" placeholder="URL" value="' . $item->url . '" />' .
               '<a href="#" class="url-delete">[X]</a></li>';
        }
      }
    }

    echo '</ul><input type="hidden" class="url-value" name="metafluidics-device-parts" value="' . htmlspecialchars($value)  . '" />' .
         '<p><a href="#" class="url-add">[+] add parts origin</a></p>';
  }

  // ##########################
  // Save Meta Box Form Values
  // ##########################

  // save all meta box data - update for images and software uploads
  add_action( 'save_post', 'metafluidics_device_meta_save_data' );
  function metafluidics_device_meta_save_data($post_id) {
    // set collection of labels for DRYness
    global $metaBoxCollection, $metaBoxFileCollection;

    // edit arunav step 4
    $my_data1 = $_POST['metafluidics-license'];
    update_post_meta( $post_id, 'metafluidics-license', $my_data1 );
    // end of edit

    foreach ( $metaBoxCollection as $metaBoxLabel ) {

      $metaBoxLabelPost = str_replace('_', '-', $metaBoxLabel);

      if ( $metaBoxLabel === 'likes' || $metaBoxLabel === 'views' || $metaBoxLabel === 'downloads_total' ) {
        continue;
      }

      if ( !isset( $_POST['metafluidics_device_' . $metaBoxLabel . '_nonce'] ) ) {
        return;
      }

      if ( !wp_verify_nonce( $_POST['metafluidics_device_' . $metaBoxLabel . '_nonce'], 'metafluidics_device_' . $metaBoxLabel . '_save_data' ) ) {
        return;
      }

      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
      }

      if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) ) {
          return;
        }
      } else {
        if ( !current_user_can( 'edit_post', $post_id ) ) {
          return;
        }
      }

      if ( in_array($metaBoxLabel, $metaBoxFileCollection) ) {

        $fileTypesImages = array(
          'jpg|jpeg|jpe'                 => 'image/jpeg',
          'gif'                          => 'image/gif',
          'png'                          => 'image/png'
        );

        $fileTypesText = array(
          'txt|asc|c|cc|h'               => 'text/plain',
          'doc'                          => 'application/msword'
        );

        $fileTypesSoftware = array(
          'zip'                          => 'application/zip'
        );

        $fileTypesDesignFiles = array(
          // Image formats
          'jpg|jpeg|jpe'                 => 'image/jpeg',
          'gif'                          => 'image/gif',
          'png'                          => 'image/png',
          'bmp'                          => 'image/bmp',
          'tif|tiff'                     => 'image/tiff',
          'ico'                          => 'image/x-icon',

          // Text formats
          'txt|asc|c|cc|h'               => 'text/plain',
          'csv'                          => 'text/csv',
          'tsv'                          => 'text/tab-separated-values',
          'ics'                          => 'text/calendar',
          'rtx'                          => 'text/richtext',
          'css'                          => 'text/css',
          'htm|html'                     => 'text/html',

            // Misc application formats
          'rtf'                          => 'application/rtf',
          'js'                           => 'application/javascript',
          'pdf'                          => 'application/pdf',
          'swf'                          => 'application/x-shockwave-flash',
          'class'                        => 'application/java',
          'tar'                          => 'application/x-tar',
          'zip'                          => 'application/zip',
          'gz|gzip'                      => 'application/x-gzip',
          'rar'                          => 'application/rar',
          '7z'                           => 'application/x-7z-compressed',
          'exe'                          => 'application/x-msdownload',

          // MS Office formats
          'doc'                          => 'application/msword',
          'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
          'wri'                          => 'application/vnd.ms-write',
          'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
          'mdb'                          => 'application/vnd.ms-access',
          'mpp'                          => 'application/vnd.ms-project',
          'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
          'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
          'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
          'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
          'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
          'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
          'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
          'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
          'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
          'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
          'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
          'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
          'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
          'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
          'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
          'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
          'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
          'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',

          // OpenOffice formats
          'odt'                          => 'application/vnd.oasis.opendocument.text',
          'odp'                          => 'application/vnd.oasis.opendocument.presentation',
          'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
          'odg'                          => 'application/vnd.oasis.opendocument.graphics',
          'odc'                          => 'application/vnd.oasis.opendocument.chart',
          'odb'                          => 'application/vnd.oasis.opendocument.database',
          'odf'                          => 'application/vnd.oasis.opendocument.formula',

          // WordPerfect formats
          'wp|wpd'                       => 'application/wordperfect',

          // iWork formats
          'key'                          => 'application/vnd.apple.keynote',
          'numbers'                      => 'application/vnd.apple.numbers',
          'pages'                        => 'application/vnd.apple.pages',
        );

        // acceptable file types - TODO: actually check
        switch ($metaBoxLabel) {
          case 'thumbnail':
            $supportedTypes = $fileTypesImages;
            break;
          case 'images':
            $supportedTypes = $fileTypesImages;
            break;
          case 'design_files':
            $supportedTypes = $fileTypesDesignFiles;
            break;
          case 'bill_of_materials':
            $supportedTypes = $fileTypesText;
            break;
          case 'build_instructions':
            $supportedTypes = $fileTypesText;
            break;
          case 'software':
            $supportedTypes = $fileTypesCompressed;
            break;
          default:
            $supportedTypes = $fileTypesImages;
        }

        if ( !isset( $_POST['metafluidics-device-' . $metaBoxLabelPost] ) ) {
          return;
        }
      }


      $my_data = $_POST['metafluidics-device-' . $metaBoxLabelPost];
      update_post_meta( $post_id, 'metafluidics-device-' . $metaBoxLabelPost, $my_data );
    }
  }

}
