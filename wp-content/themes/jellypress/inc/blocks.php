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
      $reg = register_block_type(get_template_directory() . '/template-parts/blocks/' . $block . '/block.json');
    }
  }
}
add_action('init', 'jellypress_register_blocks', 5);

/**
 * Scans the blocks directory for all blocks and returns an array of any functions.php files found
 *
 * @return array $partials An array of paths to functions.php files
 */
function jellypress_get_block_functions() {
  $block_folders = glob(get_template_directory() . '/template-parts/blocks/*', GLOB_ONLYDIR);
  $partials = [];
  foreach ($block_folders as $folder) {
    $block_functions = $folder . '/functions.php';
    if (file_exists($block_functions)) $partials[] = $block_functions;
  }
  return $partials;
}

/**
 * Stores an array of additional functions.php files to include, that are bundled with each block.
 * This is used to include block specific functionality, such as custom fields, enqueues etc.
 */
function jellypress_register_block_functions() {
  $theme   = wp_get_theme();
  $blocks_with_functions  = get_option('jellypress_block_functions');
  $version = get_option('jellypress_block_functions_version');
  if (
    empty($blocks_with_functions) ||
    version_compare($theme->get('Version'), $version) ||
    (function_exists('wp_get_environment_type') && 'production' !== wp_get_environment_type())
  ) {
    $blocks_with_functions = jellypress_get_block_functions();
    update_option('jellypress_block_functions', $blocks_with_functions);
    update_option('jellypress_block_functions_version', $theme->get('Version'));
  }

  // Require the files
  if (!empty($blocks_with_functions)) {
    foreach ($blocks_with_functions as $path_to_function) {
      require_once $path_to_function;
    }
  }
}

/**
 * Get all block names from template-parts/blocks
 */
function jellypress_get_blocks() {
  $theme   = wp_get_theme();
  $blocks  = get_option('jellypress_blocks');
  $version = get_option('jellypress_blocks_version');
  // if (empty($blocks) || version_compare($theme->get('Version'), $version) || (function_exists('wp_get_environment_type') && 'production' !== wp_get_environment_type())) {
  $blocks = scandir(get_template_directory() . '/template-parts/blocks/');

  // Remove unnecessary directories and files
  $blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store')));
  update_option('jellypress_blocks', $blocks);
  update_option('jellypress_blocks_version', $theme->get('Version'));
  // }
  return $blocks;
}

/**
 * Load ACF field groups for blocks
 */
function jellypress_load_acf_block_fields($paths) {
  $blocks = jellypress_get_blocks();
  foreach ($blocks as $block) {

    $directory = get_template_directory() . '/template-parts/blocks/' . $block;

    // Only proceed if directory is type 'dir'
    if (is_dir($directory)) {
      $paths[] = $directory;
    }
  }
  return $paths;
}
add_filter('acf/settings/load_json', 'jellypress_load_acf_block_fields');

/**
 * Specify which blocks are exposed to Gutenberg Editor
 */
add_filter('allowed_block_types_all', 'jellypress_allowed_blocks', 99, 2);
function jellypress_allowed_blocks($allowed_block_types, $editor_context) {
  if (!empty($editor_context->post)) {
    $blocks = jellypress_get_blocks();

    $post_type = $editor_context->post->post_type;

    $theme_blocks = [
      "core/heading",
      "core/paragraph",
      "core/table",
      "core/image",
      "core/list",
      "core/list-item",
      "core/quote",
      "core/audio",
      "core/pullquote",
      "core/embed",
      "core/separator",
      "core/html",
      "core/shortcode",
      "core/code",
      "core/footnotes",
      "gravityforms/form",
      "core/separator",
      // "core/rss",
      // "core/group", // TODO: Add support for group, but by default its doing row/stack which we dont want
      "core/block",
    ];


    if ($post_type == 'post') {
      $disallowed_block_types = [
        "core/block",
        "ezpz/section",
        "ezpz/columns",
        "ezpz/text-media"
      ];
      $theme_blocks = array_diff($theme_blocks, $disallowed_block_types);

      // This way no other blocks will get merged into posts
      return $theme_blocks;
    }

    // Get blocks from the theme
    foreach ($blocks as $block) {
      $directory = get_template_directory() . '/template-parts/blocks/' . $block;
      // Only proceed if directory is type 'dir'
      if (is_dir($directory)) {
        $theme_blocks[] = 'ezpz/' . $block;
      }
    }

    if (is_array($allowed_block_types))
      $allowed_block_types = array_merge($allowed_block_types, $theme_blocks);
    else
      $allowed_block_types = $theme_blocks;
  }

  return $allowed_block_types;
}

