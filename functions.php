<?php

/**
 * Theme functions and definitions
 * This file simply pulls in partials from /inc
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (file_exists(get_template_directory() . '/env.json')) {
  $vars = file_get_contents(get_template_directory() . '/env.json');
  $vars = json_decode($vars, true);
  foreach ($vars as $key => $value) putenv("$key=$value");
}

if (!function_exists('jellypress_env')) {
  function jellypress_env($key, $default = null)
  {
    $value = getenv($key);
    if ($value === false) return $default;
    return $value;
  }
}

if (!defined('DEV_URL')) define('DEV_URL', jellypress_env('DEV_URL'));
if (!defined('STAGING_URL')) define('STAGING_URL', jellypress_env('STAGING_URL'));
if (!defined('PROD_URL')) define('PROD_URL', jellypress_env('PROD_URL'));

$jellypress_includes = array(
  'inc/tgm-plugin-activation.php',   // Third party script to allow required/recommended plugins
  'inc/plugins.php',                 // Require and recommend plugins for this theme
  'inc/helpers.php',                 // Useful helper functions
  'inc/theme-setup.php',             // Basic theme setup
  'inc/widgets.php',                 // Register widget areas
  'inc/enqueue.php',                 // Register and enqueue scripts and styles.
  'inc/editor.php',                  // Customise editor
  'inc/remove-comments.php',         // Include this to completely remove support for comments
  'inc/template-tags.php',           // Custom template tags for this theme.
  'inc/template-functions.php',      // Functions which enhance the theme by hooking into WordPress.
  'inc/pagination.php',              // Custom pagination for this theme.
  'inc/acf.php',                     // Functions which hook into ACF to add additional functionality to the site.
  'inc/dry.php',                     // Don't repeat yourself! Functions which reduce repetition in the theme code.
  'inc/video-embed.php',             // Functions to help with embedding videos.
  'inc/customizer.php',              // Customizer additions.
  'inc/ajax-loadmore/loadmore.php',  // Uses Wordpress AJAX to lazyload more posts.
  'inc/remote-images.php',           // Uses images from a remote production URL if working in the local dev environment.
  'inc/schema.php',                  // Hook into WP_Footer to print Structured Schema markup
  'inc/modals.php',                  // Initialize and manipulate modals
  'inc/slider.php',                  // Integrate sliders using SplideJS
  'inc/countdown.php',               // Initialize countdowns
  'inc/charts.php',                  // Functions which work with charts.js library
  'inc/compare.php',                 // Functions which work with TwentyTwenty Image Comparison

  //~~~~~ CUSTOM POST TYPES
  'inc/post_types/post.php',
  'inc/post_types/page.php',
  'inc/post_types/stacks.php',

  //~~~~~ USER CAPABILITIES
  //'inc/user_caps/client-admin.php',  // Example for how to restrict user capabilities for a specific role
);

foreach ($jellypress_includes as $file) {
  $filepath = get_template_directory() . '/' . $file;
  if (file_exists($filepath)) require_once $filepath;
}

if (class_exists('woocommerce')) {
  // Only include Woo Support if Woo Installed
  $woo_support = get_template_directory() . '/inc/woocommerce.php';
  if (file_exists($woo_support)) require_once $woo_support;
};
