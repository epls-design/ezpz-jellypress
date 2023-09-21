<?php

/**
 * Enqueue scripts and styles to theme
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Enqueue scripts and styles.
 */
add_action('wp_enqueue_scripts', 'jellypress_scripts');
function jellypress_scripts() {
  $theme_version = wp_get_theme()->get('Version'); // Get current version of theme
  $css_version = $theme_version . ':' . filemtime(get_template_directory() . '/style.css'); // Appends time stamp to help with cache busting
  $js_version = $theme_version . ':' . filemtime(get_template_directory() . '/dist/site.min.js'); // Appends time stamp to help with cache busting

  // Dequeue Gutenberg block library CSS because we only use custom blocks in this theme
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wc-block-style');

  // Enqueue Stylesheets
  wp_enqueue_style('jellypress-styles', get_template_directory_uri() . '/style.css', array(), $css_version);
  wp_style_add_data('jellypress-styles', 'rtl', 'replace');

  /**
   * SVG4everybody gives SVG spritesheets background compatibility with polyfills
   * Push to footer and initialise on doc ready
   */
  wp_enqueue_script('svg4everybody', 'https://cdnjs.cloudflare.com/ajax/libs/svg4everybody/2.1.9/svg4everybody.min.js', array('jquery'), '', true);
  wp_add_inline_script('svg4everybody', 'jQuery(document).ready(function($){svg4everybody();});'); // Init

  /**
   * Register Scripts but don't enqueue them until they are required.
   */

  wp_register_script(
    'jellypress-scripts',
    get_template_directory_uri() . '/dist/site.min.js',
    array('jquery'),
    $js_version,
    true
  );

  wp_register_script(
    'youtube-api',
    '//www.youtube.com/iframe_api',
    array(),
    date('YW'),
    true
  );

  wp_register_script(
    'vimeo-api',
    '//player.vimeo.com/api/player.js',
    array(),
    date('YW'),
    true
  );

  wp_register_script(
    'aria-accordion',
    get_template_directory_uri() . '/template-parts/blocks/accordion/aria.accordion.min.js',
    array(),
    filemtime(get_template_directory() . '/template-parts/blocks/accordion/aria.accordion.min.js'),
    true
  );

  wp_register_script(
    'countdown-init',
    get_template_directory_uri() . '/template-parts/blocks/countdown/countdown-init.js',
    array('jquery'),
    filemtime(get_template_directory() . '/template-parts/blocks/countdown/countdown-init.js'),
    true
  );

  wp_register_script(
    'number-counter',
    get_template_directory_uri() . '/template-parts/blocks/number-counter/counter.js',
    array('jquery'),
    filemtime(get_template_directory() . '/template-parts/blocks/number-counter/counter.js'),
    true
  );

  wp_register_script(
    'magnific-popup',
    get_template_directory_uri() . '/lib/magnific-popup.min.js',
    array('jquery'),
    $js_version,
    true
  );

  wp_register_script(
    'splide-slider',
    get_template_directory_uri() . '/lib/splide.min.js',
    array(),
    $js_version,
    true
  );

  wp_register_script(
    'photoswipe-init',
    get_template_directory_uri() . '/lib/photoswipe-init.js',
    array('jquery'),
    filemtime(get_template_directory() . '/lib/photoswipe-init.js'),
    true
  );

  $get_gmaps_api = get_global_option('google_maps_api_key');
  if ($get_gmaps_api) {
    wp_register_script(
      'googlemaps',
      'https://maps.googleapis.com/maps/api/js?key=' . $get_gmaps_api . '"',
      null,
      null,
      null
    );
  }

  /**
   * Enqueue Required scripts
   */
  wp_enqueue_script('jellypress-scripts');
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  /**
   * Move jQuery to footer to reduce render-blocking
   */
  wp_scripts()->add_data('jquery', 'group', 1);
  wp_scripts()->add_data('jquery-core', 'group', 1);
  wp_scripts()->add_data('jquery-migrate', 'group', 1);
}

/**
 * Modifies enqueued scripts to defer loading.
 * You can add plugin-provided scripts here to improve page load times.
 */
add_filter('script_loader_tag', 'jellypress_scripts_add_atts', 10, 3);
function jellypress_scripts_add_atts($tag, $handle, $src) {
  if (!is_admin()) {
    // Anything to defer goes in this array...
    $defer_scripts = ['svg4everybody', 'search-filter-plugin-chosen', 'search-filter-plugin-build', 'jquery-ui-sortable', 'jquery-ui-resizable', 'wc-cart-fragments'];

    if (in_array($handle, $defer_scripts)) {
      if (false === stripos($tag, 'defer')) {
        $tag = str_replace(' src', ' defer="defer" src', $tag);
      }
    }
  }

  // Set photoswipe to enqueue as a module
  if ('photoswipe-init' == $handle) {
    $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
  }
  return $tag;
}

/**
 * Hook Favicons into wp_head() using a range of sizes and specifications
 * TODO: Visit https://realfavicongenerator.net/ to generate the pack, replace the below with the latest standards as these often change
 */
add_action('wp_head', 'jellypress_favicon_script');
function jellypress_favicon_script() {
  echo '
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/favicon/favicon.ico">
    <meta name="msapplication-config" content="browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    ';
}

// Add favicon to admin areas
add_action('login_head', 'jellypress_add_favicon_to_admin');
add_action('admin_head', 'jellypress_add_favicon_to_admin');
function jellypress_add_favicon_to_admin() {
  echo '<link rel="shortcut icon" href="/favicon.ico">';
}
