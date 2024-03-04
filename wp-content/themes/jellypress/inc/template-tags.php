<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function jellypress_posted_on() {
  $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
  if (get_the_time('U') !== get_the_modified_time('U')) {
    $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <time class="updated screen-reader-text" datetime="%3$s">(Updated: %4$s)</time>';
  }

  $time_string = sprintf(
    $time_string,
    esc_attr(get_the_date(DATE_W3C)),
    esc_html(get_the_date()),
    esc_attr(get_the_modified_date(DATE_W3C)),
    esc_html(get_the_modified_date())
  );

  $posted_on = sprintf(
    /* translators: %s: post date. */
    esc_html_x('Posted on %s', 'post date', 'jellypress'),
    '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
  );

  echo '<span class="posted-on">' . $posted_on . '</span>';
}

/**
 * Prints HTML with meta information for the current author.
 */
function jellypress_posted_by() {
  $byline = sprintf(
    /* translators: %s: post author. */
    esc_html_x('by %s', 'post author', 'jellypress'),
    '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
  );

  echo '<span class="byline"> ' . $byline . '</span>';
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function jellypress_entry_footer() {

  // Hide category and tag text for pages.
  if ('post' === get_post_type()) {
    echo '<footer class="entry-footer"><div class="container"><div class="row"><div class="col">';
    /* translators: used between list items, there is a space after the comma */
    $categories_list = get_the_category_list(esc_html__(', ', 'jellypress'));
    if ($categories_list) {
      /* translators: 1: list of categories. */
      printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'jellypress') . '</span>', $categories_list);
    }

    /* translators: used between list items, there is a space after the comma */
    $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'jellypress'));
    if ($tags_list) {
      /* translators: 1: list of tags. */
      printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'jellypress') . '</span>', $tags_list);
    }
  }

  // If the post type is a post (ie. not a page), it is not single, is not private and has comments open or at least one comment, show a link to the comments...
  if ('post' === get_post_type() && !is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
    echo '<span class="comments-link">';
    comments_popup_link(
      sprintf(
        wp_kses(
          /* translators: %s: post title */
          __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'jellypress'),
          array(
            'span' => array(
              'class' => array(),
            ),
          )
        ),
        get_the_title()
      )
    );
    echo '</span>';
  }
  if ('post' === get_post_type()) echo '</div></div></div></footer>';
}

/**
 * Returns an attachment image without srcset
 *
 * @param integer $attachment_id
 * @param string $size
 * @param boolean $icon
 * @param string $attr
 * @return html for attachment image
 */
function wp_get_attachment_image_no_srcset($attachment_id, $size = 'thumbnail', $icon = false, $attr = '') {
  // add a filter to return null for srcset
  add_filter('wp_calculate_image_srcset_meta', '__return_null');
  // get the srcset-less img html
  $html = wp_get_attachment_image($attachment_id, $size, $icon, $attr);
  // remove the above filter
  remove_filter('wp_calculate_image_srcset_meta', '__return_null');
  return $html;
}

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function jellypress_post_thumbnail($size = 'medium', $classes = null) {
  if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
    return;
  }
  if (is_singular()) :
?>

    <figure class="post-thumbnail">
      <?php
      if ($classes) the_post_thumbnail($size, ['class' => $classes]);
      else the_post_thumbnail($size)
      ?>
    </figure>

  <?php else : ?>

    <a class="post-thumbnail" href="<?php the_permalink(); ?>" tabindex="-1">
      <?php
      if ($classes) the_post_thumbnail($size, ['class' => $classes, 'alt' => the_title_attribute()]);
      else the_post_thumbnail($size, ['alt' => the_title_attribute()]);
      ?>
    </a>

<?php
  endif; // End is_singular().
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
    post_status = 'publish' AND post_type = 'page'
    ");
  $output = '';
  if ($copyright_dates) {
    $copyright = "&copy; " . get_bloginfo('name') . ' ' . $copyright_dates[0]->firstdate;
    if ($copyright_dates[0]->firstdate != $copyright_dates[0]->lastdate) {
      $copyright .= '-' . $copyright_dates[0]->lastdate;
    }
    $output = $copyright;
  }
  return $output;
}

/**
 * This function effectively does the same job as the_post_navigation() but wraps the navigation in Jellyfish compliant classes.
 * It will only display the_post_navigation() if there are posts to show.
 *
 * @return void
 */
function jellypress_post_navigation() {
  $prev_post = get_previous_post();
  $prev_id = $prev_post->ID;
  $prev_permalink = get_permalink($prev_id);
  $next_post = get_next_post();
  $next_id = $next_post->ID;
  $next_permalink = get_permalink($next_id);
  $postType = get_post_type_object(get_post_type());

  if ($prev_id || $next_id) :
    echo '<nav class="post-navigation block bg-white"><div class="container"><div class="row"><div class="col">';
    _e('<h2 class="screen-reader-text">' . $postType->labels->singular_name . ' navigation</h2>', 'jellypress');
    echo '<div class="nav-links">';
    if ($prev_id)
      _e('<div class="nav-previous">Previous<span class="screen-reader-text"> ' . $postType->labels->singular_name . '</span>: <a href="' . $prev_permalink . '" rel="prev">' . $prev_post->post_title . '</a></div>', 'jellypress');
    if ($next_id)
      _e('<div class="nav-next">Next<span class="screen-reader-text"> ' . $postType->labels->singular_name . '</span>: <a href="' . $next_permalink . '" rel="next">' . $next_post->post_title . '</a></div>', 'jellypress');
    echo '</div></div></div></div></nav>';
  endif;
}


