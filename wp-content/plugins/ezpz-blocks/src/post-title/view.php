<?php

if (!defined('ABSPATH')) exit;

/**
 * Server-side rendering of the `ezpz/post-title` block.
 */

$class = 'title';
$class .= ' ' . get_post_type() . '-title';
the_title('<h1 class="' . $class . '">', '</h1>');