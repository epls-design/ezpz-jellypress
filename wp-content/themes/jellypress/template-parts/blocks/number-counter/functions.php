<?php

/**
 * Functions necessary for the number counter block
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function () {
  wp_register_script(
    'number-counter',
    get_template_directory_uri() . '/template-parts/blocks/number-counter/counter.js',
    array('jquery'),
    filemtime(get_template_directory() . '/template-parts/blocks/number-counter/counter.js'),
    true
  );
});