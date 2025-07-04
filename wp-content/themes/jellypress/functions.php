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

if (!is_admin() && !function_exists('get_field')) {
  echo 'Please install and activate Advanced Custom Fields Pro';
  die();
}

$jellypress_includes = array(
  'inc/helpers.php',                 // Useful helper functions
  'inc/theme-setup.php',             // Basic theme setup
  'inc/enqueue.php',                 // Register and enqueue scripts and styles.
  'inc/editor.php',                  // Customise editor
  'inc/acf.php',                     // Functions which hook into ACF to add additional functionality to the site.
  'inc/blocks.php',                  // Set up the blocks used by this theme
  'inc/widgets.php',                 // Widgets
  'inc/seo.php',                     // SEO filters, hooks and functions for Rank Math SEO Plugin and the theme
  'inc/template-tags.php',           // Custom template tags for this theme.
  'inc/template-functions.php',      // Functions which enhance the theme by hooking into WordPress.
  'inc/loadmore.php',                // Uses Wordpress AJAX to lazyload more posts.
);

foreach ($jellypress_includes as $file) {
  $filepath = get_template_directory() . '/' . $file;
  if (file_exists($filepath)) require_once $filepath;
}

jellypress_register_block_functions();

if (class_exists('woocommerce')) {
  // Only include Woo Support if Woo Installed
  $woo_support = get_template_directory() . '/inc/woocommerce.php';
  if (file_exists($woo_support)) require_once $woo_support;
};
