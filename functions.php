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
  'inc/tgm-plugin-activation.php',   // Third party script to allow required/recommended plugins
  'inc/plugins.php',                 // Require and recommend plugins for this theme
  'inc/helpers.php',                 // Useful helper functions
	'inc/theme-setup.php',             // Basic theme setup
	'inc/widgets.php',                 // Register widget areas
  'inc/enqueue.php',                 // Enqueue scripts and styles.
  'inc/editor.php',                  // Customise editor
  'inc/template-tags.php',           // Custom template tags for this theme.
  'inc/template-functions.php',      // Functions which enhance the theme by hooking into WordPress.
	'inc/pagination.php',              // Custom pagination for this theme.
  'inc/acf.php',                     // Functions which hook into ACF to add additional functionality to the site.
  'inc/dry.php',                     // Don't repeat yourself! Functions which reduce repetition in the theme code.
	'inc/customizer.php',              // Customizer additions.
  'inc/woocommerce.php',             // Load WooCommerce functions.
  'inc/ajax-loadmore/loadmore.php',  // Uses Wordpress AJAX to lazyload more posts.
  'inc/shortcodes.php',              // Custom shortcodes used by the theme

  //~~~~~ CUSTOM POST TYPES
  'post_types/post.php',             // Hooks into post_type 'post' to make the Labels more friendly
  //'post_types/example.php',

);

foreach ( $jellypress_includes as $file ) {
  $filepath = get_template_directory() . '/' . $file;
  if(file_exists($filepath)) require_once $filepath;
}
