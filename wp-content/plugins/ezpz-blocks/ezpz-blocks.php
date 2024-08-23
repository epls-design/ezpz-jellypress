<?php

/**
 * Plugin Name:       EZPZ Blocks
 * Description:       Registers blocks for use with Gutenberg, using @create-block. This plugin supports the theme by creating a generic 'content' block for text
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.2.1
 * Author:            EPLS
 * Author URI:        https://epls.design
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ezpz-content
 *
 * @package           create-block
 */

class ezpzBlocks {

	function __construct() {
		add_filter('block_categories_all', [$this, 'register_layout_category'], 10, 1);
		add_action('init', [$this, 'register_blocks']);
		add_filter('allowed_block_types_all', [$this, 'filter_allowed_blocks'], 20, 2);
		add_filter('plugin_action_links', [$this, 'prevent_deactivation'], 10, 2);
		add_filter('render_block', [$this, 'overwrite_render'], 20, 2);
	}

	/**
	 * Registers a new category for 'Layout' and moves it to the top
	 */
	function register_layout_category($block_categories) {
		$layout = array(
			'slug' => 'ezpz-layout',
			'title' => 'Layout'
		);
		// Push to top of array
		array_unshift($block_categories, $layout);
		return $block_categories;
	}

	/**
	 * Gets all the blocks created in this plugin
	 * @param string $prefix Prefix to add to the block names
	 * @return array $blocks Array of block names
	 */
	function get_plugin_blocks($prefix = '') {
		// Get all block folders
		$blocks = scandir(__DIR__ . '/build/');
		// Remove unnecessary directories and files
		$blocks = array_values(array_diff($blocks, array('..', '.', '.DS_Store')));

		if (!empty($prefix)) {
			$i = 0;
			foreach ($blocks as $block) {
				$block = $prefix . $block;
				$blocks[$i] = $block;
				$i++;
			}
		}

		return $blocks;
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 * Behind the scenes, it registers also all assets so they can be enqueued
	 * through the block editor in the corresponding context.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	function register_blocks() {
		$blocks = $this->get_plugin_blocks();

		if (empty($blocks)) return;

		foreach ($blocks as $block) {

			$args = [];

			// If it's post title block, we are using a dynamic callback rather than js to render the block
			if ($block == 'post-title') {
				$args['render_callback'] = function ($attributes) {
					ob_start();
					// Include the template
					include(__DIR__ . '/src/post-title/view.php');
					return ob_get_clean();
				};
			}

			register_block_type(__DIR__ . '/build/' . $block, $args);
		}
	}

	/**
	 * Filters these blocks into the array of allowed blocks because in the theme we filter to
	 * only include theme blocks. This will put back in core blocks we want, and those
	 * registered in this plugin.
	 */
	function filter_allowed_blocks($block_editor_context, $editor_context) {
		if (!empty($editor_context->post)) {

			$plugin_blocks = $this->get_plugin_blocks('ezpz/');
			$additional_blocks =  [
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
				// "core/group", // TODO: Add support for group, but by default its doing row/stack which we dont want
				"core/block",
				"complianz/document"
			];

			// Merge $additional_blocks with $plugin_blocks
			$additional_blocks = array_merge($additional_blocks, $plugin_blocks);

			if (is_array($block_editor_context))
				$block_editor_context = array_merge($block_editor_context, $additional_blocks);
			else
				$block_editor_context = $additional_blocks;
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
	function overwrite_render($block_content, $block) {

		$block_name = $block['blockName'];

		// Strip class from heading
		if ($block_name == 'ezpz/content') {
			$block_content = str_replace('<div class="wp-block-ezpz-content"', '<div class="inner-content"', $block_content);
		} elseif ($block_name == 'ezpz/section' || $block_name == 'ezpz/columns' || $block_name == 'ezpz/text-media') {

			// Strip ezpz/ from the block name
			$block_name = str_replace('ezpz/', '', $block_name);

			// Redefine the classes
			$classes = [
				'block',
				'block-' . $block_name,
			];

			$background = isset($block['attrs']['backgroundColor']) ? 'bg-' . $block['attrs']['backgroundColor'] : 'bg-white';

			$classes[] = $background;

			// Check if any have been added by the user
			isset($block['attrs']['className']) && $classes[] = $block['attrs']['className'];

			// Add the classes to the block
			$block_content = str_replace('<section', '<section class="' . implode(" ", $classes) . '"', $block_content);
		}

		return $block_content;
	}

	/**
	 * Prevents the plugin from being deactivated
	 */
	function prevent_deactivation($actions, $plugin_file) {
		static $plugin;

		if (!isset($plugin)) {
			$plugin = plugin_basename(__FILE__);
		}

		if ($plugin == $plugin_file) {
			unset($actions['deactivate']);
		}

		return $actions;
	}
}
new ezpzBlocks();
