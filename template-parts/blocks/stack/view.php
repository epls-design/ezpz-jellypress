<?php

/**
 * Flexible layout: Stack
 * Renders a display of block(s) from the Stacks CPT. Allows the user to create reusable components for use across the site.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];

$post = $block['stack'];
if ($post) :
  setup_postdata($post); // Set up "environment" for template tags
  get_template_part('template-parts/blocks/acf-flexible-content/view', null, array('is_stack' => true, 'stack_id' => $block_id)); // Get flexible content from ACF
  wp_reset_postdata();
endif;
