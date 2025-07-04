<?php

/**
 * Widget > Menu Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 *        This is either the post ID currently being displayed inside a query loop,
 *        or the post ID of the post hosting this block.
 * @param array $context The context provided to the block by the post or it's parent block.
 * @param array $block_attributes Processed block attributes to be used in template.
 * @param array $fields Array of ACF fields used in this block.
 *
 * Block registered with ACF using block.json
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Displays the block preview in the Gutenberg editor. Requires example to be set in block.json and a preview.png image file.
if (jellypress_get_block_preview_image($block) == true) return;

$menu_exists = false;

$block_attributes = jellypress_get_widget_attributes($block, $is_preview);

$fields = get_fields();

$menu = $fields['navigation_menus'] ?? null;

$get_menu_locations = get_nav_menu_locations();
if (!empty($get_menu_locations)) {
  $menu_id = array_key_exists($menu, $get_menu_locations) ? $get_menu_locations[$menu] : null;
  if ($menu_id) {
    $menu_items = wp_get_nav_menu_items($menu_id);
    $menu_exists = !empty($menu_items);
  }
}

// Bail if no menu items
if (!$menu_exists) {
  if ($is_preview) {
    echo '<p class="text-error weight-bold">' . __('No menu items found. Widget will not display on front-end.', 'jellypress') . '</p>';
  }
  return;
}

?>

<div class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <?php
  if ($fields['widget_title']) {
    echo '<h3 class="widget-title h6">' . esc_html($fields['widget_title']) . '</h3>';
  }
  wp_nav_menu(array(
    'theme_location' => $menu,
    'menu_id'        => $menu,
    'container'      => 'nav',
    'container_class' => 'widget-menu',
  ));
  ?>
</div>