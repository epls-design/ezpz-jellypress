<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'jellypress_body_classes' ) ) {
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
    if ( ! is_active_sidebar( 'default-sidebar' ) ) {
      $classes[] = 'no-sidebar';
    }

    return $classes;
  }
}
add_filter( 'body_class', 'jellypress_body_classes' );

if ( ! function_exists( 'jellypress_pingback_header' ) ) {
  /**
   * Add a pingback url auto-discovery header for single posts, pages, or attachments.
   */
  function jellypress_pingback_header() {
    if ( is_singular() && pings_open() ) {
      printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
  }
}
add_action( 'wp_head', 'jellypress_pingback_header' );

if ( ! function_exists( 'jellypress_yoastprioritylow' ) ) {
  /**
   * Move Yoast Meta Box to bottom of editor
   */
  function jellypress_yoastprioritylow() {
    return 'low';
  }
}
add_filter( 'wpseo_metabox_prio', 'jellypress_yoastprioritylow');

if ( ! function_exists( 'jellypress_unset_image_sizes' ) ) {
  /**
   * Wordpress 5.3+ adds additional image sizes. All of these get generated every time an image is
   * uploaded. This function removes some of those images, to reduce server load and storage use.
   * Note: you may need to regenerate thumbnails to clear any using old sizes.
   */

  function jellypress_unset_image_sizes($sizes) {
    //unset($sizes['thumbnail']);    // disable thumbnail size
    //unset($sizes['medium']);       // disable medium size
    //unset($sizes['large']);        // disable large size
    unset($sizes['medium_large']); // disable medium-large size
    unset($sizes['1536x1536']);    // disable 2x medium-large size
    unset($sizes['2048x2048']);    // disable 2x large size
    return $sizes;
  }
}
add_action('intermediate_image_sizes_advanced', 'jellypress_unset_image_sizes');

if ( ! function_exists( 'jellypress_prevent_autotags' ) ) {
  /**
   * Prevents Wordpress from automatically adding classes to pasted text, on <span> and <p> tags (eg. class="p1")
   */
  function jellypress_prevent_autotags($in) {
    $in['paste_preprocess'] = "function(pl,o){ o.content = o.content.replace(/p class=\"p[0-9]+\"/g,'p'); o.content = o.content.replace(/span class=\"s[0-9]+\"/g,'span'); }";
    return $in;
  }
}
add_filter('tiny_mce_before_init', 'jellypress_prevent_autotags');

/**
 * Change the default search page to /search/$s rather than ?s=$s
 *
 * @return void
 */
if ( !function_exists( 'jellypress_change_search_url' )) :
  function jellypress_change_search_url() {
    if ( is_search() && ! empty( $_GET['s'] ) ) {
      wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) ); // Redirect to slug search
      exit();
    }
  }
  add_action( 'template_redirect', 'jellypress_change_search_url' );
endif;

/**
* Filter to remove the leading text from archive pages (eg 'Category:', 'Author:')
*/
add_filter( 'get_the_archive_title', function ($title) {
if ( is_category() )
  $title = single_cat_title( '', false );
elseif ( is_tag() )
  $title = single_tag_title( '', false );
elseif ( is_author() )
  $title = '<span class="vcard">' . get_the_author() . '</span>' ;
elseif ( is_tax() ) //for custom post types
  $title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
elseif (is_post_type_archive())
  $title = post_type_archive_title( '', false );
return $title;
});
