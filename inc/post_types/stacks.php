<?php

/**
 * Register a Custom Post Type for 'Stacks' - more args available at the link
 * @link https://developer.wordpress.org/reference/functions/register_post_type/
 *
 * REMEMBER TO FLUSH PERMALINKS AFTER REGISTERING A CPT!
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (!function_exists('jellypress_create_campaign_cpt')) :

  /**
   * Register a CPT Stack
   */
  function jellypress_create_campaign_cpt()
  {

    $labels = array(
      'name' => _x('Stacks (Block Library)', 'Post Type General Name', 'jellypress'),
      'singular_name' => _x('Stack', 'Post Type Singular Name', 'jellypress'),
      'menu_name' => _x('Stacks (Block Library)', 'Admin Menu text', 'jellypress'),
      'name_admin_bar' => _x('Stack', 'Add New on Toolbar', 'jellypress'),
      'archives' => __('Stack Archives', 'jellypress'),
      'attributes' => __('Stack Attributes', 'jellypress'),
      'parent_item_colon' => __('Parent Stack:', 'jellypress'),
      'all_items' => __('All Stacks', 'jellypress'),
      'add_new_item' => __('Add New Stack', 'jellypress'),
      'add_new' => __('Add New', 'jellypress'),
      'new_item' => __('New Stack', 'jellypress'),
      'edit_item' => __('Edit Stack', 'jellypress'),
      'update_item' => __('Update Stack', 'jellypress'),
      'view_item' => __('View Stack', 'jellypress'),
      'view_items' => __('View Stacks', 'jellypress'),
      'search_items' => __('Search Stack', 'jellypress'),
      'not_found' => __('Not found', 'jellypress'),
      'not_found_in_trash' => __('Not found in Trash', 'jellypress'),
      'featured_image' => __('Featured Image', 'jellypress'),
      'set_featured_image' => __('Set featured image', 'jellypress'),
      'remove_featured_image' => __('Remove featured image', 'jellypress'),
      'use_featured_image' => __('Use as featured image', 'jellypress'),
      'insert_into_item' => __('Insert into Stack', 'jellypress'),
      'uploaded_to_this_item' => __('Uploaded to this Stack', 'jellypress'),
      'items_list' => __('Stacks list', 'jellypress'),
      'items_list_navigation' => __('Stacks list navigation', 'jellypress'),
      'filter_items_list' => __('Filter Stacks list', 'jellypress'),
    );
    $args = array(
      'label' => __('Stack', 'jellypress'),
      'description' => __('Stacks are collections of blocks (or singular blocks) that can be reused throughout your website.', 'jellypress'),
      'labels' => $labels,
      'menu_icon' => 'dashicons-tagcloud',
      'supports' => array(
        'title',
        //'editor',
        //'excerpt',
        //'thumbnail',
        'revisions',
        //'author',
        //'comments',
        //'trackbacks',
        //'page-attributes',
        //'post-formats',
        //'custom-fields'
      ), // Core feature(s) the post type supports.
      //'taxonomies' => array(), // An array of taxonomy identifiers that will be registered for the post type. Taxonomies can be registered later with register_taxonomy()
      'public' => true, // Controls how the type is visible to authors (show_in_nav_menus, show_ui) and readers (exclude_from_search, publicly_queryable).
      'show_ui' => true, // Whether to generate a default UI for managing this post type in the admin.
      'show_in_menu' => true, // Where to show the post type in the admin menu. show_ui must be true.
      'menu_position' => 5, // The position in the menu order the post type should appear. show_in_menu must be true. Default: null - defaults to below Comments
      'show_in_admin_bar' => true, // Whether to make this post type available in the WordPress admin bar.
      'show_in_nav_menus' => true, // Whether post_type is available for selection in navigation menus.
      'can_export' => true, // Can this post_type be exported.
      'has_archive' => false, // Whether there should be post type archives, or if a string, the archive slug to use. Will generate the proper rewrite rules if $rewrite is enabled.
      'hierarchical' => false, // Whether the post type is hierarchical (e.g. page)
      'exclude_from_search' => true, // Whether to exclude posts with this post type from front end search results. Default is the opposite value of $public.
      //'show_in_rest' => true, // Whether to expose this post type in the REST API. Must be true to enable Gutenberg.
      'publicly_queryable' => true, // Whether queries can be performed on the front end for the post type as part of parse_request().
      'capability_type' => 'page', // The string to use to build the read, edit, and delete capabilities.
    );
    register_post_type('stack', $args);
  }
endif;

add_action('init', 'jellypress_create_campaign_cpt', 0);
/**
 * Creates notice for post edit screen to explain what this CPT is for
 */
if (!function_exists('jellypress_campaign_cpt_notice')) :
  function jellypress_campaign_cpt_notice()
  {
    global $pagenow;
    if (($pagenow == 'edit.php') && ($_GET['post_type'] == 'stack')) {
      echo '<div class="notice custom-notice notice-info"><p>' . __('Stacks are collections of blocks (or singular blocks) that can be reused throughout your website. <strong>Please note, when you add or edit a stack, you should clear the server cache to ensure the block is updated across the website.</strong>', 'jellypress') . '</p></div>';
    }
  }
endif;

add_action('admin_notices', 'jellypress_campaign_cpt_notice');


/**
 * If not admin, redirect all traffic to home page
 */
add_action('template_redirect', 'jellypress_redirect_cpt_stack_to_home');
if (!function_exists('jellypress_redirect_cpt_stack_to_home')) :
  function jellypress_redirect_cpt_stack_to_home()
  {
    $queried_post_type = get_query_var('post_type');
    if (is_single() && 'stack' ==  $queried_post_type && !current_user_can('edit_posts')) {
      wp_redirect(home_url('/'), 301);
      exit;
    }
  }
endif;
