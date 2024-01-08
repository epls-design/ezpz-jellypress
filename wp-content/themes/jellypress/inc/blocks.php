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
    array('ezpz/hero-page', array(
      'lock' => array(
        'move'   => true,
        'remove' => true,
      ),
    )),
    array('ezpz/section', array()),
  );

  $post_type_object = get_post_type_object('post');
  $post_type_object->template = array(
    array('ezpz/hero-post', array(
      'lock' => array(
        'move'   => true,
        'remove' => true,
      ),
    )),
    array('ezpz/section', array()),
  );
}
add_action('init', 'jellypress_block_templates', 20);

/**
 * Returns the default <InnerBlocks /> template
 * @param array $blocks The blocks to use if the default is not required
 *
 * @return array The default block, json encoded
 */
function jellypress_get_allowed_blocks($blocks = null) {
  if (!$blocks) {
    $blocks = array(
      "ezpz/content-restricted",
    );
  }
  return esc_attr(wp_json_encode($blocks));
}

/**
 * Returns the standard block template which is the 'ezpz/content-restricted' block defined in the ezpz/blocks plugin
 * @param array $template The block template to use if the default is not required
 * @return array The block template, json encoded
 *
 */
function jellypress_get_block_template($template = null) {
  if (!$template) {
    $template = array(
      array(
        'ezpz/content-restricted', array(),
      )
    );
  }

  return esc_attr(wp_json_encode($template));
}

/**
 * Hook into block render function to overwrite the output of core blocks
 *
 * @param string $block_content HTML content of the block.
 * @param array $block Gutenberg block object.
 * @return string $block_content HTML content of the block.
 */

add_filter('render_block_core/embed', 'jellypress_filter_block_core_footnotes', 20, 2);
function jellypress_filter_block_core_footnotes($block_content,  $block) {
  ob_start();
  $args = [
    'block' => $block,
    'block_content' => $block_content,
  ];
  get_template_part('template-parts/blocks/core/footnotes', null, $args);
  return ob_get_clean();
}

add_filter('render_block_core/embed', 'jellypress_filter_block_core_embed', 20, 2);
function jellypress_filter_block_core_embed($block_content,  $block) {
  $attrs = $block['attrs'];
  if ($attrs['providerNameSlug'] == 'youtube') {
    $provider = 'youtube';
  } elseif ($attrs['providerNameSlug'] == 'vimeo') {
    $provider = 'vimeo';
  } else {
    $provider = null;
  }
  if ($provider) {
    ob_start();
    $args = [
      'block' => $block,
      'block_content' => $block_content,
      'provider' => $provider,
    ];
    get_template_part('template-parts/blocks/core/embed', null, $args);
    return ob_get_clean();
  }
  return $block_content;
}

add_filter('render_block_core/image', 'jellypress_filter_block_core_image', 20, 2);
function jellypress_filter_block_core_image($block_content,  $block) {
  ob_start();
  $args = [
    'block' => $block,
    'block_content' => $block_content,
  ];
  get_template_part('template-parts/blocks/core/image', null, $args);
  return ob_get_clean();
}