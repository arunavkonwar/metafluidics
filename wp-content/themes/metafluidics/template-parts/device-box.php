<?php
/**
 * Template part for displaying a device archive box
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

 	/* METRICS */

	// views
	$views = get_post_meta( $post->ID, 'metafluidics-device-views', true );
	if ( !$views || is_wp_error( $views ) ) {
		$views = 0;
	}

	// downloads
	$downloads = get_post_meta( $post->ID, 'metafluidics-device-downloads-total', true );
	if ( !$downloads || is_wp_error( $downloads ) ) {
		$downloads = 0;
	}

	// likes count
	$likes = get_post_meta( $post->ID, 'metafluidics-device-likes', true );
	if ( !$likes || is_wp_error( $likes ) ) {
		$likes = 0;
	}

	// like status
	global $current_user;
	get_currentuserinfo();
	$userLikes = explode( ',', get_user_meta( $current_user->ID, 'user-device-likes', true) );
	$userLikedAlready = in_array( $post->ID, $userLikes );
	$likedStatus = ( $userLikedAlready ) ? 'liked' : '';

	// thumbnail
	$thumbnailID = get_post_meta( $post->ID, 'metafluidics-device-thumbnail', true );

	if ( $thumbnailID && !is_wp_error( $thumbnailID ) ) {
	 $thumbnailHTML = wp_get_attachment_image( $thumbnailID, 'archive-thumbnail' );
	}
	else {
	 $thumbnailHTML = '<img src="' . get_template_directory_uri() . '/assets/img/placeholder.jpg" />';
	}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<ul class="metrics">

		<li class="views"><?php echo $views; ?></li>

		<li class="likes">
			<?php if ( is_user_logged_in() ) { ?>
					<a href="#" class="like-this <?php echo $likedStatus; ?>" data-post="<?php the_ID(); ?>"><span class="like-count"><?php echo __( $likes ); ?></span></a>
			<?php }
						else { ?>
							<span class="like-count"><?php echo __( $likes ); ?></span>
			<?php } ?>
		</li>

		<li class="downloads"><?php echo $downloads; ?></li>

	</ul>

	<div class="archive-thumbnail">
		<a href="<?php echo get_permalink(); ?>">
			<?php echo $thumbnailHTML; ?>
		</a>
	</div>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<div class="entry-meta">
			<?php metafluidics_device_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
</article><!-- #post-## -->
