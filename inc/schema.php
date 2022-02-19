<?php

/**
 * Hook Schema markup into WP Footer
 *
 * @package jellypress
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
