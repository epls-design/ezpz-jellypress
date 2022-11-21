<?php

/**
 * Register a Custom Post Type for 'Films' using the helper class defined in inc/classes.php
 * REMEMBER TO FLUSH PERMALINKS AFTER REGISTERING A CPT!
 * Methods are loosely based on @link https://github.com/jjgrainger/wp-custom-post-type-class
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Register the post type and pass through four arguments:
 * @param string $singular Post Type Single Name
 * @param string $plural Post Type Plural Name
 * @param string $menu_icon Post Type Menu Icon
 * @param array $options Post Type Options (optional) see https://developer.wordpress.org/reference/functions/register_post_type/
 *
 */
$options = array(
  // 'publicly_queryable' => false
);
$film = new customPostType('Film', 'Films', 'dashicons-video-alt', $options);

/**
 * Use the method add_support to add additional support to a post type
 * (as an alternative to passing through in $options)
 * Accepts an array or string
 * @see https://developer.wordpress.org/reference/functions/register_post_type/#supports
 */
// $film->add_support('excerpt');
// $film->add_support('thumbnail');
$film->add_support(array('thumbnail', 'excerpt'));

/**
 * Use the method add_support to remove support from a post type
 * (as an alternative to passing through in $options)
 * Accepts an array or string
 * @see https://developer.wordpress.org/reference/functions/register_post_type/#supports
 */
// $film->remove_support('title');
// $film->remove_support('editor');
// $film->remove_support(array('title', 'editor'));

/**
 * Add a message to the All Items page
 */
$film->add_admin_message('Films are awesome!');

/**
 * Register taxonomy and pass through three arguments:
 * @param string $singular Taxonomy Single Name
 * @param string $plural Taxonomy Plural Name
 * @param array $options Taxonomy Options (optional) https://developer.wordpress.org/reference/functions/register_taxonomy/#parameters
 */
$options = array(
  // 'hierarchical' => true,
  // 'meta_box_cb' => false,
);
$film->register_taxonomy('Genre', 'Genres', $options);

/**
 * Assign an existing taxonomy by passing through just the taxonomy slug
 */
// $film->register_taxonomy('category');
// $film->register_taxonomy('post_tag');

/**
 * Use the columns method to override the admin columns in Wordpress
 * This is useful if you want to eg add a custom field with the populate_column method
 */
$columns = array(
  'cb' => '<input type="checkbox" />',
  'title' => __('Title', 'jellypress'),
  'genre' => __('Genres', 'jellypress'),
  'gross_takings' => __('Box Office Takings', 'jellypress'),
  'rating' => __('Rating', 'jellypress'),
  'date' => __('Date', 'jellypress')
);
$film->columns($columns);

/**
 * Use the populate_column method to add your own data into a Wordpress admin column
 * eg. ACF data
 */
$film->populate_column('gross_takings', function ($column, $post) {
  echo "£" . get_field('gross'); // ACF get_field() function
});
$film->populate_column('rating', function ($column, $post) {
  echo get_field('rating') . ' / 5'; // ACF get_field() function
});

/**
 * Use the sortable method to make an admin column sortable
 */
$film->sortable(array(
  'gross_takings' => array('gross', true),
  'rating' => array('rating', true),
));
