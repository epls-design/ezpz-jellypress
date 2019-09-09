<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package jellypress
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function jellypress_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'jellypress_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function jellypress_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'jellypress_pingback_header' );

/**
 * Adds a function to display SVGs from the spritesheet.
 */
function jellypress_icon($icon) {
	// Define SVG sprite file.
	$icon_path = get_theme_file_path( '/assets/icons/'.$icon.'.svg' );
  // If it exists, include it.
  if ( file_exists( $icon_path ) ) {
    $use_link = THEME_URI.'/assets/icons/icons.svg#icon-'.$icon;
    echo '<svg class="icon"><use xlink:href="'.$use_link.'" /></use></svg>';
  }
  else {
    return '';
  }
}

/**
 * Obscure email addresses from spam bots
 * Spam bots will only be able to read the email address if they are capable of executing javascript
 * @link http://www.maurits.vdschee.nl/php_hide_email/
 */
function jellypress_hide_email($email) {
  $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
  $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
  for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
  $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
  $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
  $script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
  $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
  $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
  echo '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
}

/**
 * Creates a more dynamic copyright notice using first and last post date.
 * @link https://www.wpbeginner.com/wp-tutorials/how-to-add-a-dynamic-copyright-date-in-wordpress-footer/
 */
function jellypress_copyright() {
  global $wpdb;
  $copyright_dates = $wpdb->get_results("
  SELECT
  YEAR(min(post_date_gmt)) AS firstdate,
  YEAR(max(post_date_gmt)) AS lastdate
  FROM
  $wpdb->posts
  WHERE
  post_status = 'publish'
  ");
  $output = '';
  if($copyright_dates) {
  $copyright = "&copy; " . get_bloginfo( 'name' ) . ' ' . $copyright_dates[0]->firstdate;
  if($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
  $copyright .= '-' . $copyright_dates[0]->lastdate;
  }
  $output = $copyright;
  }
  return $output;
  }
