<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;
/**
 * Pass through an existing post type slug or name to ezpzPostType to create an object for it
 */
$post = new ezpzPostType('post');

// Remove support for the editor - useful if using ACF flexible content for page layout
$post->remove_support(array('editor'));
