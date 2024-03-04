<?php

/**
 * Functions necessary for the number counter block
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', function () {

  wp_register_script(
    'youtube-api',
    '//www.youtube.com/iframe_api',
    array('video-embed'),
    date('YW'),
    true
  );

  wp_register_script(
    'vimeo-api',
    '//player.vimeo.com/api/player.js',
    array('video-embed'),
    date('YW'),
    true
  );

  wp_register_script(
    'video-embed',
    get_template_directory_uri() . '/template-parts/blocks/video/video-player.js',
    array('jquery'),
    filemtime(get_template_directory() . '/template-parts/blocks/video/video-player.js'),
    true
  );
});
