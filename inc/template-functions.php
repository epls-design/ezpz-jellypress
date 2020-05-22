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
 * Move Yoast Meta Box to bottom of editor
 */
function jellypress_yoastprioritylow() {
  return 'low';
  }
  add_filter( 'wpseo_metabox_prio', 'jellypress_yoastprioritylow');

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
add_action('intermediate_image_sizes_advanced', 'jellypress_unset_image_sizes');

/**
 * Prevents Wordpress from automatically adding classes to pasted text, on <span> and <p> tagds (eg. class="p1")
 */
function jellypress_prevent_autotags($in) {
  $in['paste_preprocess'] = "function(pl,o){ o.content = o.content.replace(/p class=\"p[0-9]+\"/g,'p'); o.content = o.content.replace(/span class=\"s[0-9]+\"/g,'span'); }";
  return $in;
}
add_filter('tiny_mce_before_init', 'jellypress_prevent_autotags');

/**
 * This function adds numeric pagination to archive pages
 * @link https://gist.github.com/franz-josef-kaiser/818457/b947cc90a960633c7bc8373b907d138189e006ba
 * @param integer $range = how many posts to show
 * @param string $oldest_text = Text to appear in 'Last' link
 * @param string $newest_text = Text to appear in 'First' link
 *
 */
if ( ! function_exists( 'jellypress_numeric_pagination' ) ) :

  function jellypress_numeric_pagination( $oldest_text = 'Oldest posts', $newest_text = 'Newest posts', $range = 5 ) {
		// $paged - number of the active page
		global $paged, $wp_query;
		// How many pages do we have?
		if ( !$max_page )
			$max_page = $wp_query->max_num_pages;
		// We need the pagination only if there is more than 1 page
		if ( $max_page > 1 ) :
      if ( !$paged ) $paged = 1;

    // Wrap in several containers
    echo "\n".'<nav class="nav-links"><ul class="pagination">'."\n";
    printf( '<li class="nav-total">%s</li>' . "\n", __( 'Page '.$paged.' of '.$max_page, 'jellypress' ));

		// On the first page, don't put the First page link
		if ( $paged != 1 )
			echo '<li class="nav-first"><a href='.get_pagenum_link(1).'>'.__($newest_text, 'jellypress').' </a></li>';

		// To the previous page
		echo '<li class="nav-next">';
      previous_posts_link(' &laquo; '); // «
		echo '</li>';

		// We need the sliding effect only if there are more pages than is the sliding range
		if ( $max_page > $range ) :
			// When closer to the beginning
			if ( $paged < $range ) :
				for ( $i = 1; $i <= ($range + 1); $i++ ) {
					$class = $i == $paged ? 'active' : '';
					echo '<li class="paged-num '.$class.'"><a href="'.get_pagenum_link($i).'"> '.$i.' </a></li>';
				}
			// When closer to the end
			elseif ( $paged >= ( $max_page - ceil($range/2)) ) :
				for ( $i = $max_page - $range; $i <= $max_page; $i++ ){
					$class = $i == $paged ? 'active' : '';
					echo '<li class="paged-num '.$class.'"><a href="'.get_pagenum_link($i).'"> '.$i.' </a></li>';
				}
      endif;
      // TODO: Around 4-5 it seems to lose the numbering
		// Somewhere in the middle
		elseif ( $paged >= $range && $paged < ( $max_page - ceil($range/2)) ) :
			for ( $i = ($paged - ceil($range/2)); $i <= ($paged + ceil($range/2)); $i++ ) {
					$class = $i == $paged ? 'active' : '';
				echo '<li class="paged-num '.$class.'"><a href="'.get_pagenum_link($i).'"> '.$i.' </a></li>';
			}
		// Less pages than the range, no sliding effect needed
		else :
			for ( $i = 1; $i <= $max_page; $i++ ) {
				$class = $i == $paged ? 'active' : '';
				echo '<li class="paged-num '.$class.'"><a href="'.get_pagenum_link($i).'"> '.$i.' </a></li>';
			}
		endif;

		// Next page
		echo '<li class="nav-previous">';
      next_posts_link(' &raquo; '); // »
		echo '</li>';

		// On the last page, don't put the Last page link
		if ( $paged != $max_page )
			echo '<li class="nav-last"><a href='.get_pagenum_link($max_page).'> '.__($oldest_text, 'jellypress').'</a></li>';

    echo "\n".'</ul></nav>'."\n";

  endif; //	if ( $max_page > 1 )
  }
endif;