/**
 * Gutenberg blocks are not validated on save, so we need to manually validate
 * This is a known issue - see https://support.advancedcustomfields.com/forums/topic/required-fields-in-gutenberg-editor/
 */
add_action('acf/validate_save_post', 'jellypress_validate_acf_on_save', 5);
function jellypress_validate_acf_on_save() {

  if (!$_POST) return;

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
    array('core/paragraph', array()),
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
        'ezpz/content-restricted',
        array(),
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

add_filter('render_block_core/footnotes', 'jellypress_filter_block_core_footnotes', 20, 2);
function jellypress_filter_block_core_footnotes($block_content,  $block) {
  ob_start();
  $args = [
    'block' => $block,
    'block_content' => $block_content,
  ];
  get_template_part('template-parts/blocks/core/footnotes', null, $args);
  return ob_get_clean();
}

add_filter('render_block_core/code', 'jellypress_filter_block_core_code', 20, 2);
function jellypress_filter_block_core_code($block_content,  $block) {
  ob_start();
  $args = [
    'block' => $block,
    'block_content' => $block_content,
  ];
  get_template_part('template-parts/blocks/core/code', null, $args);
  return ob_get_clean();
}

add_filter('render_block_core/embed', 'jellypress_filter_block_core_embed', 20, 2);
function jellypress_filter_block_core_embed($block_content,  $block) {
  $attrs = $block['attrs'];

  if (empty($attrs)) return $block_content;

  if ($attrs['providerNameSlug'] == 'youtube') {
    $provider = 'youtube';
  } elseif ($attrs['providerNameSlug'] == 'vimeo') {
    $provider = 'vimeo';
  } else {
    // If provider isn't set, can we get it from the URL
    $url = $attrs['url'];

    if (strpos($url, 'youtube') !== false) {
      $provider = 'youtube';
    } elseif (strpos($url, 'vimeo') !== false) {
      $provider = 'vimeo';
    } else {
      $provider = null;
    }
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

/**
 * Rewrites the output of core/image on the front end
 */
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

/**
 * Rewrites the output of core/table on the front end
 */
add_filter('render_block_core/table', 'jellypress_filter_block_core_table', 20, 2);
function jellypress_filter_block_core_table($block_content,  $block) {
  ob_start();
  $args = [
    'block' => $block,
    'block_content' => $block_content,
  ];
  get_template_part('template-parts/blocks/core/table', null, $args);
  return ob_get_clean();
}
/**
 * Displays a block preview image in the block inserter, if it exists (and the block has a previewImage attribute set)
 * Note: in the longer term it might be nicer to render live ACF fields in the block inserter, but this is a quick and dirty way to get a preview image in there for now.
 */
function jellypress_get_block_preview_image($block) {
  if (isset($block['data']['previewImage'])) {
    // Remove 'ezpz' from the block name
    $block['name'] = str_replace('ezpz/', '', $block['name']);

    // Check if the preview image exists
    $image_path = get_template_directory() . '/template-parts/blocks/' . $block['name'] . '/preview.png';
    if (!file_exists($image_path)) return false;

    // If it does, display it
    $image_url = get_template_directory_uri() . '/template-parts/blocks/' . $block['name'] . '/preview.png';
    echo '<img src="' . $image_url . '" style="width:100%; height:auto;">';
    echo '<small>(' . __('Preview only - actual style may vary', 'jellypress') . ')</small>';
    return true;
  } else {
    return false;
  }
}

/**
 * Wraps the password form in a container
 * @param string $output The password form
 * @return string The password form wrapped in a container
 */
add_filter('the_password_form', 'jellypress_password_filter', 9999);
function jellypress_password_filter(string $output) {
  return '<section class="block bg-white"><div class="container">' . $output . '</div></section>';
}
