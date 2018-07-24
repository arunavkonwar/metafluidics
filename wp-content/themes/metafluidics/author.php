<?php
/**
 * The template for displaying the user dashboard pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

get_header();

// get current logged in user if logged in
if ( is_user_logged_in() ) {
  global $current_user;
  get_currentuserinfo();

  $loggedIn = true;
}
else {
  $loggedIn = false;
}

// get current page's author info
global $wp_query;
$currentAuthor = $wp_query->get_queried_object();
$isMyPage = ( $loggedIn && $currentAuthor->user_login === $current_user->user_login ) ? true : false;

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
$author = get_user_by('slug', get_query_var('author_name'))->ID;
?>

<header class="page-header">

  <div id="user-profile">
    <table class="profile-info">

    <?php

      // if this is current user
      if ( $isMyPage ) { ?>

          <tr>
            <th>Display Name: </th>
            <td><?php echo $current_user->display_name; ?></td>
          </tr>
          <tr>
            <th>First Name: </th>
            <td><?php echo $current_user->user_firstname; ?></td>
          </tr>
          <tr>
            <th>Last Name: </th>
            <td><?php echo $current_user->user_lastname; ?></td>
          </tr>
          <tr>
            <th>Email: </th>
            <td><?php echo $current_user->user_email; ?></td>
          </tr>
          <tr>
            <th>Email public?: </th>
            <td>
              <?php
                $show_email = get_user_meta( $current_user->ID, 'show_email', true);
                echo ( $show_email ) ? $show_email : 'no';
              ?>
            </td>
          </tr>
          <tr>
            <th>Affiliation: </th>
            <td><?php echo get_user_meta( $current_user->ID, 'institution', true); ?></td>
          </tr>
          <tr>
            <th>Affiliation public?: </th>
            <td>
              <?php
                $show_institution = get_user_meta( $current_user->ID, 'show_institution', true);
                echo ( $show_institution ) ? $show_institution : 'no';
              ?>
            </td>
          </tr>
          <tr>
            <th>Bio: </th>
            <td><?php echo $current_user->description; ?></td>
          </tr>
          <tfoot>
            <td><a class="edit-profile" href="<?php echo get_edit_user_link( $current_user->ID ); ?>">edit</a></td>
          </tfoot>

<?php }
      else { ?>

          <tr>
            <th>Display Name: </th>
            <td><?php echo $currentAuthor->display_name; ?></td>
          </tr>

        <?php
          // show institution if user said to make it public
          if ( get_user_meta($currentAuthor->ID, 'show_institution', true) === 'yes' ) { ?>
          <tr>
            <th>Affiliation: </th>
            <td><?php echo get_user_meta( $currentAuthor->ID, 'institution', true); ?></td>
          </tr>
        <?php
          }

          // show email if user said to make it public
          if ( get_user_meta($currentAuthor->ID, 'show_email', true) === 'yes' ) { ?>
          <tr>
            <th>Email: </th>
            <td><?php echo $currentAuthor->user_email; ?></td>
          </tr>
        <?php
          }
          ?>
          <tr>
            <th>Bio: </th>
            <td><?php echo $currentAuthor->description; ?></td>
          </tr>
          <?php
      } ?>
    </table>
  </div>
</header><!-- .page-header -->

  <?php

    if ( $isMyPage ) {
      // drafts
      $draftsQuery = new WP_Query( array( 'post_type' => 'metafluidics_device', 'paged' => $paged, 'author' => $author, 'post_status' => 'draft' ) );

      if ( $draftsQuery->have_posts() ) :

        echo '<div id="drafts" class="devices content-area box-area" role="main">' .
                '<h2>Drafts</h2>';

        while ( $draftsQuery->have_posts() ) : $draftsQuery->the_post();

          get_template_part( 'template-parts/device', 'box' );

        endwhile;

        if ( $draftsQuery->max_num_pages > 1 ) {
          echo '<div><a class="button view-more" href="/drafts">More Drafts</a></div>';
        }

        echo '</div>';

      endif;

      wp_reset_postdata();
    }
  ?>

  <?php
    // parts
    $partsQuery = new WP_Query( array( 'post_type' => 'metafluidics_device', 'paged' => $paged, 'author' => $author, 'post_status' => 'publish' ) );

    if ( $partsQuery->have_posts() ) :

      echo '<div id="posts" class="devices content-area box-area" role="main">' .
              '<h2>Parts</h2>';

      while ( $partsQuery->have_posts() ) : $partsQuery->the_post();

        get_template_part( 'template-parts/device', 'box' );

      endwhile;

      // post navigation
      echo '<nav class="navigation posts-navigation"><div class="nav-links cf"><span class="nav-previous">';
        previous_posts_link('&#9668; Previous Page', $partsQuery->max_num_pages);
      echo '</span><span class="nav-next">';
        next_posts_link('Next Page &#9658;', $partsQuery->max_num_pages);
      echo '</span></div></nav>';

      echo '</div>';

    endif;

    wp_reset_postdata();
  ?>

  <?php
    // blogs
    $blogQuery = new WP_Query( array( 'post_type' => 'post', 'paged' => $paged, 'author' => $author, 'showposts' => -1 ) );

    if ( $blogQuery->have_posts() ) :
      echo '<div id="blogs" class="content-area" role="main">' .
              '<h2>Blogs</h2>';

      while ( $blogQuery->have_posts() ) : $blogQuery->the_post();

        get_template_part( 'template-parts/blog', 'listing' );

      endwhile;

      // post navigation
      echo '<nav class="navigation posts-navigation"><div class="nav-links cf"><span class="nav-previous">';
        previous_posts_link('&#9668; Previous Page', $blogQuery->max_num_pages);
      echo '</span><span class="nav-next">';
        next_posts_link('Next Page &#9658;', $blogQuery->max_num_pages);
      echo '</span></div></nav>';

      echo '</div>';

    endif;

    wp_reset_postdata();
  ?>

  <?php
    // likes
    $likesArray = array_filter( explode( ',', get_user_meta( $author, 'user-device-likes', true) ) );

    if ( count( $likesArray ) ) :

      $likesQuery = new WP_Query( array( 'post_type' => 'metafluidics_device', 'post__in' => $likesArray, 'paged' => $paged, 'showposts' => 3 ) );

      if ( $likesQuery->have_posts() ) :
        echo '<div id="likes" class="devices content-area box-area" role="main">' .
                '<h2>Likes</h2>';

        while ( $likesQuery->have_posts() ) : $likesQuery->the_post();

          get_template_part( 'template-parts/device', 'box' );

        endwhile;

        if ( $likesQuery->max_num_pages > 1 ) :
          echo '<div><a class="button view-more" href="/likes">More Likes</a></div>';
        endif;

        echo '</div>';

      endif;

      wp_reset_postdata();

    endif;
  ?>

<?php get_footer(); ?>
