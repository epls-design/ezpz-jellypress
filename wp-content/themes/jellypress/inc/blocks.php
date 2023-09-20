<?php

/**
 * Set up Gutenberg blocks used by this theme
 * Note that we are caching the blocks in an option to avoid having to scan the
 * filesystem on every page load. This means that if you add a new block, you
 * will need to bump the version in order for the new block to be registered
 * on the live site. On local, you can set WP_ENVIRONMENT_TYPE to
 * 'development' or 'local' to avoid this.
 * @link https://www.billerickson.net/building-acf-blocks-with-block-json/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Register Blocks with Gutenberg
 */
function jellypress_register_blocks() {
  $blocks = jellypress_get_blocks();
  foreach ($blocks as $block) {
    if (file_exists(get_template_directory() . '/template-parts/blocks/' . $block . '/block.json')) {
      register_block_type(get_template_directory() . '/template-parts/blocks/' . $block . '/block.json');
    }
  }
}
add_action('init', 'jellypress_register_blocks', 5);

/**
 * Get all block names from template-parts/blocks
 */
function jellypress_get_blocks() {
  $theme   = wp_get_theme();
  $blocks  = get_option('jellypress_blocks');
  $version = get_option('jellypress_blocks_version');
  if (empty($blocks) || version_compare($theme->get('Version'), $version) || (function_exists('wp_get_environment_type') && 'production' !== wp_get_environment_type())) {
    $blocks = scandir(get_template_directory() . '/template-parts/blocks/');

    // Remove unnecessary directories and files
    $blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store')));

    update_option('jellypress_blocks', $blocks);
    update_option('jellypress_blocks_version', $theme->get('Version'));
  }
  return $blocks;
}

/**
 * Load ACF field groups for blocks
 */
function jellypress_load_acf_block_fields($paths) {
  $blocks = jellypress_get_blocks();
  foreach ($blocks as $block) {
    $paths[] = get_template_directory() . '/template-parts/blocks/' . $block;
  }
  return $paths;
}
add_filter('acf/settings/load_json', 'jellypress_load_acf_block_fields');


/**
 * Specify which blocks are exposed to Gutenberg Editor
 */
add_filter('allowed_block_types_all', 'jellypress_allowed_blocks', 10, 2);
function jellypress_allowed_blocks($block_editor_context, $editor_context) {
  if (!empty($editor_context->post)) {
    $blocks = jellypress_get_blocks();

    $allowed_blocks = [];

    foreach ($blocks as $block) {
      $allowed_blocks[] = 'ezpz/' . $block;
    }

    // Add a filter to allow plugins to add their own allowed blocks
    $allowed_blocks = apply_filters('ezpz_allowed_blocks', $allowed_blocks);

    /**
     * You can use the filter to add blocks, eg. from a plugin. Like this:
     * add_filter('ezpz_allowed_blocks', function($allowed_blocks) {
     *  $allowed_blocks[] = 'core/image';
     * return $allowed_blocks;
     * });
     */

    return $allowed_blocks;
  }

  return $block_editor_context;
}

/**
 * Gutenberg blocks are not validated on save, so we need to manually validate
 * This is a known issue - see https://support.advancedcustomfields.com/forums/topic/required-fields-in-gutenberg-editor/
 */
add_action('acf/validate_save_post', 'jellypress_validate_acf_on_save', 5);
function jellypress_validate_acf_on_save() {
  // bail early if no $_POST
  $acf = false;
  foreach ($_POST as $key => $value) {
    if (strpos($key, 'acf') === 0) {
      if (!empty($_POST[$key])) {
        acf_validate_values($_POST[$key], $key);
      }
    }
  }
}

/**
 * Restrict access to the locking UI to Administrators.
 *
 * @param array $settings Default editor settings.
 * @param WP_Block_Editor_Context $context The current block editor context.
 */
function jellypress_restrict_locking_ui($settings, $context) {
  $settings['canLockBlocks'] = current_user_can('administrator');
  return $settings;
}
add_filter('block_editor_settings_all', 'jellypress_restrict_locking_ui', 10, 2);


/**
 * Forces posts and pages to include the hero template
 * @see https://fullsiteediting.com/lessons/creating-block-templates-for-custom-post-types/
 * for more information on block templates
 * @return void
 */
function jellypress_block_templates() {
  $post_type_object = get_post_type_object('page');
  $post_type_object->template = array(
    array('ezpz/page-hero', array(
      'lock' => array(
        'move'   => true,
        'remove' => true,
      ),
    )),
  );
  $post_type_object = get_post_type_object('post');
  $post_type_object->template = array(
    array(
      'ezpz/post-hero', array(
        'lock' => array(
          'move'   => true,
          'remove' => true,
        )
      ),
    )
  );
}
add_action('init', 'jellypress_block_templates', 20);

/**
 * Enqueue JS to override Gutenberg core block settings
 */
add_action('enqueue_block_editor_assets', function () {
  wp_enqueue_script(
    'jellypress-gutenberg-overrides',
    get_template_directory_uri() . '/lib/gutenberg-overrides.js',
    array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
  );
});

/**
 * Returns the default <InnerBlocks /> template
 *
 * @return void
 */
function jellypress_get_allowed_blocks($blocks = null) {
  if (!$blocks) {
    $blocks = array(
      "ezpz/content",
    );
  }
  return esc_attr(wp_json_encode($blocks));
}

/**
 * Returns the standard block template
 */
function jellypress_get_block_template($template = null) {
  if (!$template) {
    $template = array(
      array(
        'ezpz/content', array(), array(
          array('core/heading', array(
            'placeholder' => 'Sub Title',
            'level' => 2,
          )),
          array(
            'core/paragraph', array(
              'placeholder' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus rhoncus neque nisl, a malesuada sapien cursus a. Aliquam metus mi, vestibulum venenatis ligula et, mollis laoreet quam. Aenean sed ultrices ex, a vulputate urna. Integer tellus arcu, placerat sit amet erat et, feugiat malesuada erat. Nulla nunc metus, tempus eu nibh sit amet, tincidunt fringilla massa.',
            )
          ),
        )
      )
    );
  }

  return esc_attr(wp_json_encode($template));
}
