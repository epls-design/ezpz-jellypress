<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>
<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <?php

    // HERO
    if (have_posts()) : // Search results found...
      get_template_part('template-parts/partials/hero', 'search');
      echo '<section class="block bg-white">';
    else :
      get_template_part('template-parts/partials/hero', 'none');
      echo '<section class="block no-results not-found bg-white">';
    endif;

    echo '<div class="container">
            <div class="row">
            <div class="col">';

    if (have_posts()) : // Search results found...

      /* Start the Loop */
      echo '<div class="row equal-height search-results">';
      while (have_posts()) :
        the_post();
        echo '<div class="col xs-12 sm-6 md-4 xl-3 result">';
        get_template_part('template-parts/partials/card', 'search');
        echo '</div>';
      endwhile;
      echo '</div>';
      jellypress_numeric_pagination(); // Paginate if there are older posts
    else :
      get_template_part('template-parts/partials/content', 'none');
    endif;

    echo '</div>';
    echo '</div>
          </div>
          </section>';
    ?>
  </main>
</div>
<?php get_footer(); ?>