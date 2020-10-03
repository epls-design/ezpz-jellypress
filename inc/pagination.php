<?php
/**
 * Custom pagination for this theme
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'jellypress_numeric_pagination' ) ) :
  /**
   * This function adds numeric pagination to archive pages
   * @link https://gist.github.com/franz-josef-kaiser/818457/b947cc90a960633c7bc8373b907d138189e006ba
   * @param integer $range = how many posts to show
   * @param string $oldest_text = Text to appear in 'Last' link
   * @param string $newest_text = Text to appear in 'First' link
   *
   */
  function jellypress_numeric_pagination( $oldest_text = 'Oldest posts', $newest_text = 'Newest posts', $range = 5, $query = 'default' ) {
    // $paged - number of the active page
    global $paged, $wp_query;
    if ($query == 'default') {
      $search = $wp_query;
    }
    else {
      $search = $query; // This is so that we can use the numeric pagination on search and filter, because it uses $query and not $wp_query
    }
    // How many pages do we have?
    if ( !$max_page )
      $max_page = $search->max_num_pages;
    // We need the pagination only if there is more than 1 page
    if ( $max_page > 1 ) :
      if ( !$paged ) $paged = 1;

    // Wrap in several containers
    echo "\n".'<div class="container"><div class="row"><div class="col"><nav class="nav-links"><ul class="pagination">'."\n";
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

    echo "\n".'</ul></nav></div></div></div>'."\n";

  endif; //  if ( $max_page > 1 )
  }
endif;
