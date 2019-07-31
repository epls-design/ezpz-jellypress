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
 * Adds a function to display SVGs from the spritesheet.
 */
function jellypress_icon($icon) {
	// Define SVG sprite file.
	$icon_path = get_theme_file_path( '/assets/icons/'.$icon.'.svg' );
  // If it exists, include it.
  if ( file_exists( $icon_path ) ) {
    $use_link = THEME_URI.'/assets/icons/icons.svg#icon-'.$icon;
    echo '<svg class="icon"><use xlink:href="'.$use_link.'" /></use></svg>';
  }
  else {
    return '';
  }
}
