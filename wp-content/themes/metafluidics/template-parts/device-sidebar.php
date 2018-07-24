<?php
/**
 * Template part for displaying a device page sidebar
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

	// metrics - views
	$views = getPageViews( '/devices/' . $post->post_name . '/');
	if ( !$views ) {
		$views = 0;
	}
	update_post_meta( $post->ID, 'metafluidics-device-views', $views );

	// metrics - likes
	$likes = get_post_meta( $post->ID, 'metafluidics-device-likes', true );
	if ( !$likes || is_wp_error( $likes ) ) {
		$likes = 0;
	}

	global $current_user;
	get_currentuserinfo();
	$userLikes = explode( ',', get_user_meta( $current_user->ID, 'user-device-likes', true) );
	$userLikedAlready = in_array( $post->ID, $userLikes );

	if ( $userLikedAlready ) {
		$likedStatus = 'liked';
	}
	else {
		$likedStatus = '';
	}

	$likeCountText = ( $likes === '1' ) ? ' like' : ' likes';

	// metrics - downloads
	$downloadsHTML = '<table>';
	$downloadSections = ['bill-of-materials'=>'Bill of Materials', 'build-instructions'=>'Instructions', 'design-files'=>'Design Files', 'software'=>'Software'];

	foreach ( $downloadSections as $meta=>$header ) {

		$metaContent = get_post_meta($post->ID, 'metafluidics-device-' . $meta, true);
		if ( $metaContent ) {

			$filesArray = explode(',', get_post_meta($post->ID, 'metafluidics-device-' . $meta, true));
			if ( !is_wp_error($filesArray) ) {
				$metaContent = '<ul>';
				foreach ( $filesArray as $fileId ) {

					$downloads = get_post_meta( $fileId, 'metafluidics-device-downloads', true );
					if ( !$downloads || is_wp_error( $downloads ) ) {
						$downloads = 0;
					}

					$metaContent .= ( wp_get_attachment_url($fileId) ) ? '<li><a target="_blank" class="download" data-post="' . $fileId . '" data-parent="' . $post->ID . '" href="' . wp_get_attachment_url($fileId) . '" />' . get_the_title($fileId) . ' <span class="download-metric">&#9660; <span class="download-count">' . $downloads . '</span></span></a> </li>' : '';
				}
				$metaContent .= '</ul>';
			}

			$downloadsHTML .= '<tr class="meta-' . $meta . '">' .
													'<th>' . $header . '</th>' .
													'<td>' . $metaContent . '</td>' .
												'</tr>';
				} // end "if meta content exists"
			} // end "for each meta section"

	$downloadsHTML .= '</table>';
	$downloadsTotal = get_post_meta( $post->ID, 'metafluidics-device-downloads-total', true );
?>

<section id="sidebar">

	<div id="metrics" class="metrics cf">
		<h2>Part Data</h2>

		<ul>
			<li class="views"><?php echo $views; ?></li>

			<li class="likes">
				<?php if ( is_user_logged_in() ) { ?>
						<a href="#" class="like-this <?php echo $likedStatus; ?>" data-post="<?php the_ID(); ?>"><span class="like-count"><?php echo __( $likes ); ?></span></a>
				<?php }
							else { ?>
								<span class="like-count"><?php echo __( $likes ); ?></span>
				<?php } ?>
			</li>

			<li class="downloads"><span class="total-downloads"><?php echo $downloadsTotal; ?></span></li>
		</ul>

	</div>

	<div id="downloads" class="content-box">
		<h2>Downloads Available</h2>

		<?php
			if ( !is_user_logged_in() ) {
		?>
			<p><a href="<?php echo wp_login_url( get_permalink() ); ?>">You must be logged in to download device files.</a></p>
		<?php
			}
			else {
				echo $downloadsHTML;
			} ?>
	</div>

	<div id="metadata">
			<div id="description">
			<h2>What does this device do?</h2>

			<?php
				$metaContent = get_post_meta($post->ID, 'metafluidics-device-description', true);

				if ( $metaContent ) {
					echo $metaContent;
				}
				else {
					echo "No description given.";
				}
			?>
		</div>

		<?php
			// tag sections
			$tagSections = ['keywords'=>'Keywords', 'material'=>'Material', 'technology'=>'Fabrication Technology', 'hardware'=>'Hardware'];
			$tagArgs = array(
				'orderby' => 'count',
				'order' => 'DESC',
				'hide_empty' => false,
			);

			foreach ( $tagSections as $meta=>$key ) {
				$tagsArray = wp_get_post_terms($post->ID, 'metafluidics_device_' . $meta, $tagArgs);

				if ( count($tagsArray) > 0 ) {
					echo '<h2>' . $key . ':</h2>';
					echo '<ul class="tags">';

					for ( $i = 0; $i < count($tagsArray); $i++ ) {
						echo '<li><a href="' . get_term_link( $tagsArray[$i]->slug, 'metafluidics_device_' . $meta )  . '">' . $tagsArray[$i]->name . '</a></li>';
					}

					echo '</ul>';
				}
			}
		?>

		<?php
			// url sections
			$urlSections = ['publications'=>'Publications', 'tutorials'=>'Tutorials', 'remixed'=>'Remixed From', 'parts'=>'Other Parts Used'];

			echo '<table id="urls">';
			foreach ( $urlSections as $meta=>$key ) {
				$urls = get_post_meta( $post->ID, 'metafluidics-device-' . $meta, true);

				if ( $urls && !is_wp_error($urls) ) {
					$json = json_decode( $urls );

					echo '<tr><th><h2>' . $key . ':</h2></th>';
					echo '<td><ul class="urls">';
					foreach ( $json as $key=>$item ) {
						echo '<li><a href="' . $item->url . '">' . $item->name . '</a></li>';
					}
					echo '</ul></td>';
				}
			}
			echo '</table>';
		?>
	</div><!--//end #metadata -->

</section>
