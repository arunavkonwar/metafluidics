<?php
/**
 * Template Name: Registration Page
 * The template for displaying the registration page.
 *
 * @package metafluidics
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<form name="registerform" id="registerform" action="http://metafluidics.loc/wp-login.php?action=register" method="post" novalidate="novalidate">
				    	<p>
				    		<label for="user_login">Username<br />
				    		<input type="text" name="user_login" id="user_login" class="input" value="" size="20" /></label>
				    	</p>
				    	<p>
				    		<label for="user_email">E-mail<br />
				    		<input type="email" name="user_email" id="user_email" class="input" value="" size="25" /></label>
				    	</p>
				    	  <p>
				        <label for="first_name">First Name<br />
				        <input type="text" name="first_name" id="first_name" class="input" value="" size="25" /></label>
				      </p>
				      <p>
				        <label for="last_name">Last Name<br />
				        <input type="text" name="last_name" id="last_name" class="input" value="" size="25" /></label>
				      </p>
				      <p>
				        <label for="display_name">Display Name<br />
				        <input type="text" name="display_name" id="display_name" class="input" value="" size="25" /></label>
				      </p>
				      <p>
				        <label for="institution">Institution/Organization<br />
				        <input type="text" name="institution" id="institution" class="input" value="" size="25" /></label>
				      </p>
				      <p>
				        <label for="show_institution" style="display: block; margin-bottom: 20px;"><input type="checkbox" name="show_institution" id="show_institution" value="yes" ?="" />
				        Show Institution/Organization on public profile?</label>
				      </p>
				      <p id="reg_passmail">Registration confirmation will be e-mailed to you.</p>
				    	<input type="hidden" name="redirect_to" value="" />
				    	<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Register"></p>
				    </form>
					</div><!-- .entry-content -->

				</article><!-- #post-## -->



				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
