<?php

/**
 * Plugin Name:       EZPZ Blocks
 * Description:       Registers blocks for use with Gutenberg, using @create-block. This plugin supports the theme by creating a generic 'content' block for text
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            EPLS
 * Author URI:        https://epls.design
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ezpz-content
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_ezpz_content_init() {
	register_block_type(__DIR__ . '/build/content');
	register_block_type(__DIR__ . '/build/section');
	register_block_type(__DIR__ . '/build/columns');
	register_block_type(__DIR__ . '/build/column');
}
add_action('init', 'create_block_ezpz_content_init');

add_filter('allowed_block_types_all', 'ezpz_content_allowed_blocks', 20, 2);
function ezpz_content_allowed_blocks($block_editor_context, $editor_context) {
	if (!empty($editor_context->post)) {
		$additional_blocks =  [
			'ezpz/content',
			'ezpz/columns',
			'ezpz/column',
			'ezpz/section',
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item',
			'core/shortcode',
			'core/table',
		];

		// Merge the two arrays
		$block_editor_context = array_merge($block_editor_context, $additional_blocks);
	}
	return $block_editor_context;
}

/**
 * Hook into block render function to strip out tags that we dont want to render
 * This is a workaround as I couldn't work out how to do this in the save.js files
 * As useBlockProps didn't seem to allow me to overwrite
 *
 * @param string $block_content HTML content of the block.
 * @param array $block Gutenberg block object.
 * @return string $block_content HTML content of the block.
 */
function ezpz_blocks_overwrite_render($block_content, $block) {

	$block_name = $block['blockName'];

	// Strip class from heading
	if ($block_name == 'core/heading') {
		$block_content = str_replace('<h2 class="wp-block-heading"', '<h2', $block_content);
	} elseif ($block_name == 'ezpz/content') {
		$block_content = str_replace('<div class="wp-block-ezpz-content"', '<div class="inner-content"', $block_content);
	} elseif ($block_name == 'ezpz/section' || $block_name == 'ezpz/columns') {

		// Strip ezpz/ from the block name
		$block_name = str_replace('ezpz/', '', $block_name);

		// Redefine the classes
		$classes = [
			'block',
			'block-' . $block_name,
		];


		// TODO: This may be better done in JS but it works now
		$background = isset($block['attrs']['backgroundColor']) ? 'bg-' . $block['attrs']['backgroundColor'] : 'bg-white';

		$classes[] = $background;

		// Check if any have been added by the user
		isset($block['attrs']['className']) && $classes[] = $block['attrs']['className'];

		// Add the classes to the block
		$block_content = str_replace('<section', '<section class="' . implode(" ", $classes) . '"', $block_content);
	}

	return $block_content;
}
add_filter('render_block', 'ezpz_blocks_overwrite_render', 20, 2);


/**
 * Prevents the plugin from being deactivated
 */
add_filter('plugin_action_links', function ($actions, $plugin_file) {
	static $plugin;

	if (!isset($plugin)) {
		$plugin = plugin_basename(__FILE__);
	}

	if ($plugin == $plugin_file) {
		unset($actions['deactivate']);
	}

	return $actions;
}, 10, 2);