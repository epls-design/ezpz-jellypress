<?php

/**
 * The template for displaying archive pages.
 * This template uses AJAX to load new posts. For a paginated approach, use the alternative archive-paginated.php template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$loading_type = 'scroll';
$term = get_queried_object();

get_header();

?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">

    <?php

    // HERO
    if (have_posts()) : // Search results found...
      get_template_part('template-parts/layout/hero/hero', 'archive');
      echo '<section class="block bg-white">';
    else :
      get_template_part('template-parts/layout/hero/hero', 'none');
      echo '<section class="block no-results not-found bg-white">';
    endif;

    echo '<div class="container">
            <div class="row">
            <div class="col">';

    if (have_posts()) :

      /* Start the Loop */
      if (!empty($term)) echo '<div class="row equal-height archive-feed feed-' . $term->slug . '" id="feed-' . $term->slug . '">';
      else echo '<div class="row equal-height archive-feed" id="feed-posts">';
      while (have_posts()) :
        the_post();
        echo '<article class="col xs-12 sm-6 md-4 xl-3" id="post-' . get_the_ID() . '">';
        get_template_part('template-parts/components/card/card', get_post_type(), array('loaded' => true));
        echo '</article>';
      endwhile;
      if ($loading_type == 'scroll') echo '</div><div class="row"><div class="col xs-12"><div id="archive-loading"></div></div>';
      if ($wp_query->max_num_pages > 1 && $loading_type == 'button') {
        echo '</div><div class="row"><div class="col xs-12"><button class="button outline button-loadmore">' . __('Load More...', 'jellypress') . '</button></div>';
      };
      echo '</div>';

    else :
      get_template_part('template-parts/layout/content/content', 'none');
    endif;

    echo '</div>';
    jellypress_sidebar();
    echo '</div>
            </div>
            </section>';
    ?>
  </main>
</div>
<?php
jellypress_initialize_ajax_posts(null, $loading_type); // Initialise on $wp_query
get_footer();
?>
