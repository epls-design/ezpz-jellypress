<?php

/**
 * Plugin Name:  EZPZ Environments
 * Plugin URI:   https://github.com/epls-design
 * Description:  Displays a banner on the admin dashboard to indicate which environment you're working in. Additionally, allows to define WP_REMOTE_URL for staging and development environments to load images from a remote server. In order for this plugin to work, you need to set the WP_ENV and WP_REMOTE_URL constants in your wp-config.php file.
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-environments
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('ezpzEnvironments')) {
  class ezpzEnvironments {

    public $environment;
    public $remote_url;

    // Initialize the class
    function __construct() {
      add_action('init', array($this, 'set_environment'));
      add_action('init', array($this, 'set_remote_url'));
      add_filter('acf/settings/show_admin', array($this, 'hide_acf_admin'));
      add_action('admin_notices', array($this, 'display_environment_banner'));

      // If remote URL is set, load images from remote server - there are various hooks here to cover all possible scenarios
      add_filter('wp_get_attachment_url', array($this, 'wp_get_attachment_url_remote'), 10);
      add_filter('wp_get_attachment_image_src', array($this, 'wp_get_attachment_image_src_remote'), 10);
      add_filter('wp_calculate_image_srcset', array($this, 'wp_calculate_image_srcset_remote'), 10);
    }

    // Set the environment - reads from WP_ENV constant in wp-config.php
    function set_environment() {
      if (defined('WP_ENV')) {
        $this->environment = WP_ENV;
      } else {
        $this->environment = 'production';
      }
    }

    // Set the remote URL - reads from WP_REMOTE_URL constant in wp-config.php
    function set_remote_url() {
      if (defined('WP_REMOTE_URL')) {
        $this->remote_url = WP_REMOTE_URL;
      } else {
        $this->remote_url = '';
      }
    }

    // Hide ACF admin menu if not in production
    // @link https://www.awesomeacf.com/snippets/hide-the-acf-admin-menu-item-on-selected-sites/
    function hide_acf_admin() {
      if ($this->environment != 'development') {
        return false; // Hide
      } else {
        return true;
      }
    }

    // Display the environment banner on the admin dashboard
    function display_environment_banner() {
      if ($this->environment != 'production') {
        echo '<div class="notice notice-error"><p style="font-size:1.3em;"><strong>Warning:</strong> You are currently working in the <strong>' . $this->environment . '</strong> environment. Changes you make will not be saved on the live website.</p></div>';
      }
    }

    // Load images from remote server if remote URL is set
    function wp_get_attachment_url_remote($url = '') {
      if (is_admin()) {
        return $url;
      }
      $wp_upload_dir = wp_upload_dir();
      $base_url = $wp_upload_dir['baseurl'];
      $absolute_path = $wp_upload_dir['basedir'] . str_replace($base_url, '', $url);

      if (!file_exists($absolute_path)) {
        $url = str_replace(get_site_url(), $this->remote_url, $url);
      }
      return $url;
    }

    function wp_get_attachment_image_src_remote($image = array()) {

      if (!is_array($image) || empty($image)) {
        return $image;
      }

      $wp_upload_dir = wp_upload_dir();
      $base_url = $wp_upload_dir['baseurl'];
      $absolute_path = $wp_upload_dir['basedir'] . str_replace($base_url, '', $image[0]);

      if (file_exists($absolute_path)) {
        return $image;
      }

      $image[0] = str_replace(get_site_url(), $this->remote_url, $image[0]);

      return $image;
    }

    function wp_calculate_image_srcset_remote($sources = array()) {
      if (!is_array($sources) || is_admin()) {
        return $sources;
      }
      $wp_upload_dir = wp_upload_dir();
      $base_url = $wp_upload_dir['baseurl'];
      foreach ($sources as $key => $val) {
        $absolute_path = $wp_upload_dir['basedir'] . str_replace($base_url, '', $val['url']);
        if (!file_exists($absolute_path)) {
          $val['url']  = str_replace(get_site_url(), $this->remote_url, $val['url']);
          $sources[$key] = $val;
        }
      }
      return $sources;
    }
  }
}

/**
 * Initialize the plugin
 */
new ezpzEnvironments();
