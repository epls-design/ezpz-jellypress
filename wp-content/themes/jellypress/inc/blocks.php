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
    $block_json = get_template_directory() . '/template-parts/blocks/' . $block . '/block.json';
    if (file_exists($block_json)) {
      register_block_type($block_json);
    }
  }
}
add_action('init', 'jellypress_register_blocks', 5);

/**
 * Get all block names from template-parts/blocks
 */
function jellypress_get_blocks() {

  static $blocks = null;

  if (null !== $blocks) {
    return $blocks;
  }

  $blocks = scandir(get_template_directory() . '/template-parts/blocks/');
  $blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store')));

  return $blocks;
}

/**
 * Load block-specific functions
 */
function jellypress_register_block_functions() {
  $blocks = jellypress_get_blocks();
  foreach ($blocks as $block) {
    $functions_file = get_template_directory() . "/template-parts/blocks/{$block}/functions.php";
    if (file_exists($functions_file)) {
      require_once $functions_file;
    }
  }
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

/**
 * Patterns can be defined in /patterns to allow for templating post creation
 * @see https://fullsiteediting.com/lessons/creating-block-templates-for-custom-post-types/
 * for more information on block templates
 */

/**
 * Get the global block patterns or set it if it doesn't exist.
 * Reduces the amount of repetition compiling the list
 */
global $jellypress_block_patterns;
function jellypress_get_block_patterns() {
  global $jellypress_block_patterns;

  if (isset($jellypress_block_patterns)) {
    return $jellypress_block_patterns;
  }

  $block_patterns = glob(get_template_directory() . '/patterns/*.php', GLOB_BRACE);
  if (!$block_patterns) {
    return [];
  }

  $returned_patterns = [];

  foreach ($block_patterns as $index => $pattern) {
    // $block_patterns[$index] = include $pattern;
    $pattern_data = include $pattern;

    if (!isset($pattern_data['pattern'])) {
      continue;
    }

    if (!isset($pattern_data['title'])) {
      $base_name = basename($pattern, '.php');
      $pattern_data['title'] = ucwords(str_replace('-', ' ', $base_name));
    }

    // Default to 'page' if not specified
    if (!is_array($pattern_data['post_types'])) {
      $pattern_data['post_types'] = array('page');
    }

    foreach ($pattern_data['post_types'] as $post_type) {
      if (!isset($returned_patterns[$post_type])) {
        $returned_patterns[$post_type] = [];
      }

      // Bail if a pattern with this title already exists for this post type
      $pattern_titles = array_column($returned_patterns[$post_type], 'title');
      if (in_array($pattern_data['title'], $pattern_titles)) {
        continue;
      }

      $returned_patterns[$post_type][] = [
        'title' => $pattern_data['title'],
        'pattern' => $pattern_data['pattern'],
        'description' => isset($pattern_data['description']) ? $pattern_data['description'] : '',
        'image' => isset($pattern_data['image']) ? $pattern_data['image'] : '',
      ];
    }
  }

  $jellypress_block_patterns = $returned_patterns;

  return $jellypress_block_patterns;
}
/**
 * Initialises the intermediary step in admin to choose a block pattern when creating a new post/page
 */
add_action('admin_init', 'jellypress_initialise_block_patterns', 30);
function jellypress_initialise_block_patterns() {
  $patterns = jellypress_get_block_patterns();

  if (empty($patterns)) {
    return;
  }

  global $pagenow;

  $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'post';

  if (
    $pagenow === 'post-new.php'
  ) {

    // Bail if no patterns for this post type
    if (!isset($patterns[$post_type])) {
      return;
    }

    // If only one pattern, set it
    if (count($patterns[$post_type]) === 1) {
      $post_type_object = get_post_type_object($post_type);
      $post_type_object->template = $patterns[$post_type][0]['pattern'];
      return;
    }

    // Set the pattern if found, else return without setting anything to avoid an infinite redirect
    if (isset($_GET['template'])) {
      $template_index = intval($_GET['template']);
      if (isset($patterns[$post_type][$template_index])) {
        $post_type_object = get_post_type_object($post_type);
        $post_type_object->template = $patterns[$post_type][$template_index]['pattern'];
        return;
      } else {
        return;
      }
    }

    // Else redirect to the pattern selector
    wp_redirect(admin_url('admin.php?page=select-block-pattern&post_type=' . $post_type));
    exit;
  }
}

/**
 * Add a hidden submenu page to select the pattern for use in the front end.
 */
add_action('admin_menu', 'jellypress_add_block_pattern_selector');
function jellypress_add_block_pattern_selector() {
  $patterns = jellypress_get_block_patterns();

  if (empty($patterns)) {
    return;
  }

  add_submenu_page(
    null, // Hidden from menu (accessed directly via URL)
    __('Select a Block Pattern', 'jellypress'),
    __('Select a Block Pattern', 'jellypress'),
    'edit_posts',
    'select-block-pattern',
    'jellypress_render_block_pattern_selector'
  );
}

/**
 * Callback for the pattern selector page.
 *
 * @return void
 */
function jellypress_render_block_pattern_selector() {

  // Handle form submission
  if (isset($_POST['submit'])) {
    $redirect_url = admin_url('post-new.php?post_type=' . sanitize_text_field($_POST['post_type'])) . '&template=' . sanitize_text_field($_POST['template_index']);
    wp_redirect($redirect_url);
    exit;
  }

  $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'page';
  $patterns  = jellypress_get_block_patterns();

  $post_type_object = get_post_type_object($post_type);

  // Redirect if post type doesn't exist
  if (!$post_type_object) {
    wp_redirect(admin_url());
    exit;
  }

  // FAILSAFE: Redirect to post-new.php if no patterns for this post type
  if (!isset($patterns[$post_type])) {
    wp_redirect(admin_url('post-new.php?post_type=' . $post_type));
    exit;
  }

  $post_type_name   = $post_type_object->labels->singular_name;

  $post_templates = $patterns[$post_type];
?>
  <style>
    .notice {
      display: none !important;
    }
  </style>
  <div class="wrap">
    <h1>
      <?php
      echo sprintf(
        __('Select a %s Template to get started', 'jellypress'),
        $post_type_name
      );
      ?>
    </h1>
    <div style="display: flex; flex-wrap:wrap; flex-direction: row; column-gap: 12px;">
      <?php foreach ($post_templates as $index => $template): ?>
        <div class="card" style="width: 300px;">
          <form method="post" action="">
            <h2><?php echo $template['title']; ?></h2>
            <?php
            if (isset($template['description'])): ?>
              <p><?php echo $template['description']; ?></p>
            <?php endif; ?>
            <input type="hidden" name="template_index" value="<?php echo $index; ?>" />
            <input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>" />
            <?php submit_button(__('Select this template', 'jellypress'), 'primary', 'submit'); ?>
            <?php
            if (isset($template['image']) && !empty($template['image'])): ?>
              <img src="<?php echo esc_url($template['image']); ?>" alt="<?php echo esc_attr($template['title']); ?>" style="width:100%; height:auto; margin-bottom:12px;" />
            <?php endif; ?>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  <?php
}

/**
 * Simple helper function to determine if $content passed to an ACF block has any actual text.
 * This is useful because without this, any placeholder text in the editor will cause the block to think it has content and display empty tags.
 *
 * @param string $content The block content
 * @return bool True if the block has inner content, false otherwise
 */
function jellypress_has_inner_content($content) {
  $text_content = trim(strip_tags($content));
  $has_inner_blocks = strlen($text_content) > 0;
  return $has_inner_blocks;
}
