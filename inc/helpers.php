<?php
/**
 * Useful Helper functions and snippets
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Function which trims supplied text to a specified length.
 *
 * @param $text = Text to Trim
 * @param $maxchar = Maximum characters
 * @param string $end = Appended to text that gets trimmed
 * @return void
 */
function jellypress_trimpara($text, $maxchar, $end='...') {
  // @link https://www.hashbangcode.com/article/cut-string-specified-length-php
  if (strlen($text) > $maxchar || $text == '') {
      $words = preg_split('/\s/', $text);
      $output = '';
      $i      = 0;
      while (1) {
          $length = strlen($output)+strlen($words[$i]);
          if ($length > $maxchar) {
              break;
          }
          else {
              $output .= " " . $words[$i];
              ++$i;
          }
      }
      $output .= $end;
  }
  else {
      $output = $text;
  }
  return $output;
}

/**
 * Preg match search for [accent] and replace with a span with class .text-accent
 */
if ( ! function_exists( 'jellypress_bracket_tag_replace' ) ) {
  function jellypress_bracket_tag_replace($text) {
    // TODO: Eventually replace this with a script that searches for what is in the square bracket and then uses that as a css .class
    if (preg_match("~\[accent\](.*?)\[\/accent\]~",$text,$m)) {
      $find = ['(\[accent\])', '(\[\/accent\])'];
      $replace = ['<span class="text-accent">', '</span>'];
      return preg_replace($find, $replace,  $text);
    }
    else {
      return $text;
    }
  }
}
