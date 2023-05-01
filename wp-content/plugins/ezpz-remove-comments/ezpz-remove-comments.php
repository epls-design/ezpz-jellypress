<?php

/**
 * Plugin Name:  EZPZ Remove Comments
 * Plugin URI:   null
 * Description:  Drop in plug in which allows you to completely remove comment functionality from a website.
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-removecomments
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('ezpzRemoveComments')) {
  class ezpzRemoveComments {

    // Initialize the class
    function __construct() {
      add_action('admin_init', array($this, 'redirect_admin'), 0);
      add_action('admin_menu', array($this, 'remove_admin_menu'), 0);
      add_action('init', array($this, 'remove_admin_bar_links'));
      add_action('wp_before_admin_bar_render', array($this, 'remove_admin_comments_link'));

      // Close comments on the front-end
      add_filter('comments_open', '__return_false', 20, 2);
      add_filter('pings_open', '__return_false', 20, 2);

      // Hide existing comments
      add_filter('comments_array', '__return_empty_array', 10, 2);
    }

    // Redirect any user trying to access comments page
    function redirect_admin() {
      global $pagenow;

      if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
      }

      // Remove comments metabox from dashboard
      remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

      // Disable support for comments and trackbacks in post types
      foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
          remove_post_type_support($post_type, 'comments');
          remove_post_type_support($post_type, 'trackbacks');
        }
      }
    }

    // Remove Admin Menu
    function remove_admin_menu() {
      remove_menu_page('edit-comments.php');
    }

    // Remove comments links from admin bar
    function remove_admin_bar_links() {
      if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
      }
    }
    function remove_admin_comments_link() {
      global $wp_admin_bar;
      $wp_admin_bar->remove_menu('comments');
    }
  }
}

/**
 * Initialize the plugin
 */
new ezpzRemoveComments();