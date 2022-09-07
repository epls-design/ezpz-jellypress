<?php

/**
 * Enqueue scripts and styles to theme
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (!function_exists('jellypress_scripts')) {
  /**
   * Enqueue scripts and styles.
   */
  function jellypress_scripts() {
    $theme_version = wp_get_theme()->get('Version'); // Get current version of theme
    $css_version = $theme_version . ':' . filemtime(get_template_directory() . '/style.css'); // Appends time stamp to help with cache busting
    $js_version = $theme_version . ':' . filemtime(get_template_directory() . '/dist/js/site.min.js'); // Appends time stamp to help with cache busting

    // NOTE: Not using Gutenberg in this theme? Then remove these comments
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wc-block-style'); // WooCommerce - you can remove this if you don't use Woocommerce

    // Enqueue Stylesheets
    wp_enqueue_style('jellypress-styles', get_stylesheet_uri(), array(), $css_version);
    wp_style_add_data('jellypress-styles', 'rtl', 'replace');

    /**
     * SVG4everybody gives SVG spritesheets background compatibility with polyfills
     * Push to footer and initialise on doc ready
     */
    wp_enqueue_script('svg4everybody', 'https://cdnjs.cloudflare.com/ajax/libs/svg4everybody/2.1.9/svg4everybody.min.js', '', '', true);
    wp_add_inline_script('svg4everybody', 'jQuery(document).ready(function($){svg4everybody();});'); // Init

    /**
     * Register Scripts but don't enqueue them until they are required.
     */

    wp_register_script(
      'jellypress-scripts',
      get_template_directory_uri() . '/dist/js/site.min.js',
      array('jquery'),
      $js_version,
      true
    );

    //  wp_register_script(
    //    'example-slider-plugin',
    //    get_template_directory_uri() . '/lib/example-slider-plugin.js',
    //    array(),
    //    $js_version,
    //    true
    //  );

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
      get_template_directory_uri() . '/lib/aria.accordion.min.js',
      array(),
      $js_version,
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
      'twentytwenty',
      get_template_directory_uri() . '/lib/twentytwenty.min.js',
      array('jquery'),
      $js_version,
      true
    );

    wp_register_script(
      'charts',
      get_template_directory_uri() . '/lib/charts.min.js',
      array(),
      $js_version,
      true
    );

    wp_register_script(
      'charts-opts',
      get_template_directory_uri() . '/lib/charts-opts.js',
      array('charts'),
      $js_version,
      true
    );

    wp_register_style(
      'font-awesome',
      'https://use.fontawesome.com/releases/v5.5.0/css/all.css',
      array(),
      '5.5.0'
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
}
add_action('wp_enqueue_scripts', 'jellypress_scripts');

/**
 * Modifies enqueued scripts to defer loading.
 * You can add plugin-provided scripts here to improve page load times.
 */
add_filter('script_loader_tag', 'jellypress_scripts_add_atts', 10, 3);
function jellypress_scripts_add_atts($tag, $handle, $src) {
  // Anything to defer goes in this array...
  $defer_scripts = ['svg4everybody', 'search-filter-plugin-chosen', 'search-filter-plugin-build', 'jquery-ui-sortable', 'jquery-ui-resizable', 'wc-cart-fragments'];

  // TODO: Make it so ACF is deferred on front end only (not admin) - or do these get wrapped in a not logged in function?
  if (in_array($handle, $defer_scripts)) {
    if (!is_admin()) {
      // Anything to defer goes in this array...
      $defer_scripts = ['svg4everybody', 'search-filter-plugin-chosen', 'search-filter-plugin-build', 'jquery-ui-sortable', 'jquery-ui-resizable', 'wc-cart-fragments'];

      if (in_array($handle, $defer_scripts)) {
        if (false === stripos($tag, 'defer')) {
          $tag = str_replace(' src', ' defer="defer" src', $tag);
        }
      }
    }
  }
  return $tag;
}

if (!function_exists('jellypress_add_favicon')) {
  // Add favicon to admin areas
  function jellypress_add_favicon() {
    $theme_options = jellypress_get_acf_fields('60c219d0bd368', 'option');
    if ($favicon = wp_get_attachment_image_src($theme_options['favicon'], 'icon'))
      echo '<link rel="shortcut icon" type="image/png" href="' . $favicon[0] . '" />';
  }
}
add_action('login_head', 'jellypress_add_favicon', 10, 0);
add_action('admin_head', 'jellypress_add_favicon', 10, 0);
add_action('wp_head', 'jellypress_add_favicon', 10, 0);
