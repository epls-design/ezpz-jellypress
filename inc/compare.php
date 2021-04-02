<?php
/**
 * Functions that work with TwentyTwenty Image Comparison by Zurb
 * @link https://zurb.com/playground/twentytwenty
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if (!function_exists('jellypress_compare_init')):
  function jellypress_compare_init($compare_id, $orientation = 'horizontal', $before_label = '', $after_label = '' ) {
    $output =
      "<script type='text/javascript'>
      jQuery(function ($) {
          $('#".$compare_id."').twentytwenty({
            orientation: '".$orientation."',
            before_label: '".$before_label."',
            after_label: '".$after_label."'
          });
      });
      </script>";
    $output = str_replace(array("\r", "\n","  "), '', $output)."\n";
    $func = function () use($output) {
      print $output;
    };
    wp_enqueue_script('twentytwenty');
    return $func;
  }
endif;
