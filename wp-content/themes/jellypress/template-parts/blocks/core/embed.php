<?php

/**
 * Rewrites the output of core/embed on the front end to use a custom template which allows it to be responsive and deferred loading
 * This is called from jellypress_core_block_overwrite_render() if the embed is Vimeo or YouTube
 *
 * @param array $args['block'] The block settings and attributes.
 * @param string $args['block_content'] The block content.
 * @param string $args['provider'] The video platform
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$video_url = $args['block']['attrs']['url'];
$aspect_ratio = $args['block']['attrs']['aspectRatio'];
$provider = $args['provider'];

// See if there is a figcaption passed in the block content
$content = $args['block_content'];
$pattern = '/<figcaption[^>]*>([^<]+)<\/figcaption>/i';
preg_match($pattern, $content, $matches);
if (isset($matches[1])) {
  $figcaption = trim($matches[1]);
} else {
  $figcaption = null;
}

jellypress_embed_video($video_url, $aspect_ratio, $provider, $figcaption);

// FIXME: There are some errors rendering in the admin when this block is used, because the JS edit.js view is different to this render