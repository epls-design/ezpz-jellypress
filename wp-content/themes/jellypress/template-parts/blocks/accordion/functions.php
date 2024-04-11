<?php

/**
 * Functions necessary for the accordion block
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'jellypress_register_accordion');
add_action('admin_enqueue_scripts', 'jellypress_register_accordion');

function jellypress_register_accordion() {
  wp_register_script(
    'aria-accordion',
    get_template_directory_uri() . '/template-parts/blocks/accordion/aria.accordion.min.js',
    array(),
    filemtime(get_template_directory() . '/template-parts/blocks/accordion/aria.accordion.min.js'),
    true
  );
  wp_register_script(
    'aria-accordion-init',
    get_template_directory_uri() . '/template-parts/blocks/accordion/accordion-init.js',
    array('aria-accordion'),
    filemtime(get_template_directory() . '/template-parts/blocks/accordion/accordion-init.js'),
    true
  );
}