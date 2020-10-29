<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Remove support for WP Editor if you are using ACF exclusively for content
 */
if (! function_exists('jellypress_post_type_supports_page') ) :
  function jellypress_post_type_supports_page() {
    remove_post_type_support( 'page', 'editor' );
  }
  add_action('init', 'jellypress_post_type_supports_page');
endif;
