<?php
/**
 * Enqueue scripts and styles to theme
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'FAVICON_DIR' ) ) {
	define( 'FAVICON_DIR', get_template_directory_uri() . '/dist/favicon' );
}

if ( ! function_exists( 'jellypress_scripts' ) ) {
  /**
   * Enqueue scripts and styles.
   */
  function jellypress_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' ); // Get current version of theme
    $css_version = $theme_version . '.' . filemtime( get_template_directory() . '/style.css' ); // Appends time stamp to help with cache busting
    $js_version = $theme_version . '.' . filemtime( get_template_directory() . '/dist/js/site.min.js' ); // Appends time stamp to help with cache busting

    // Enqueue Stylesheets
    wp_enqueue_style('jellypress-styles', get_stylesheet_uri(), array(), $css_version);
    wp_style_add_data( 'jellypress-styles', 'rtl', 'replace' );

    // Enqueue Scripts
    wp_enqueue_script('jellypress-scripts', get_template_directory() . '/dist/js/site.min.js', array('jquery'), $js_version, true);

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
    }
  }
}
add_action( 'wp_enqueue_scripts', 'jellypress_scripts' );

if ( ! function_exists( 'jellypress_favicon_script' ) ) {
  /**
   * Hook Favicons into wp_head() using a range of sizes and specifications
   * TODO: Visit https://realfavicongenerator.net/ to generate the pack, replace the below with the latest standards as these often change
   */
  function jellypress_favicon_script() {
    echo '
    <link rel="apple-touch-icon" sizes="180x180" href="'.FAVICON_DIR.'/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="'.FAVICON_DIR.'/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="'.FAVICON_DIR.'/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="'.FAVICON_DIR.'/manifest.json">
    <link rel="mask-icon" href="'.FAVICON_DIR.'/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="'.FAVICON_DIR.'/favicon.ico">
    <meta name="msapplication-config" content="'.FAVICON_DIR.'/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    ';
  }
}
add_action('wp_head', 'jellypress_favicon_script');

if ( ! function_exists( 'jellypress_add_favicon_to_admin' ) ) {
  // Add favicon to admin areas
  function jellypress_add_favicon_to_admin() {
      $favicon_url = FAVICON_DIR.'/favicon.ico';
      echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
  }
}
add_action('login_head', 'jellypress_add_favicon_to_admin');
add_action('admin_head', 'jellypress_add_favicon_to_admin');
