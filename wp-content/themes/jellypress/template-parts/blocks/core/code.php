<?php

/**
 * Rewrites the output of core/table on the front end, to wrap it in a responsive container.
 *
 * @param array $args['block'] The block settings and attributes.
 * @param string $args['block_content'] The block content.
 * @param string $args['provider'] The video platform
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get the original block content.
$output = $args['block_content'];

$lang = $args['block']['attrs']['codeLanguage'] ?? 'plain-text';

/**
 * Create a WP_HTML_Tag_Processor instance for manipulating HTML tags.
 * @see https://wpdevelopment.courses/articles/wp-html-tag-processor/
 * @see https://developer.wordpress.org/reference/classes/wp_html_tag_processor/
 */
$markup = new WP_HTML_Tag_Processor($output);

// Get the first <code> tag.
$markup->next_tag(array('tag_name' => 'code'));
$markup->add_class('language-' . $lang);

// Get the updated markup.
$markup = $markup->get_updated_html();

// Output the updated markup.
echo $markup;
