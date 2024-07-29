<?php

/**
 * Server-side rendering of the `ezpz/post-title` block.
 */

if (!defined('ABSPATH')) exit;

$class = 'title';
$class .= ' ' . get_post_type() . '-title';

$title = isset($attributes['titleText']) && $attributes['titleText'] != ''  ? $attributes['titleText'] : get_the_title();

echo '<h1 class="' . $class . '">' . $title . '</h1>';
