<?php
/*
  Plugin Name: Metafluidics Custom User Fields
  Description: Creates custom user fields.
  Author: Bocoup
  Version: 1.0
  Author URI: http://bocoup.com
*/

// set up roles and capabilities
add_action( 'admin_init', function(){

  // set subscriber role
  remove_role( 'subscriber' );
  add_role( 'subscriber', 'Subscriber', array(
    'read' => true,
    'upload_files' => true
  ) );

  // remove unnecessary roles
  remove_role( 'contributor' );
  remove_role( 'author' );

  // add device capabilities to subscribers
  $roles = array( 'subscriber', 'editor', 'administrator' );
  foreach ( $roles as $role ) {
    $thisRole = get_role( $role );

    if ( $role !== 'subscriber' ) {
      $thisRole->add_cap( 'edit_others_metafluidics_devices' );
      $thisRole->add_cap( 'delete_others_metafluidics_devices' );
    }
    $thisRole->add_cap( 'read' );
    $thisRole->add_cap( 'read_metafluidics_device');
    $thisRole->add_cap( 'read_private_metafluidics_devices' );
    $thisRole->add_cap( 'edit_metafluidics_device' );
    $thisRole->add_cap( 'edit_metafluidics_devices' );
    $thisRole->add_cap( 'edit_published_metafluidics_devices' );
    $thisRole->add_cap( 'publish_metafluidics_devices' );
    $thisRole->add_cap( 'delete_private_metafluidics_devices' );
    $thisRole->add_cap( 'delete_published_metafluidics_devices' );
    $thisRole->add_cap( 'delete_draft_metafluidics_devices' );
    $thisRole->add_cap( 'delete_metafluidics_devices' );
  }
}, 999);

// customize admin user login form
add_action('login_enqueue_scripts', 'metafluidics_admin_login_scripts');
function metafluidics_admin_login_scripts() {
  wp_enqueue_style('custom-login', '/wp-content/mu-plugins/metafluidics/css/login.css' );
}

// redirect users to dashboard after login
add_filter( 'login_redirect', 'metafluidics_login_redirect', 10, 3 );
function metafluidics_login_redirect( $redirect_to, $request, $user ) {
  global $user;

  if ( isset( $user->roles ) && is_array( $user->roles ) ) {
    if ( in_array( 'administrator', $user->roles ) ) {
      return $redirect_to;
    }
    else {
      return home_url();
    }
  }
  else {
    return $redirect_to;
  }
}

// add header masthead to login form
add_filter('login_head', 'metafluidics_login_header');
function metafluidics_login_header() {
  include( get_template_directory() . '/template-parts/header-masthead.php');
};

// change logo link to home url
add_filter('login_headerurl','metafluidics_login_logo_link');
function metafluidics_login_logo_link() {
	return home_url();
}

// change logo link tooltip to site name
add_filter('login_headertitle', 'metafluidics_login_logo_tooltip');
function metafluidics_login_logo_tooltip() {
	return get_bloginfo('name');
}

// add custom fields to user profile
add_action('show_user_profile', 'metafluidics_show_extra_profile_fields');
add_action('edit_user_profile', 'metafluidics_show_extra_profile_fields');
add_action('personal_options_update', 'metafluidics_save_extra_profile_fields');
add_action('edit_user_profile_update', 'metafluidics_save_extra_profile_fields');

function metafluidics_show_extra_profile_fields( $user ) { ?>
  <table class="form-table">
    <tr>
      <th><label for="institution"><?php _e( 'Institution/Organization', 'metafluidics' ); ?></label></th>
      <td>
        <input type="text" name="institution" id="institution" value="<?php echo esc_attr( get_the_author_meta( 'institution', $user->ID ) ); ?>" class="regular-text" /><br />
        <span class="description"><?php _e( 'Please enter your institution or organization. If an individual, leave blank.', 'metafluidics' ); ?></span>
      </td>
    </tr>

    <tr>
      <th><label for="show_institution"><?php _e( 'Make Institution/Organization Public?', 'metafluidics' ); ?></label></th>
      <td>
        <?php
          $checked = ( get_the_author_meta('show_institution', $user->ID) === 'yes' ) ? 'checked="checked"' : '';
        ?>
         <label for="show_institution"><input type="checkbox" name="show_institution" id="show_institution" value="yes" <?php echo $checked; ?>> <?php _e( 'Show Institution/Organization on public profile page.', 'metafluidics' ); ?></label><br />
        <span class="description"><?php _e( 'Checking this will make your institution or organization visible on your public profile page.', 'metafluidics' ); ?></span>
      </td>
    </tr>

    <tr>
      <th><label for="show_email"><?php _e( 'Make Email Public?', 'metafluidics' ); ?></label></th>
      <td>
        <?php
          $checked = ( get_the_author_meta('show_email', $user->ID) === 'yes' ) ? 'checked="checked"' : '';
        ?>
         <label for="show_email"><input type="checkbox" name="show_email" id="show_email" value="yes" <?php echo $checked; ?>> <?php _e( 'Show email on public profile page and device pages.', 'metafluidics' ); ?></label><br />
        <span class="description"><?php _e( 'Checking this will make your email address visible on your public profile page and device pages.', 'metafluidics' ); ?></span>
      </td>
    </tr>

  </table>
<?php }

function metafluidics_save_extra_profile_fields( $user_id ) {
  if ( !current_user_can( 'edit_user', $user_id ) ) {
    return false;
  }

  update_usermeta( $user_id, 'institution', sanitize_text_field($_POST['institution']) );
  update_usermeta( $user_id, 'show_institution', sanitize_text_field($_POST['show_institution']) );
  update_usermeta( $user_id, 'show_email', sanitize_text_field($_POST['show_email']) );
}

