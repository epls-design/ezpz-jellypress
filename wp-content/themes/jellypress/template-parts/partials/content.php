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

if (get_post_type() == 'post') {
  echo '<section class="block block-section bg-white"><div class="container"><div class="row"><div class="col md-10 lg-8">';
}
the_content();

if (get_post_type() == 'post') {
  echo '</div></div></div></section>';
}
echo '</div>';
