<?php

/**
 * Functions necessary for the accordion block
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function () {
  wp_register_script(
    'aria-accordion',
    get_template_directory_uri() . '/template-parts/blocks/accordion/aria.accordion.min.js',
    array(),
    filemtime(get_template_directory() . '/template-parts/blocks/accordion/aria.accordion.min.js'),
    true
  );
});
