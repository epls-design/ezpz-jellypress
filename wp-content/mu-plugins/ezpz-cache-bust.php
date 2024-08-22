<?php

/**
 * Plugin Name:  EZPZ Cache Bust
 * Plugin URI:   https://github.com/epls-design
 * Description:  Clears cache whenever a post is updated - this is necessary for certain blocks that have WP queries in them (eg. latest posts)
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-cachebust
 */


// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

class ezpzCacheBust() {

  function __construct() {
    add_action('save_post', array($this, 'clear_cache'), 10, 1);
  }

  private function clear_cache($post_id) {
    $post_type = get_post_type($post_id);
    if($post_type == 'post') {
      do_action('breeze_clear_all_cache');
      do_action('breeze_clear_varnish');
    }
  }
}

new ezpzCacheBust();