<?php

/**
 * SEO filters, hooks and functions for Rank Math SEO Plugin and the theme
 * @link https://rankmath.com/kb/filters-hooks-api-developer/
 *
 * @package jellypress
 */

 /**
  * Echos out any FAQ schema into the footer
  */
add_action('wp_footer', 'jellypress_faq_schema', 100);
if (!function_exists('jellypress_faq_schema')) :
  function jellypress_faq_schema()
  {
    global $faq_schema;
    if (!empty($faq_schema)) echo '<script type="application/ld+json">' . json_encode($faq_schema) . '</script>';
  }
endif;

/**
 * Remove hentry from post_class
 */
add_filter('post_class', 'jellypress_remove_hentry_class');
if (!function_exists('jellypress_remove_hentry_class')) :
  function jellypress_remove_hentry_class($classes)
  {
    $classes = array_diff($classes, array('hentry'));
    return $classes;
  }
endif;

/**
 * Change the Rank Math Metabox Priority to move it below the editor
 *
 * @param array $priority Metabox Priority.
 */
add_filter( 'rank_math/metabox/priority', function( $priority ) {
  return 'low';
 });

 /**
 * Create Meta Description from jellypress_generate_excerpt(), if the description is missing in the Post metabox
 */
add_action( 'rank_math/frontend/description', function( $description ) {
  global $post;
  $desc = RankMath\Post::get_meta( 'description', $post->ID );

  if ( ! $desc ) {
    $desc = jellypress_generate_excerpt(160, true);
  }

  if ( $desc ) {
    return RankMath\Helper::replace_vars( $desc, $post );
  }

  return $description;
 });

/**
 * Disable Gutenberg Sidebar Integration for Rank Math Options
 */
add_filter( 'rank_math/gutenberg/enabled', '__return_false' );

/**
 * Filter: Prevent Rank Math from changing admin_footer_text.
 */
add_action( 'rank_math/whitelabel', '__return_true');
