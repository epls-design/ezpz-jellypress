<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package jellypress
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function jellypress_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'jellypress_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function jellypress_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'jellypress_pingback_header' );

/**
 * Move Yoast Meta Box to bottom of editor
 */
function jellypress_yoastprioritylow() {
  return 'low';
  }
  add_filter( 'wpseo_metabox_prio', 'jellypress_yoastprioritylow');

/**
 * Wordpress 5.3+ adds additional image sizes. All of these get generated every time an image is
 * uploaded. This function removes some of those images, to reduce server load and storage use.
 * Note: you may need to regenerate thumbnails to clear any using old sizes.
 */

function jellypress_unset_image_sizes($sizes) {
	//unset($sizes['thumbnail']);    // disable thumbnail size
	//unset($sizes['medium']);       // disable medium size
	//unset($sizes['large']);        // disable large size
	unset($sizes['medium_large']); // disable medium-large size
	unset($sizes['1536x1536']);    // disable 2x medium-large size
	unset($sizes['2048x2048']);    // disable 2x large size
  return $sizes;
}
add_action('intermediate_image_sizes_advanced', 'jellypress_unset_image_sizes');

/**
 * Prevents Wordpress from automatically adding classes to pasted text, on <span> and <p> tagds (eg. class="p1")
 */
function jellypress_prevent_autotags($in) {
  $in['paste_preprocess'] = "function(pl,o){ o.content = o.content.replace(/p class=\"p[0-9]+\"/g,'p'); o.content = o.content.replace(/span class=\"s[0-9]+\"/g,'span'); }";
  return $in;
}
add_filter('tiny_mce_before_init', 'jellypress_prevent_autotags');
