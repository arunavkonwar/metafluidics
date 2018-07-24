<?php
/**
 * The header masthead.
 *
 * The header code, a snippet of the Metafluidics theme header.php, which is displayed
 * on the top of the wp-login.php generated pages.
 *
 */

  // if being used in login or register page, remove login-css and add template stylesheet
  if ( in_array($_SERVER['PHP_SELF'], array('/wp-login.php')) ) { ?>
    <link rel="stylesheet" href="<?php echo bloginfo('template_url') . '/style.css'; ?>" />
    <script type="text/javascript">
      (function(){
        // remove admin login-css script
        var loginCSS = document.getElementById('login-css');
        var buttonCSS = document.getElementById('buttons-css');
        if ( loginCSS ) {
          loginCSS.remove();
          buttonCSS.remove();
          console.log('lol');

        }
      })();
    </script>

<?php
  } // end if is_admin
?>

	<header id="masthead" class="site-header" role="banner">
		<div class="masthead-top cf">
  		<div class="site-branding">
  		  <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?>
  		  <small><?php bloginfo( 'description' ); ?></small></a></h1>
  		</div>

  		<div class="site-tools">
    		<nav id="site-navigation" class="navigation" role="navigation">
    			<?php
      			if ( is_user_logged_in() ) {
        			global $current_user;
        			get_currentuserinfo();

        			// display create and user menu links ?>
      			  <a class="button" id="nav-call" href="/create-part">+ Add a New Part</a>

              <div id="tool-links">
                <a href="/blog">Our Blog</a>
                <ul id="user-dropdown-menu">
                  <li>
                    <a href="#" id="this-user"><?php echo $current_user->display_name; ?></a>
                      <div class="menu-user-logged-in-menu-container">
                        <ul id="user-menu" class="menu">
                          <li class="menu-item "><a href="<?php echo get_author_posts_url($current_user->ID); ?>">Dashboard</a></li>
                          <li class="menu-item"><a href="<?php echo wp_logout_url( home_url() ); ?>">Sign Out</a></li>
                        </ul>
                      </div>
                  </li>
                </ul>
              </div>
      <?php }
            else {
              // display create and user menu links ?>
              <a class="button" id="nav-call" href="<?php echo wp_registration_url(); ?>">Sign Up</a>

              <div id="tool-links">
                <a href="/blog">Our Blog</a>
                <a href="<?php echo wp_login_url(); ?>" id="login">Log In</a>
              </div>

      <?php } ?>
    		</nav>
  		</div>

		</div>
	</header>
