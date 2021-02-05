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


/**
 * Change the default search page to /search/$s rather than ?s=$s
 * Remove this if using Search and Filter Pro as it can cause a conflict.
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

/**
 * Automatically format an image's Title and add Alt text on upload
 * Takes the filename of the attachment, sanitizes and capitalises it
 */
add_action( 'add_attachment', 'jellypress_auto_attachment_meta' );
if ( !function_exists( 'jellypress_auto_attachment_meta' )) :
  function jellypress_auto_attachment_meta( $post_ID ) {

    // Check if uploaded file is an image, else do nothing

    if ( wp_attachment_is_image( $post_ID ) ) {

      $my_image_title = get_post( $post_ID )->post_title;

      // Sanitize the title:  remove hyphens, underscores & extra spaces:
      $my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );

      // Sanitize the title:  capitalize first letter of every word (other letters lower case):
      $my_image_title = ucwords( strtolower( $my_image_title ) );

      // Create an array with the image meta (Title, Caption, Description) to be updated
      // Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
      $my_image_meta = array(
        'ID'		=> $post_ID,			// Specify the image (ID) to be updated
        'post_title'	=> $my_image_title,		// Set image Title to sanitized title
        //'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
        //'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
      );

      // Set the image Alt-Text
      update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );

      // Set the image meta (e.g. Title, Excerpt, Content)
      wp_update_post( $my_image_meta );

    }
  }
endif;

/**
 * Adds helper text to the featured image box. This only works on classic editor as Gutenberg
 * does not support the filter, or have any equivalents (as of Oct 2020)
 */
add_filter( 'admin_post_thumbnail_html', 'jellypress_featured_image_admin_prompt', 10, 3 );
if ( !function_exists( 'jellypress_featured_image_admin_prompt' )) :
  function jellypress_featured_image_admin_prompt( $content, $post_id, $thumbnail_id ){
    $help_text = '<p>' . __( 'Please add a featured image. This will be used as the main image for the page on search engines and may be displayed on the page itself depending on the design. ', 'jellypress' ) . '</p>';
    return $help_text . $content;
  }
endif;

/**
 * Get Primary Post Category
 * @Link https://www.lab21.gr/blog/wordpress-get-primary-category-post/
 *
 */
if ( ! function_exists( 'jellypress_get_post_primary_category' ) ) :
  function jellypress_get_post_primary_category($post_id, $term='category', $return_all_categories=false){
    $return = array();

    if (class_exists('WPSEO_Primary_Term')){
        // Show Primary category by Yoast if it is enabled & set
        $wpseo_primary_term = new WPSEO_Primary_Term( $term, $post_id );
        $primary_term = get_term($wpseo_primary_term->get_primary_term());

        if (!is_wp_error($primary_term)){
            $return['primary_category'] = $primary_term;
        }
    }

    if (empty($return['primary_category']) || $return_all_categories){
        $categories_list = get_the_terms($post_id, $term);

        if (empty($return['primary_category']) && !empty($categories_list)){
            $return['primary_category'] = $categories_list[0];  //get the first category
        }
        if ($return_all_categories){
            $return['all_categories'] = array();

            if (!empty($categories_list)){
                foreach($categories_list as &$category){
                    $return['all_categories'][] = $category->term_id;
                }
            }
        }
    }

    return $return;
  }

endif;

/**
 * Add Support for descriptions in nav items
 */
add_filter( 'walker_nav_menu_start_el', 'jellypress_add_navbar_descriptions', 10, 4 );
if ( ! function_exists( 'jellypress_add_navbar_descriptions' ) ) :
  function jellypress_add_navbar_descriptions( $item_output, $item, $depth, $args ) {
    if ( !empty( $item->description ) ) {
        $item_output = str_replace( $args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output );
    }
    return $item_output;
  }
endif;
