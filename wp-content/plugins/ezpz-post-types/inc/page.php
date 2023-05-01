<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Pass through an existing post type slug or name to ezpzPostType to create an object for it
 */
$page = new ezpzPostType('page');

// Remove support for the editor - useful if using ACF flexible content for page layout
$page->remove_support(array('editor'));

// Add Excerpt support to pages
$page->add_support(array('excerpt'));
