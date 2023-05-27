<?php

/**
 * Template part for displaying content when no other more specific partial exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

echo '<div class="' . get_post_type() . '-content">';
the_content();
echo '</div>';
