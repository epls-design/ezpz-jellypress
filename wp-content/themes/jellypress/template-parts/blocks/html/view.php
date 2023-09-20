<?php

/**
 * Unfiltered HTML Block Template. Can only be used inside ezpz/content.
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

$fields = get_fields();

jellypress_acf_placeholder(
  $fields['unfiltered_html'],
  __('Please add your html to this block - click here to get started.', 'jellypress'),
  $is_preview
);
if (!$is_preview) {
  echo $fields['unfiltered_html'];
} elseif ($is_preview && $fields['unfiltered_html']) {
  echo '<div class="callout">' . __('Your HTML will only render on the front end', 'jellypress') . '</div>';
}
