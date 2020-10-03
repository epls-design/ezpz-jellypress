<?php
/**
 * Jellypress functions and definitions
 * This file simply pulls in partials from /inc
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Define Dev URL
if (! defined('DEV_URL') ) {
  define('DEV_URL', 'https://jellypress.local');
}

$jellypress_includes = array(
  '/helpers.php',                 // Useful helper functions
	'/theme-setup.php',             // Basic theme setup
	'/widgets.php',                 // Register widget areas
  '/enqueue.php',                 // Enqueue scripts and styles.
  '/editor.php',                  // Customise editor
  '/template-tags.php',           // Custom template tags for this theme.
  '/template-functions.php',      // Functions which enhance the theme by hooking into WordPress.
	'/pagination.php',              // Custom pagination for this theme.
  '/acf.php',                     // Functions which hook into ACF to add additional functionality to the site.
  '/dry.php',                     // Don't repeat yourself! Functions which reduce repetition in the theme code.
	'/customizer.php',              // Customizer additions.
  '/woocommerce.php',             // Load WooCommerce functions.
  '/ajax-loadmore/loadmore.php',  // Uses Wordpress AJAX to lazyload more posts.
  '/shortcodes.php',              // Custom shortcodes used by the theme
);

foreach ( $jellypress_includes as $file ) {
	require_once get_template_directory() . '/inc' . $file;
}
