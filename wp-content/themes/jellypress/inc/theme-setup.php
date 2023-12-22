<?php

/**
 * Defines anything that needs to be set up for the theme to function, for example:
 * - Content Width
 * - Theme Support
 * - Image Sizes
 * - Nav Menus
 * - Sidebars
 * - Options Pages
 * - etc
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * Priority 0 to make it available to lower priority callbacks.
 * @global int $content_width
 */
add_action('after_setup_theme', 'jellypress_content_width', 0);
function jellypress_content_width() {
  // This variable is intended to be overruled from themes.
  // @link https://pineco.de/why-we-should-set-the-content_width-variable-in-wordpress-themes/#:~:text=The%20%24content_width%20global%20variable%20was,for%20images%2C%20videos%20and%20embeds.
  $GLOBALS['content_width'] = apply_filters('jellypress_content_width', 1280);
}

add_action('after_setup_theme', 'jellypress_setup');
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function jellypress_setup() {

  // Make theme available for translation.
  load_theme_textdomain('jellypress', get_template_directory() . '/languages');

  // Add default posts and comments RSS feed links to head.
  add_theme_support('automatic-feed-links');

  // Let WordPress manage the document title.
  add_theme_support('title-tag');

  // Enable support for Post Thumbnails on posts and pages.
  add_theme_support('post-thumbnails');

  // Add theme support for selective refresh for widgets.
  add_theme_support('customize-selective-refresh-widgets');

  // Switch default core markup for search form, comment form, and comments to output valid HTML5.
  add_theme_support(
    'html5',
    array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
    )
  );

  // Enable editor styles in Gutenberg
  add_theme_support('editor-styles');
  add_editor_style('dist/editor-style.min.css');

  // Register Nav Menus
  register_nav_menus(
    array(
      'menu-primary' => esc_html__('Primary', 'jellypress'),
    )
  );

  // Register Image Sizes
  add_image_size('icon', 40, 40, true); // Used by Google Maps
  add_image_size('small', 350, 350);
  add_image_size('medium_landscape', 400, 300, true);
}

/**
 * Adds any custom image sizes to the Gutenberg editor image size selector
 */
add_filter('image_size_names_choose', 'jellypress_add_image_sizes_to_editor');
function jellypress_add_image_sizes_to_editor($sizes) {
  $sizes = array_merge($sizes, array(
    'medium_landscape' => __('Medium Landscape', 'jellypress'),
  ));
  return $sizes;
}

/**
 * Adds ACF options pages
 */
if (function_exists('acf_add_options_page')) {
  acf_add_options_page(
    array(
      'page_title'   => __('Theme Options', 'jellypress'),
      'menu_title'  => __('Theme Options', 'jellypress'),
      'menu_slug'   => 'theme-options',
      'capability'  => 'edit_posts',
      //'redirect'    => true,
      'icon_url' => 'dashicons-info',
      'position' => 90,
      'autoload' => true, // Speeds up load times
      'updated_message' => __("Successfully updated Theme options", 'jellypress'),
    )
  );
}

/**
 * Register widget areas
 */
add_action('widgets_init', 'jellypress_widgets_init');
function jellypress_widgets_init() {
  register_sidebar(array(
    'name'          => esc_html__('Sidebar', 'jellypress'),
    'id'            => 'default-sidebar',
    'description'   => esc_html__('Add your widgets here.', 'jellypress'),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h4 class="widget-title">',
    'after_title'   => '</h4>',
  ));
}
