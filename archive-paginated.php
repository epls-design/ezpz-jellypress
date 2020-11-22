<?php
/**
 * The template for displaying archive pages
 * This template uses pagination to load new posts. For an ajax approach, use the archive.php template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$term = get_queried_object();

get_header();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">

  <?php

    // HERO
    if ( have_posts() ) : // Search results found...
      get_template_part( 'template-parts/hero', 'archive' );
      echo '<section class="block bg-white">';
    else:
      get_template_part( 'template-parts/hero', 'none' );
      echo '<section class="block no-results not-found bg-white">';
    endif;

    echo '<div class="container">
          <div class="row">
          <div class="col">';

    if ( have_posts() ) :
      echo '<div class="row equal-height">';
      /* Start the Loop */
      while ( have_posts() ) :
        the_post();
        echo '<article class="col xs-12 sm-6 md-4 xl-3" id="post-'.get_the_ID().'">';
          get_template_part( 'template-components/card', get_post_type() );
        echo '</article>';
      endwhile;
      echo '</div>';

      jellypress_numeric_pagination(); // Paginate if there are older posts

      else :
        get_template_part( 'template-parts/content', 'none' );
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
get_footer();
