<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Change "Posts" to "News". Customise as you wish.
add_action('init', 'jellypress_change_post_object_labels', 0);

if (!function_exists('jellypress_change_post_object_labels')) :
  function jellypress_change_post_object_labels()
  {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->singular_name = _x('News', 'Post Type Singular Name', 'jellypress');
    $labels->menu_name = _x('News', 'Admin Menu text', 'jellypress');
    $labels->name_admin_bar = _x('News Article', 'Add New on Toolbar', 'jellypress');
    $labels->archives = __('News Archives', 'jellypress');
    $labels->attributes = __('News Attributes', 'jellypress');
    $labels->parent_item_colon = __('Parent News:', 'jellypress');
    $labels->all_items = __('All News', 'jellypress');
    $labels->add_new_item = __('Add New Article', 'jellypress');
    $labels->add_new = __('Add New', 'jellypress');
    $labels->new_item = __('New Article', 'jellypress');
    $labels->edit_item = __('Edit Article', 'jellypress');
    $labels->update_item = __('Update Article', 'jellypress');
    $labels->view_item = __('View Article', 'jellypress');
    $labels->view_items = __('View Article', 'jellypress');
    $labels->search_items = __('Search News', 'jellypress');
    $labels->not_found = __('Not found', 'jellypress');
    $labels->not_found_in_trash = __('Not found in Trash', 'jellypress');
    $labels->featured_image = __('Featured Image', 'jellypress');
    $labels->set_featured_image = __('Set featured image', 'jellypress');
    $labels->remove_featured_image = __('Remove featured image', 'jellypress');
    $labels->use_featured_image = __('Use as featured image', 'jellypress');
    $labels->insert_into_item = __('Insert into Article', 'jellypress');
    $labels->uploaded_to_this_item = __('Uploaded to this Article', 'jellypress');
    $labels->items_list = __('News list', 'jellypress');
    $labels->items_list_navigation = __('News list navigation', 'jellypress');
    $labels->filter_items_list = __('Filter News list', 'jellypress');
  }
endif;

// Change "Posts" icon
add_filter('register_post_type_args', 'jellypress_change_post_object_args', 20, 2);

if (!function_exists('jellypress_change_post_object_args')) :
  function jellypress_change_post_object_args($args, $post_type)
  {
    if ($post_type == 'post') {
      $args['menu_icon'] = 'dashicons-megaphone';
      $args['menu_position'] = 10;
    }

    return $args;
  }
endif;
