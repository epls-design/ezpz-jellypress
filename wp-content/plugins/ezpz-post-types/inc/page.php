<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Pass through an existing post type slug or name to ezpzPostType to create an object for it
 */
$page = new ezpzPostType('page');

// Add Excerpt support to pages
$page->add_support(array('excerpt'));
