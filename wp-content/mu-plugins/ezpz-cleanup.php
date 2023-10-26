<?php

/**
 * Plugin Name:  EZPZ Clean Up
 * Plugin URI:   https://github.com/epls-design
 * Description:  Applies some security fixes and cleans up the installation. Additionally, automatically adds Alt tags to images uploaded based on their file name.
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-cleanup
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('ezpzCleanUp')) {
  class ezpzCleanUp {

    // Initialize the class
    function __construct() {
      add_action('init', array($this, 'disallow_file_edit'));
      add_action('init', array($this, 'limit_post_revisions'));
      add_action('init', array($this, 'disable_trackbacks_and_smilies'));
      add_action('after_setup_theme', array($this, 'after_theme_setup'));
      add_action('add_attachment', array($this, 'auto_alt_tags'));
    }

    // Disallow file edit
    function disallow_file_edit() {
      if (!defined('DISALLOW_FILE_EDIT'))
        define('DISALLOW_FILE_EDIT', true);
    }

    // Restrict post revisions
    function limit_post_revisions() {
      if (!defined('WP_POST_REVISIONS'))
        define('WP_POST_REVISIONS', 10);
    }

    // Disable trackbacks and smilies
    function disable_trackbacks_and_smilies() {
      // Force admin options to off
      $options = array(
        'default_ping_status'   => 'closed',
        'default_pingback_flag'  => 0,
        'use_smilies'           => 0
      );
      foreach ($options as $key => $value) {
        $current = get_option($key);
        if ($current != $value) {
          update_option($key, $value);
        }
      }
    }

    function after_theme_setup() {

      /* Remove Emoji Junk from wp_head */
      remove_action('wp_head', 'print_emoji_detection_script', 7);
      remove_action('wp_print_styles', 'print_emoji_styles');
      remove_action('admin_print_scripts', 'print_emoji_detection_script');
      remove_action('admin_print_styles', 'print_emoji_styles');

      /* Remove Wordpress version from wp_head */
      remove_action('wp_head', 'wp_generator');

      /* Remove XML-RPC Really Simple Discovery from wp_head */
      remove_action('wp_head', 'rsd_link');

      /* Remove Windows Live Writer link from wp_head */
      remove_action('wp_head', 'wlwmanifest_link');

      /* Remove Useless Post Relational links from wp_head */
      remove_action('wp_head', 'index_rel_link'); // remove link to index page
      remove_action('wp_head', 'start_post_rel_link', 10, 0); // remove random post link
      remove_action('wp_head', 'parent_post_rel_link', 10, 0); // remove parent post link
      remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // remove the next and previous post links
      remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
      remove_action('wp_head', 'wp_shortlink_wp_head');

      /* Remove Wordpress Welcome Panel */
      remove_action('welcome_panel', 'wp_welcome_panel');

      /* Remove feed links from wp_head */
      remove_action('wp_head', 'feed_links', 2);
      remove_action('wp_head', 'feed_links_extra', 3);

      /* Remove unwanted WP Dashboard Widgets */
      add_action('wp_dashboard_setup', function () {
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
        remove_meta_box('network_dashboard_right_now', 'dashboard', 'normal'); // Network Right Now
        remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Draft / Your Recent Drafts
        remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress Events and News
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal'); // Site Health Status
      });

      /* Remove the REST API lines from the HTML Header */
      remove_action('wp_head', 'rest_output_link_wp_head', 10);
      remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);


      /* Remove Annoying Plugin-specific widgets from WP Dashboard */
      add_action('do_meta_boxes', function () {
        remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal'); // yoast seo overview
        remove_meta_box('rank_math_dashboard_widget', 'dashboard', 'normal'); // rank math seo overview
        remove_meta_box('aw_dashboard', 'dashboard', 'normal'); // wp socializer box
        remove_meta_box('w3tc_latest', 'dashboard', 'normal'); // w3 total cache news box
        remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal'); // gravity forms
        remove_meta_box('bbp-dashboard-right-now', 'dashboard', 'normal'); // bbpress right now in forums
        remove_meta_box('jetpack_summary_widget', 'dashboard', 'normal'); // jetpack
        remove_meta_box('tribe_dashboard_widget', 'dashboard', 'normal'); // modern tribe rss widget
      });

      /* Remove Update Nag for non Administrators */
      add_action('admin_head', function () {
        if (!current_user_can('administrator')) {
          remove_action('admin_notices', 'update_nag', 3);
        }
      });

      /* Disables Self-Trackbacks */
      add_action('pre_ping', function ($links) {
        foreach ($links as $l => $link) {
          if (0 === strpos($link, get_option('home'))) {
            unset($links[$l]);
          }
        }
      });

      /* Removes Helly Dolly plugin if it exists */
      add_action('admin_init', function () {
        if (file_exists(WP_PLUGIN_DIR . '/hello.php')) {
          delete_plugins(array('hello.php'));
        }
      });

      /* Modifies #more link to not use hashtag anchor */
      add_filter('the_content_more_link', array($this, 'more_jump_link_anchor'));

      /* Fixes curly quotes and badly formatted characters */
      // add_filter('content_save_pre',      array($this, 'curly_other_chars'));
      // add_filter('title_save_pre',        array($this, 'curly_other_chars'));
    }

    // Automatically adds Alt tags to images uploaded, based on the image title or filename.
    function auto_alt_tags($post_ID) {

      // Check if uploaded file is an image, else do nothing

      if (wp_attachment_is_image($post_ID)) {

        $img_title = get_post($post_ID)->post_title;

        // Sanitize the title:  remove hyphens, underscores & extra spaces:
        $img_title = preg_replace('%\s*[-_\s]+\s*%', ' ',  $img_title);

        // Sanitize the title:  capitalize first letter of every word (other letters lower case):
        $img_title = ucwords(strtolower($img_title));

        // Create an array with the image meta (Title, Caption, Description) to be updated
        // Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
        $img_meta = array(
          'ID'    => $post_ID,      // Specify the image (ID) to be updated
          'post_title'  => $img_title,    // Set image Title to sanitized title
          //'post_excerpt'	=> $img_title,		// Set image Caption (Excerpt) to sanitized title
          //'post_content'	=> $img_title,		// Set image Description (Content) to sanitized title
        );

        // Set the image Alt-Text
        update_post_meta($post_ID, '_wp_attachment_image_alt', $img_title);

        // Set the image meta (e.g. Title, Excerpt, Content)
        wp_update_post($img_meta);
      }
    }


    /**
     * Modifies #more link to not use hashtag anchor
     */
    public function more_jump_link_anchor($link) {

      $offset = strpos($link, '#more-');

      if ($offset) {
        $end = strpos($link, '"', $offset);
      }

      if ($end) {
        $link = substr_replace($link, '', $offset, $end - $offset);
      }

      return $link;
    }

    /**
     * Fixes curly quotes and badly formatted characters when pasting from Word
     */
    function curly_other_chars($fixChars) {

      $fixChars = str_replace(
        array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
        array("'", "'", '"', '"', '-', '&mdash;', '&hellip;'),
        $fixChars
      );

      $fixChars = str_replace(
        array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
        array("'", "'", '"', '"', '-', '&mdash;', '&hellip;'),
        $fixChars
      );

      $fixChars = str_replace(
        array('â„¢', 'Â©', 'Â®'),
        array('&trade;', '&copy;', '&reg;'),
        $fixChars
      );

      return $fixChars;
    }
  }
}

/**
 * Initialize the plugin
 */
new ezpzCleanUp();