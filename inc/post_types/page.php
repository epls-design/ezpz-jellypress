<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Pass through an existing post type slug to customPostType to create an object for it
 */
$page = new customPostType('page');

// Add taxonomy for 'Page Type' - used only by editors to categorise content
$page->register_taxonomy(
  'Page Type',
  'Page Types',
  array(
    'publicly_queryable' => false,
    'rewrite' => false
  )
);

// Remove support for the editor - useful if using ACF flexible content for page layout
$page->remove_support(array('editor'));

// Add Excerpt support to pages
$page->add_support(array('excerpt'));