function jellypress_get_comments() {
  // If comments are open or we have at least one comment, load up the comment template.
  if (comments_open() || get_comments_number()) :
    comments_template();
  endif;
};

/**
 * Wrap oEmbed to play nice with responsive
 */
add_filter('embed_oembed_html', 'jellypress_wrap_oembed', 99, 4);
function jellypress_wrap_oembed($html, $url, $attr, $post_id) {

  return '<div class="embed-container">' . $html . '</div>';
}

/**
 * This function adds numeric pagination to archive pages
 * @link https://gist.github.com/franz-josef-kaiser/818457/b947cc90a960633c7bc8373b907d138189e006ba
 * @param integer $range = how many posts to show
 *
 */
function jellypress_numeric_pagination($show_total = false, $range = 4, $query = 'default', $scrollto = '') {

  // $paged - number of the active page
  global $paged, $wp_query;

  if ($query == 'default') $search = $wp_query;
  else $search = $query; // This is so that we can use the numeric pagination on search and filter, because it uses $query and not $wp_query


  // How many pages do we have?
  $max_page = $search->max_num_pages;


  // We need the pagination only if there is more than 1 page
  if ($max_page > 1) {

    if (!$paged) $paged = 1;

    // Wrap in several containers
    echo "\n" . '<div class="row"><div class="col"><nav class="nav-links"><ul class="pagination">' . "\n";

    // We need the sliding effect only if there are more pages than is the sliding range
    if ($max_page > $range) {

      if ($show_total) printf('<li class="nav-total">%s</li>' . "\n", __('Page ' . $paged . ' of ' . $max_page, 'jellypress'));

      // On the first page, don't put the First page link
      if ($paged != 1)
        echo '<li class="nav-first"><a href="' . get_pagenum_link(1) . '" title="' . __('First Page', 'jellypress') . '">' . jellypress_icon('caret-left') . ' </a></li>';

      // To the previous page
      //echo '<li class="nav-next">';
      //  previous_posts_link(' &laquo; '); // «
      //echo '</li>';

      // When closer to the beginning
      if ($paged < $range) :
        for ($i = 1; $i <= ($range + 1); $i++) {
          $class = $i == $paged ? 'active' : '';
          echo '<li class="paged-num ' . $class . '"><a href="' . get_pagenum_link($i) . $scrollto . '" title="' . __('Page ' . $i . ' of ' . $max_page, 'jellypress') . '"> ' . $i . ' </a></li>';
        }
        echo '<li class="nav-ellipses">' . jellypress_icon('ellipses') . '</li>';
      // When closer to the ends
      elseif ($paged >= ($max_page - ceil($range / 2))) :
        echo '<li class="nav-ellipses">' . jellypress_icon('ellipses') . '</li>';
        for ($i = $max_page - $range; $i <= $max_page; $i++) {
          $class = $i == $paged ? 'active' : '';
          echo '<li class="paged-num ' . $class . '"><a href="' . get_pagenum_link($i) . $scrollto . '" title="' . __('Page ' . $i . ' of ' . $max_page, 'jellypress') . '"> ' . $i . ' </a></li>';
        }
      // Somewhere in the middle
      elseif ($paged >= $range && $paged < ($max_page - ceil($range / 2))) :
        echo '<li class="nav-ellipses">' . jellypress_icon('ellipses') . '</li>';
        for ($i = ($paged - ceil($range / 2)); $i <= ($paged + ceil($range / 2)); $i++) {
          $class = $i == $paged ? 'active' : '';
          echo '<li class="paged-num ' . $class . '"><a href="' . get_pagenum_link($i) . $scrollto . '" title="' . __('Page ' . $i . ' of ' . $max_page, 'jellypress') . '"> ' . $i . ' </a></li>';
        }
        echo '<li class="nav-ellipses">' . jellypress_icon('ellipses') . '</li>';
      endif;

      // Next page
      //echo '<li class="nav-previous">';
      //  next_posts_link(' &raquo; '); // »
      //echo '</li>';

      // On the last page, don't put the Last page link
      if ($paged != $max_page) echo '<li class="nav-last"><a href="' . get_pagenum_link($max_page) . '" title="' . __('Last Page', 'jellypress') . '"> ' . jellypress_icon('caret-right') . '</a></li>';
    } else {
      // Less pages than the range, no sliding effect needed
      for ($i = 1; $i <= $max_page; $i++) {
        $class = $i == $paged ? 'active' : '';
        echo '<li class="paged-num ' . $class . '"><a href="' . get_pagenum_link($i) . '" title="' . __('Page ' . $i . ' of ' . $max_page, 'jellypress') . '"> ' . $i . ' </a></li>';
      }
    }

    echo "\n" . '</ul></nav></div></div>' . "\n"; // Close the containers

  } //  if ( $max_page > 1 )
}
