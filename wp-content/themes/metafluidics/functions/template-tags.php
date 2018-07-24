<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package metafluidics
 */

if ( ! function_exists( 'metafluidics_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function metafluidics_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'metafluidics' ), $time_string
	);

	$byline = sprintf(
		esc_html_x( '%s', 'post author', 'metafluidics' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $byline . ' &bullet; ' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'metafluidics_footer_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function metafluidics_footer_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( '%s', 'post date', 'metafluidics' ), $time_string
	);

	$byline = sprintf(
		esc_html_x( '%s', 'post author', 'metafluidics' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo $byline . ' <br /> ' . $posted_on; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'metafluidics_device_posted_on' ) ) :
/**
 * Prints HTML with meta information for the device's author
 */
function metafluidics_device_posted_on() {

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'metafluidics' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo $byline;
}
endif;

if ( ! function_exists( 'metafluidics_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function metafluidics_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		// author and date
		echo '<div class="posted-on">';
			metafluidics_footer_posted_on();
		echo '</div>';

		// posted in
		echo '<div class="posted-in">';
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'metafluidics' ) );
		if ( $categories_list && metafluidics_categorized_blog() ) {
			printf( '<div class="cat-links">' . esc_html__( 'Posted in: %1$s', 'metafluidics' ) . '</div>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'metafluidics' ) );
		if ( $tags_list ) {
			printf( '<div class="tags-links">' . esc_html__( 'Tagged as: %1$s', 'metafluidics' ) . '</div>', $tags_list ); // WPCS: XSS OK.
		}
		echo '</div>';
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'metafluidics' ), esc_html__( '1 Comment', 'metafluidics' ), esc_html__( '% Comments', 'metafluidics' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'metafluidics' ), '<div class="edit-link">', '</div>' );
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function metafluidics_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'metafluidics_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'metafluidics_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so metafluidics_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so metafluidics_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in metafluidics_categorized_blog.
 */
function metafluidics_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'metafluidics_categories' );
}
add_action( 'edit_category', 'metafluidics_category_transient_flusher' );
add_action( 'save_post',     'metafluidics_category_transient_flusher' );

/**
 * Remove inline styling from tag clouds generated from wp_tag_cloud()
 */
add_filter('wp_generate_tag_cloud', 'metafluidics_tag_cloud_font',10,3);
function metafluidics_tag_cloud_font( $tagString ){
   return preg_replace("/style='font-size:.+pt;'/", '', $tagString);
}

function wpse_allowedtags() {
	// Add custom tags to this string
   return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>';
}

if ( ! function_exists( 'wpse_custom_wp_trim_excerpt' ) ) :
  function wpse_custom_wp_trim_excerpt($wpse_excerpt) {
  global $post;
  $raw_excerpt = $wpse_excerpt;
    if ( '' == $wpse_excerpt ) {
	      $wpse_excerpt = get_the_content('');
	      $wpse_excerpt = strip_shortcodes( $wpse_excerpt );
	      $wpse_excerpt = apply_filters('the_content', $wpse_excerpt);
	      $wpse_excerpt = str_replace(']]>', ']]&gt;', $wpse_excerpt);
	      $wpse_excerpt = strip_tags($wpse_excerpt, wpse_allowedtags()); /*IF you need to allow just certain tags. Delete if all tags are allowed */

	      //Set the excerpt word count and only break after sentence is complete.
	      $excerpt_word_count = 50;
	      $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count);
	      $tokens = array();
	      $excerptOutput = '';
	      $count = 0;

	      // Divide the string into tokens; HTML tags, or words, followed by any whitespace
	      preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $wpse_excerpt, $tokens);
	      foreach ($tokens[0] as $token) {
	          if ($count >= $excerpt_word_count && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) {
	          // Limit reached, continue until , ; ? . or ! occur at the end
	            $excerptOutput .= trim($token);
	            break;
	          }
	          // Add words to complete sentence
	          $count++;
	          // Append what's left of the token
	          $excerptOutput .= $token;
	      }
	      $wpse_excerpt = trim(force_balance_tags($excerptOutput));

	      return $wpse_excerpt;

      }
      return apply_filters('wpse_custom_wp_trim_excerpt', $wpse_excerpt, $raw_excerpt);
    }

endif;

remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'wpse_custom_wp_trim_excerpt');
