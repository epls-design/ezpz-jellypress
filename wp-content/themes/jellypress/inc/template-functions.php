<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
add_filter('body_class', 'jellypress_body_classes');
function jellypress_body_classes($classes) {
  // Adds a class of hfeed to non-singular pages.
  if (!is_singular()) {
    $classes[] = 'hfeed';
  }
  return $classes;
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
add_action('wp_head', 'jellypress_pingback_header');
function jellypress_pingback_header() {
  if (is_singular() && pings_open()) {
    printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
  }
}

/**
 * Wordpress 5.3+ adds additional image sizes. All of these get generated every time an image is
 * uploaded. This function removes some of those images, to reduce server load and storage use.
 * Note: you may need to regenerate thumbnails to clear any using old sizes.
 */
add_action('intermediate_image_sizes_advanced', 'jellypress_unset_image_sizes');
function jellypress_unset_image_sizes($sizes) {
  //unset($sizes['thumbnail']);    // disable thumbnail size
  //unset($sizes['medium']);       // disable medium size
  //unset($sizes['large']);        // disable large size
  unset($sizes['medium_large']); // disable medium-large size
  unset($sizes['1536x1536']);    // disable 2x medium-large size
  unset($sizes['2048x2048']);    // disable 2x large size
  return $sizes;
}

/**
 * Filter to remove the leading text from archive pages (eg 'Category:', 'Author:')
 */
add_filter('get_the_archive_title', function ($title) {
  if (is_category())
    $title = single_cat_title('', false);
  elseif (is_tag())
    $title = single_tag_title('', false);
  elseif (is_author())
    $title = '<span class="vcard">' . get_the_author() . '</span>';
  elseif (is_tax()) //for custom post types
    $title = sprintf(__('%1$s'), single_term_title('', false));
  elseif (is_post_type_archive())
    $title = post_type_archive_title('', false);
  return $title;
});

/**
 * Get Primary Post Category - RankMath SEO plugin makes it the first one which is handy!
 *
 */
function jellypress_get_post_primary_category($post_id, $term = 'category') {
  $return = array();
  $categories_list = get_the_terms($post_id, $term);
  if (!empty($categories_list)) {
    $return['primary_category'] = $categories_list[0];  //get the first category
  }
  return $return;
}

/**
 * Add Support for descriptions in nav items
 */
add_filter('walker_nav_menu_start_el', 'jellypress_add_navbar_descriptions', 10, 4);
function jellypress_add_navbar_descriptions($item_output, $item, $depth, $args) {
  if (!empty($item->description)) {
    $item_output = str_replace($args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output);
  }
  return $item_output;
}

/**
 * Loops through the ACF created Gutenberg posts on the current post
 * and returns any text based fields as a long string. This is used by
 * the jellypress_generate_excerpt function to create an excerpt from
 * the ACF content.
 */
function jellypress_excerpt_from_acf_blocks() {
  $blocks = parse_blocks(get_the_content());
  $parsed_content = '';

  // This array should match the field names of any ACF field containing text
  $allowable_fields = [
    'title', 'text'
  ];

  foreach ($blocks as $block) {
    if (strpos($block['blockName'], 'ezpz/') !== false) {
      foreach ($block['attrs']['data'] as $key => $value) {
        if (in_array($key, $allowable_fields)) {
          $parsed_content .= $value;
        }
      }
    }
  }
  return $parsed_content;
}

/**
 * A function which can be used to generate an excerpt whilst in the loop
 * Use $possible_excerpts to determine the order in which excerpts are found.
 *
 * @param integer $trim Optional amount of characters to trim the output to
 * @param boolean or string $ellipses Whether or not to append the output with an ellipses.
 * @return string Sanitized and processed excerpt
 */
function jellypress_generate_excerpt($trim = null, $ellipses = false) {
  $possible_excerpts = array(
    //get_field('excerpt'), // Example use with a custom field
    get_the_excerpt(), // User defined excerpt - will fallback to the_content() gracefully
    'the_content', // Loop through ACF Flexible content to look for a WYSIWIG field
    //get_bloginfo( 'description', 'display' ), // Website description
  );
  foreach ($possible_excerpts as $possible_excerpt) {
    if ($possible_excerpt === 'the_content') {
      $post_excerpt = jellypress_excerpt_from_acf_blocks();
    } else {
      $post_excerpt = $possible_excerpt;
    }
    if ($post_excerpt) break; // Something found, end foreach
  }
  $post_excerpt = wp_strip_all_tags($post_excerpt); // Strip all HTML
  $post_excerpt = mb_substr($post_excerpt, 0, $trim, 'UTF-8'); // trim to $trim chars

  if ($post_excerpt && !preg_match('/[\p{P}]$/u', $post_excerpt) && $ellipses) {
    // Set an ellipses or string to the end, if the $post_excerpt does not end in a punctuation mark.
    if (is_string($ellipses)) $ellipses = sanitize_text_field($ellipses); // Pass a string eg '[...]'
    else $ellipses = '&#8230;'; // Fallback to an ellipses
    $post_excerpt = $post_excerpt . $ellipses;
  }
  return $post_excerpt;
}

/**
 * Hooks into Wordpress Menu to replace hashtag # with javascript:void(0)
 * Useful when you want to have a drop down parent without a corresponding page
 * @param string $menu_item item HTML
 * @return string item HTML
 */
add_filter('walker_nav_menu_start_el', 'jellypress_replace_menu_hash', 999);
function jellypress_replace_menu_hash($menu_item) {
  if (strpos($menu_item, 'href="#"') !== false) {
    $menu_item = str_replace('href="#"', 'href="javascript:void(0);"', $menu_item);
  }
  return $menu_item;
}