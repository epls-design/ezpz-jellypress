<?php

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Remove support for WP Editor if you are using ACF exclusively for content
 */

//add_action('init', 'jellypress_post_type_supports_page');
if (!function_exists('jellypress_post_type_supports_page')) :
  function jellypress_post_type_supports_page()
  {
    remove_post_type_support('page', 'editor');
    add_post_type_support('page', 'excerpt');
  }
endif;

/**
 * Register a custom taxonomy
 */
if (!function_exists('jellypress_create_page_taxonomies')) :

  // Register Taxonomy Page Type
  function jellypress_create_page_taxonomies()
  {

    $labels = array(
      'name'              => _x('Page Type', 'taxonomy general name', 'jellypress'),
      'singular_name'     => _x('Page Type', 'taxonomy singular name', 'jellypress'),
      'search_items'      => __('Search Page Types', 'jellypress'),
      'all_items'         => __('All Page Types', 'jellypress'),
      'parent_item'       => __('Parent Page Type', 'jellypress'),
      'parent_item_colon' => __('Parent Page Type:', 'jellypress'),
      'edit_item'         => __('Edit Page Type', 'jellypress'),
      'update_item'       => __('Update Page Type', 'jellypress'),
      'add_new_item'      => __('Add New Page Type', 'jellypress'),
      'new_item_name'     => __('New Page Type Name', 'jellypress'),
      'menu_name'         => __('Page Types', 'jellypress'),
    );
    $args = array(
      'labels' => $labels,
      'description' => __('Page Types for easy categorisation in the CMS', 'jellypress'),
      'hierarchical' => false, // Whether the taxonomy is hierarchical. Default false.
      'public' => true, // Whether a taxonomy is intended for use publicly either via the admin interface or by front-end users.
      'publicly_queryable' => false, // Whether the taxonomy is publicly queryable.
      'show_ui' => true, // Whether to generate and allow a UI for managing terms in this taxonomy in the admin.
      'show_in_menu' => true, // Whether to show the taxonomy in the admin menu
      'show_in_nav_menus' => false, // Makes this taxonomy available for selection in navigation menus
      'show_tagcloud' => false, // Whether to list the taxonomy in the Category Cloud Widget controls
      'show_in_quick_edit' => true, // Whether to show the taxonomy in the quick/bulk edit panel
      'show_admin_column' => true, // Whether to display a column for the taxonomy on its post type listing screens
      'show_in_rest' => true, // Whether to include the taxonomy in the REST API. You will need to set this to true in order to use the taxonomy in your gutenberg metablock.
      //'meta_box_cb' => true, // Set to False to hide from the WYSIWIG Editor sidebar
      //'rewrite' => $rewrite,
    );
    register_taxonomy('page-type', array('page'), $args);

    // Create default terms
    wp_insert_term('Landing Pages', 'page-type', array(
      'description' => 'Landing pages used to direct visitors elsewhere on the site.',
      'slug' => 'landing'
    ));
    wp_insert_term('Interactive Pages', 'page-type', array(
      'description' => 'Interactive pages, for collecting, displaying or manipulating data. E.g. "Contact Us" or "Register"',
      'slug' => 'interactive'
    ));
    wp_insert_term('eCommerce Pages', 'page-type', array(
      'description' => 'eCommerce pages, relating to the sale of goods and services. E.g. "Cart" or "Products"',
      'slug' => 'ecommerce'
    ));
    wp_insert_term('Informational Pages', 'page-type', array(
      'description' => 'Informational pages, such as "About Us" or "Meet the Team".',
      'slug' => 'informational'
    ));
    wp_insert_term('Dynamic Pages', 'page-type', array(
      'description' => 'Pages with live information, that updates frequently. For example, a page with a feed of latest news.',
      'slug' => 'dynamic'
    ));
  }
endif;
add_action('init', 'jellypress_create_page_taxonomies', 0);
