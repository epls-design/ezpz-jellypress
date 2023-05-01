<?php

/**
 * Register a Custom Post Type for 'Stacks'
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$stacks = new ezpzPostType(
  array(
    'singular' => 'Stack',
    // 'plural' => 'Stacks',
    // 'slug' => 'stack',
  ),
  'dashicons-tagcloud',
  array(
    'menu_position' => 2
  )
);
$stacks->remove_support('editor');
$stacks->add_admin_message('Stacks are collections of blocks (or singular blocks) that can be reused throughout your website. <strong>Please note, when you add or edit a stack, you should clear the server cache to ensure the block is updated across the website.</strong>');

/**
 * Override Admin Columns
 */
$columns = array(
  'cb' => '<input type="checkbox" />',
  'title' => __('Title', 'ezpz-posts'),
  'used' => __('Used', 'ezpz-posts'),
  'date' => __('Date', 'ezpz-posts')
);
$stacks->columns($columns);

/**
 * Display in 'used' admin column how many times the stack is used, and on which posts
 */
$stacks->populate_column('used', function ($column, $post) {

  global $wpdb;
  $table_name = $wpdb->base_prefix . 'postmeta';

  $search = $wpdb->get_results(
    $wpdb->prepare("SELECT * FROM $table_name WHERE meta_value like %d AND meta_key like '%_stack%'", $post->ID)
  );

  // Search found places where the stack was used
  if ($search) {
    $permalinks = [];
    foreach ($search as $search_object) {
      if (!wp_is_post_revision($search_object->post_id)) {
        $permalinks[] = '<a href="' . get_edit_post_link($search_object->post_id) . '">' . get_the_title($search_object->post_id) . '</a>';
      }
    }
    $count = count($permalinks);

    if ($count == 1) {
      echo 'Used <strong>Once</strong> in: ';
    } else {
      echo 'Used <strong>' . $count . '</strong> times in:<br>';
    }

    echo '<strong>' . implode('</strong>, <strong>', $permalinks);
  } else {
    echo __('Not used on any post', 'ezpz-posts');
  }
});

// TODO: Display warning if user tries to delete a stack that is in use