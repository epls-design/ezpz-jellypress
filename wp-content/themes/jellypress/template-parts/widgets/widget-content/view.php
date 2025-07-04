<?php

/**
 * Widget > Content Template.
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

$allowed_blocks = [
  'core/paragraph',
  'core/image',
  'core/heading',
  'core/list',
];

$allowed_blocks = jellypress_get_allowed_blocks($allowed_blocks);
$block_template = jellypress_get_block_template(
  array(
    array(
      'core/paragraph',
      array(),
    )
  )
);

$fields = get_fields();


?>

<div class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <InnerBlocks allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
</div>