// add new user form (user-new.php)
add_action('user_new_form', 'metafluidics_add_user_form');
function metafluidics_add_user_form() {

  $display_name = ( !empty( $_POST['display_name'] ) ) ? trim( $_POST['display_name'] ) : '';
  $institution = ( !empty( $_POST['institution'] ) ) ? trim( $_POST['institution'] ) : '';
  $show_institution = ( !empty( $_POST['show_institution'] ) ) ? trim( $_POST['show_institution'] ) : '';
  $show_email = ( !empty( $_POST['show_email'] ) ) ? trim( $_POST['show_email'] ) : '';
?>

  <table class="form-table">
    <tr>
      <th><label for="display_name"><?php _e( 'Display Name', 'metafluidics' ); ?></label></th>
      <td>
        <input type="text" name="display_name" id="display_name" class="regular-text" /><br />
        <span class="description"><?php _e( 'Enter the display name to show throughout the website.', 'metafluidics' ); ?></span>
      </td>
    </tr>
    <tr>
      <th><label for="institution"><?php _e( 'Institution/Organization', 'metafluidics' ); ?></label></th>
      <td>
        <input type="text" name="institution" id="institution" class="regular-text" /><br />
        <span class="description"><?php _e( 'Enter the institution or organization. If an individual, leave blank.', 'metafluidics' ); ?></span>
      </td>
    </tr>

    <tr>
      <th><label for="show_institution"><?php _e( 'Make Institution/Organization Public?', 'metafluidics' ); ?></label></th>
      <td>
        <label for="show_institution"><input type="checkbox" name="show_institution" id="show_institution" value="yes" /> <?php _e( 'Show Institution/Organization on public profile page.', 'metafluidics' ); ?></label><br />
        <span class="description"><?php _e( 'Checking this will make the institution or organization visible on the user\'s public profile page.', 'metafluidics' ); ?></span>
      </td>
    </tr>

    <tr>
      <th><label for="show_email"><?php _e( 'Make Email Public?', 'metafluidics' ); ?></label></th>
      <td>
        <label for="show_email"><input type="checkbox" name="show_email" id="show_email" value="yes" /> <?php _e( 'Show email on public profile page and device pages.', 'metafluidics' ); ?></label><br />
        <span class="description"><?php _e( 'Checking this will make your email address visible on your public profile page and device pages.', 'metafluidics' ); ?></span>
      </td>
    </tr>

  </table>

    <?php
}

// registration form (wp-login.php)
add_action('register_form', 'metafluidics_user_registration_form');
function metafluidics_user_registration_form() {

  $first_name = ( !empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
  $last_name = ( !empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
  $display_name = ( !empty( $_POST['display_name'] ) ) ? trim( $_POST['display_name'] ) : '';
  $institution = ( !empty( $_POST['institution'] ) ) ? trim( $_POST['institution'] ) : '';
  $show_institution = ( !empty( $_POST['show_institution'] ) ) ? trim( $_POST['show_institution'] ) : '';
  $show_email = ( !empty( $_POST['show_email'] ) ) ? trim( $_POST['show_email'] ) : '';
?>
  <p>
    <label for="first_name"><?php _e( 'First Name', 'metafluidics' ); ?><br />
    <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
  </p>
  <p>
    <label for="last_name"><?php _e( 'Last Name', 'metafluidics' ); ?><br />
    <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
  </p>
  <p>
    <label for="display_name"><?php _e( 'Display Name', 'metafluidics' ); ?><br />
    <input type="text" name="display_name" id="display_name" class="input" value="<?php echo esc_attr( wp_unslash( $display_name ) ); ?>" size="25" /></label>
  </p>
  <p>
    <label for="institution"><?php _e( 'Institution/Organization', 'metafluidics' ); ?><br />
    <input type="text" name="institution" id="institution" class="input" value="<?php echo esc_attr( wp_unslash( $institution ) ); ?>" size="25" /></label>
  </p>
  <p>
    <label for="show_institution" style="display: block; margin-bottom: 20px;" ><input type="checkbox" name="show_institution" id="show_institution" value="yes" ?>
    <?php _e( 'Show Institution/Organization on public profile?', 'metafluidics' ); ?></label>
  </p>
  <p>
    <label for="show_email" style="display: block; margin-bottom: 20px;" ><input type="checkbox" name="show_email" id="show_email" value="yes" ?>
    <?php _e( 'Show email on public profile and device pages?', 'metafluidics' ); ?></label>
  </p>
    <?php
}

// save user info on registration
add_action( 'user_register', 'metafluidics_user_register' );
function metafluidics_user_register( $user_id ) {
  $textFields = ['first_name', 'last_name', 'display_name', 'institution', 'show_institution', 'show_email'];

  foreach ( $textFields as $field ) {
    if ( !empty($_POST[$field]) ) {
      update_user_meta($user_id, $field, trim($_POST[$field]));
    }
  }

}

// add number of device posts to users page
add_action( 'manage_users_columns', 'metafluidics_users_column' );
function metafluidics_users_column( $column_headers ) {
  $column_headers['device_posts'] = 'Devices';
  return $column_headers;
}

add_action( 'manage_users_custom_column', 'metafluidics_users_custom_column', 10, 3 );
function metafluidics_users_custom_column( $custom_column, $column_name, $user_id ) {
  if ( $column_name === 'device_posts' ) {

    global $wpdb;
    $count = (int) $wpdb->get_var( $wpdb->prepare(
      "SELECT COUNT(ID) FROM $wpdb->posts WHERE
      post_type = 'metafluidics_device' AND post_status = 'publish' AND post_author = %d",
      $user_id
    ) );
    return ( $count > 0 ) ? '<a href="edit.php?author=' . $user_id . '&post_type=metafluidics_device">' . $count . '</a>' : $count;
  }
}
