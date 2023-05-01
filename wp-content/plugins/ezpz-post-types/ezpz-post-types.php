<?php

/**
 * Plugin Name:  EZPZ Post Types
 * Plugin URI:   null
 * Description:  Drop in plug in which allows for the creation of custom post types and taxonomies, as well as admin columns and filters.
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-posts
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

require_once plugin_dir_path(__FILE__) . 'inc/class-cpt.php';

if (!class_exists('ezpzPostTypes')) {
  class ezpzPostTypes {

    // Initialize the class
    function __construct() {
      add_action('init', array($this, 'register_post_types'), 0);
    }

    function register_post_types() {
      // Include your custom post type files here...
      require_once plugin_dir_path(__FILE__) . 'inc/page.php';
      require_once plugin_dir_path(__FILE__) . 'inc/post.php';
      require_once plugin_dir_path(__FILE__) . 'inc/stacks.php';
    }
  }
}

/**
 * Initialize the plugin
 */
new ezpzPostTypes();
