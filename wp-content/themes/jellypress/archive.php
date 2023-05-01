<?php

/**
 * The template for displaying archive pages.
 * Change the $loading_type variable to 'scroll' to load posts on scroll, 'button' to load with a button click, or 'paginated' to load posts on pagination
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$loading_type = 'paginated'; // Set to 'scroll', 'button' or 'paginated'
$term = get_queried_object();

get_header();

?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">

    <?php

    // HERO
    if (have_posts()) : // Search results found...
      get_template_part('template-parts/partials/hero', 'archive');
      echo '<section class="block bg-white">';
    else :
      get_template_part('template-parts/partials/hero', 'none');
      echo '<section class="block no-results not-found bg-white">';
    endif;

    echo '<div class="container">
            <div class="row">
            <div class="col">';

    if (have_posts()) {

      /* Start the Loop */
      if (!empty($term)) {
        echo '<div class="row equal-height archive-feed feed-' . $term->slug . '" id="feed-' . $term->slug . '">';
      } else {
        echo '<div class="row equal-height archive-feed" id="feed-posts">';
      }
      while (have_posts()) :
        the_post();
        echo '<article class="col xs-12 sm-6 md-4 xl-3" id="post-' . get_the_ID() . '">';
        get_template_part('template-parts/partials/card', get_post_type(), array('loaded' => true));
        echo '</article>';
      endwhile;
      if ($loading_type == 'scroll') {
        echo '</div><div class="row"><div class="col xs-12"><div id="archive-loading"></div></div>';
      }
      if ($wp_query->max_num_pages > 1 && $loading_type == 'button') {
        echo '</div><div class="row"><div class="col xs-12"><button class="button outline button-loadmore">' . __('Load More...', 'jellypress') . '</button></div>';
      };
      echo '</div>';

      if ($loading_type == 'paginated') {
        jellypress_numeric_pagination(); // Paginate if there are older posts
      }
    } else {
      get_template_part('template-parts/partials/content', 'none');
    }

    echo '</div>';
    echo '</div>
            </div>
            </section>';
    ?>
  </main>
</div>
<?php
if ($loading_type != 'paginated') {
  jellypress_initialize_ajax_posts(null, $loading_type); // Initialise on $wp_query
}
get_footer();
?>