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

/**
 * Obscure email addresses from spam bots
 * Spam bots will only be able to read the email address if they are capable of executing javascript
 * @link http://www.maurits.vdschee.nl/php_hide_email/
 */
if ( ! function_exists( 'jellypress_hide_email' ) ) {
  function jellypress_hide_email($email) {
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
    for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
    return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
  }
}

/**
 * Adds a function to display SVGs from the spritesheet.
 */
if ( ! function_exists( 'jellypress_icon' ) ) {
  function jellypress_icon($icon) {
    // Define SVG sprite file.
    $icon_path = get_theme_file_path( '/dist/icons/'.$icon.'.svg' );
    // If it exists, include it.
    if ( file_exists( $icon_path ) ) {
      $use_link = get_template_directory_uri().'/dist/icons/icons.svg#icon-'.$icon;
      return '<svg class="icon"><use xlink:href="'.$use_link.'" /></use></svg>';
    }
    else {
      return '';
    }
  }
}
