<?php

/**
 * Video Block Template.
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

$block_attributes = jellypress_get_block_attributes($block);
$fields = get_fields();

jellypress_acf_placeholder(
  $fields['video'],
  __('Please add a video to this block - click here to get started.', 'jellypress'),
  $is_preview
);
jellypress_embed_video($fields['video'], $fields['aspect_ratio'], $fields['autoplay']);
// TODO: Add figcaption?