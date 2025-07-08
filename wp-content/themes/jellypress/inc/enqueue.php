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
add_action('wp_enqueue_scripts', 'jellypress_block_scripts');
add_action('admin_enqueue_scripts', 'jellypress_block_scripts');

/**
 * Hooks scripts and styles into the front end only (not the admin)
 *
 * @return void
 */
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
 * Hooks assets that can run on the front end or the block editor
 */
function jellypress_block_scripts() {

  wp_register_script(
    'swiper',
    get_template_directory_uri() . '/lib/swiper-bundle.min.js',
    array(),
    filemtime(get_template_directory() . '/lib/swiper-bundle.min.js'),
    true
  );

  wp_register_script(
    'swiper-init',
    get_template_directory_uri() . '/lib/swiper-init.js',
    array('swiper'),
    filemtime(get_template_directory() . '/lib/swiper-init.js'),
    true
  );

  wp_register_script(
    'photoswipe-init',
    get_template_directory_uri() . '/lib/photoswipe-init.js',
    array('jquery'),
    filemtime(get_template_directory() . '/lib/photoswipe-init.js'),
    true
  );
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
 * TODO: Visit https://realfavicongenerator.net/ to generate the pack, replace the below files and note the colours also
 */
add_action('wp_head', 'jellypress_favicon_script');
function jellypress_favicon_script() {
  if (file_exists(ABSPATH . '/favicon.ico'))                   echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">';
  if (file_exists(ABSPATH . '/favicon/favicon-194x194.png'))   echo '<link rel="icon" type="image/png" href="/favicon/favicon-194x194.png" sizes="194x194">';
  if (file_exists(ABSPATH . '/favicon/favicon-96x96.png'))     echo '<link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96">';
  if (file_exists(ABSPATH . '/favicon/favicon-32x32.png'))     echo '<link rel="icon" type="image/png" href="/favicon/favicon-32x32.png" sizes="32x32">';
  if (file_exists(ABSPATH . '/favicon/favicon-16x16.png'))     echo '<link rel="icon" type="image/png" href="/favicon/favicon-16x16.png" sizes="16x16">';
  if (file_exists(ABSPATH . '/favicon/apple-touch-icon.png'))  echo '<link rel="apple-touch-icon" href="/favicon/apple-touch-icon.png">';
  if (file_exists(ABSPATH . '/favicon/safari-pinned-tab.svg')) echo '<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">'; // Note: change the color to match your branding
  if (file_exists(ABSPATH . '/site.webmanifest'))              echo '<link rel="manifest" href="/site.webmanifest">';
  if (file_exists(ABSPATH . '/browserconfig.xml'))             echo '<meta name="msapplication-config" content="/browserconfig.xml">';
  echo '<meta name="theme-color" content="#ffffff">';
}

// Add favicon to admin areas
add_action('login_head', 'jellypress_add_favicon_to_admin');
add_action('admin_head', 'jellypress_add_favicon_to_admin');
function jellypress_add_favicon_to_admin() {
  if (file_exists(ABSPATH . '/favicon.ico')) echo '<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">';
}

/**
 * Register and enqueue custom JS for core blocks
 *
 * @return void
 */
add_action('init', 'jellypress_register_block_assets');
function jellypress_register_block_assets() {
  // Register the script
  wp_register_script(
    'prismjs',
    get_template_directory_uri() . '/lib/prism.js',
    array('jquery'),
    filemtime(get_template_directory() . '/lib/prism.js'),
    true
  );
}
add_filter('render_block', 'jellypress_enqueue_on_block_render', 10, 2);

function jellypress_enqueue_on_block_render($block_content, $block) {
  // Check if it's the block you want to target
  if ($block['blockName'] === 'core/code') {
    wp_enqueue_script('prismjs');
  }

  return $block_content;
}
