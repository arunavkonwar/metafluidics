<?php
/**
 * Template part for displaying a device carousel
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package metafluidics
 */

?>

<?php
	// get images for carousel
	$thumbnailID = get_post_meta($post->ID, 'metafluidics-device-thumbnail', true);
	$images = get_post_meta($post->ID, 'metafluidics-device-images', true);
	$imagesArray = array();

	// put any uploaded images in array
	if ( $images && !is_wp_error($images) ) {
		$imagesArray = explode(',', $images);
	}

	// thumb is first part of carousel
	if ( $thumbnailID && !is_wp_error($thumbnailID) ) {
		array_unshift( $imagesArray, $thumbnailID );
	}

	if ( count( $imagesArray ) ) {
?>
		<div class="device-carousel">
			<?php
				$imageContent = '<ul class="carousel-originals cf">';
				foreach ( $imagesArray as $imageId ) {
					$imageContent .= ( wp_get_attachment_image_src($imageId) ) ? '<li class="attachment-' . $imageId . '">' . wp_get_attachment_image($imageId,'carousel') . '</li>' : '';
				}
				$imageContent .= '</ul>';

				echo $imageContent;
			?>

			<?php
				$imageContent = '<ul class="carousel-thumbnails">';
				foreach ( $imagesArray as $imageId ) {
					$imageContent .= ( wp_get_attachment_image_src($imageId) ) ? '<li id="attachment-' . $imageId . '">' . wp_get_attachment_image($imageId, 'carousel-thumb') . '</li>' : '';
				}
				$imageContent .= '</ul>';

				echo $imageContent;
			?>
		</div>

<?php
	} // end "if images" carousel
?>
