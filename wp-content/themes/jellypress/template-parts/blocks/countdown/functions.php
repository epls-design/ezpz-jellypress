<?php

/**
 * Functions necessary for the countdown block
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'jellypress_register_countdown');
add_action('admin_enqueue_scripts', 'jellypress_register_countdown');

function jellypress_register_countdown() {
  wp_register_script(
    'countdown-init',
    get_template_directory_uri() . '/template-parts/blocks/countdown/countdown-init.js',
    array('jquery'),
    filemtime(get_template_directory() . '/template-parts/blocks/countdown/countdown-init.js'),
    true
  );
};