<?php

/**
 * Buttons Block Template. Can only be used inside ezpz/content.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty as).
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

// TODO: REPLACE WITH GUTENBERG BUTTONS
// Displays the block preview in the Gutenberg editor. Requires example to be set in block.json and a preview.png image file.
if (jellypress_get_block_preview_image($block) == true) return;

$block_attributes = jellypress_get_block_attributes($block, $context);
$fields = get_fields();

if (!empty($fields['buttons'])) :

  $justify = isset($context['ezpz/alignText']) ? $context['ezpz/alignText'] : 'start';

  if ($justify == 'left') $justify = 'start';
  if ($justify == 'right') $justify = 'end';

  jellypress_display_cta_buttons($fields['buttons'], $context['ezpz/backgroundColor'], 'justify-' . $justify);
elseif ($is_preview) :
?>

  <div class="acf-placeholder">
    <div class="acf-placeholder-label"><?php _e('You need to add some buttons to this block. Please click here to edit the fields in the block sidebar, alternatively change the block view mode to "edit".', 'jellypress'); ?></div>
  </div>
<?php endif; ?>