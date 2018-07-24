<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package metafluidics
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">

  		<h3>Metafluidics</h3>

  		<p><img src="<?php echo get_template_directory_uri() ?>/assets/img/logo.png" /></p>

    	<?php wp_nav_menu( array( 'theme_location' => 'footer-menu' ) ); ?>

  		<div class="about">
				<p>
    			Metafluidics is an open repository for fluidic systems built by <a href="https://www.ll.mit.edu/"><strong>MIT Lincoln Laboratory</strong></a> in partnership with <a href="https://bocoup.com/"><strong>Bocoup</strong></a> and maintained by the Community Biotechnology Initiative at the <a href="http://www.media.mit.edu"><strong>MIT Media Lab</strong></a>.
			  </p>
 				<p>
					Share your fluidic devices with the global community today!
				</p>
			</div>




			<div>
				<a href="https://facebook.com/metafluidics">
					<img title="Facebook" src="https://s3.amazonaws.com/metafluidics-stage/wp-content/uploads/2017/05/30204031/facebook-dreamstale25.png" alt="" height="50" width="50" hspace="20">
				</a>
				<a href="https://twitter.com/metafluidics">
					<img title="Twitter" src="https://s3.amazonaws.com/metafluidics-stage/wp-content/uploads/2017/05/30204033/twitter-dreamstale71.png" alt="" height="50" width="50" hspace="20">
				</a>
				<a href="https://instagram.com/metafluidics">
					<img title="Instagram" src="https://s3.amazonaws.com/metafluidics-stage/wp-content/uploads/2017/05/30204032/instagram-dreamstale43.png" alt="" height="50" width="50" hspace="20">
				</a>
				<a href="mailto: metafluidics.info@gmail.com">
					<img title="Email" src="https://s3.amazonaws.com/metafluidics-stage/wp-content/uploads/2017/05/30205012/mail-dreamstale47.png" alt="" height="50" width="50" hspace="20">
				</a>
		  </div>
  		<p class="copyright">&copy; metafluidics</p>

		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-71945047-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
