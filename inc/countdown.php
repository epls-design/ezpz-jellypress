<?php
/**
 * Initialize a countdown
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'jellypress_countdown_init' ) ) :
  function jellypress_countdown_init($element_id, $deadline) {
    $output =
      "<script type='text/javascript'>
      const countTo = '$deadline';
      jfInitializeClock('$element_id', countTo);
      </script>";
    $output = str_replace(array("\r", "\n","  "), '', $output)."\n";
    $func = function () use($output) {
      print $output;
    };
    return $func;
  }
endif;
