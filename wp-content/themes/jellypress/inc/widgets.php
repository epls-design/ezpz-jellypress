<?php

/**
 * Widgets for the theme. Using Gutenberg block editor but with restricted access to blocks
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Register widget areas
 */
add_action('widgets_init', 'jellypress_widgets_init');
function jellypress_widgets_init() {
  register_sidebar(array(
    'name'          => esc_html__('Sidebar', 'jellypress'),
    'id'            => 'default-sidebar',
    'description'   => esc_html__('Add your widgets here.', 'jellypress'),
    'before_widget' => '',
    'after_widget'  => '',
  ));
}

/**
 * Helper function to set up the attributes for a custom Gutenberg widget block
 *
 * @param array $block Gutenberg block
 * @param array $is_preview Whether this is in the Gutenberg editor or not
 * @return array $attributes Array of attributes for the block
 */
function jellypress_get_widget_attributes($block, $is_preview = false) {
  $block_classes = 'widget widget-' . sanitize_title($block['title']);

  if (!$is_preview) {
    // Comment in if you want to use widgets in a row, eg in the footer
    $block_classes .= ' col';
  }

  // Merge in block classes from Gutenberg Editor
  if (isset($block['className'])) $block_classes .= ' ' . $block['className'];

  // Remove 'is-style- ' from the block classes
  $block_classes = str_replace('is-style-', '', $block_classes);

  $attributes = array(
    'anchor' => isset($block['anchor']) ? "id='" . esc_attr($block['anchor']) . "'" : '',
    'class' => $block_classes,
    'block_id' => $block['id'],
  );

  return $attributes;
}


/**
 * Specify which blocks are exposed to the widget editor or customizer
 */
add_filter('allowed_block_types_all', 'jellypress_allowed_widgets', 40, 2);
function jellypress_allowed_widgets($block_editor_context, $editor_context) {
  // Check if this is the widgets editor
  if ((!empty($editor_context->name) && $editor_context->name === 'core/edit-widgets') ||
    (function_exists('is_customize_preview') && is_customize_preview())
  ) {

    // Define which blocks to allow in widgets
    // These may be filtered out in the editor-block-filters.js
    $allowed_widgets = array(
      'core/paragraph',
      'core/image',
      'core/heading',
      'core/list',
    );

    $theme_widgets = jellypress_get_widgets();
    if (!empty($theme_widgets)) {
      foreach ($theme_widgets as $widget) {
        $allowed_widgets[] = 'ezpz/' . $widget;
      }
    }

    return $allowed_widgets;
  }

  return $block_editor_context;
}

/**
 * Register Widgets with Gutenberg
 */
function jellypress_register_widgets() {
  $widgets = jellypress_get_widgets();
  foreach ($widgets as $widget) {
    if (file_exists(get_template_directory() . '/template-parts/widgets/' . $widget . '/block.json')) {
      $reg = register_block_type(get_template_directory() . '/template-parts/widgets/' . $widget . '/block.json');
    }
  }
}
add_action('init', 'jellypress_register_widgets', 5);


/**
 * Get all widget names from template-parts/blocks
 */
function jellypress_get_widgets() {
  $theme   = wp_get_theme();
  $widgets  = get_option('jellypress_widgets');
  // $version = get_option('jellypress_widgets_version');
  $widgets = scandir(get_template_directory() . '/template-parts/widgets/');

  // Remove unnecessary directories and files
  $widgets = array_values(array_diff($widgets, array('..', '.', '.DS_Store')));
  update_option('jellypress_widgets', $widgets);
  update_option('jellypress_widgets_version', $theme->get('Version'));

  return $widgets;
}

/**
 * Load ACF field groups for widgets
 */
function jellypress_load_acf_widget_fields($paths) {
  $widgets = jellypress_get_widgets();
  foreach ($widgets as $widget) {

    $directory = get_template_directory() . '/template-parts/widgets/' . $widget;

    // Only proceed if directory is type 'dir'
    if (is_dir($directory)) {
      $paths[] = $directory;
    }
  }

  return $paths;
}
add_filter('acf/settings/load_json', 'jellypress_load_acf_widget_fields');

/**
 * Dynamically populate the menu select field in ACF
 * with a list of registered menus.
 */
add_filter('acf/load_field/name=navigation_menus', 'jellypress_populate_menu_select');
function jellypress_populate_menu_select($field) {

  // Reset choices
  $field['choices'] = array();

  // Get all registered menus
  $menus = get_registered_nav_menus();

  if (is_array($menus)) {

    foreach ($menus as $key => $value) {
      $field['choices'][$key] = $value;
    }
  }
  // Return the field
  return $field;
}
