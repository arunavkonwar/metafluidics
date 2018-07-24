<?php
/**
 * The template for displaying project archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

get_header(); ?>

  <header class="page-header">
    <?php the_archive_title( '<h1 class="page-title">', '</h1>' );?>
  </header><!-- .page-header -->

  <div id="primary" class="content-area">
    <main id="main" class="site-main box-area" role="main">

      <?php
        if ( have_posts() ) :

          while ( have_posts() ) : the_post();

            get_template_part( 'template-parts/device', 'box');

          endwhile;

          the_posts_navigation();

        else :

           get_template_part( 'template-parts/content', 'none' );

        endif;
      ?>

    </main><!-- #main -->
  </div><!-- #primary -->

<?php get_footer(); ?>
