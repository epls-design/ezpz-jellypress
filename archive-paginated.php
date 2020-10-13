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

/**
 * On archive pages, the user may wish to enable a sidebar, so we wrap the whole output in a .container .row
 * This is different to the layout of page.php
 */

get_header();
?>
<section class="block bg-white">
  <div class="container">
    <div class="row">

      <div id="primary" class="content-area col">
        <main id="main" class="site-main">

          <?php if ( have_posts() ) : ?>
            <header class="page-header">
              <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="archive-description">', '</div>' );
              ?>
            </header>

            <?php
            echo '<div class="row equal-height archive-feed feed-'.$post_type.'" id="feed-'.$post_type.'">';

            /* Start the Loop */
            while ( have_posts() ) :
              the_post();
              echo '<article class="col xs-12 sm-6 md-4 xl-3">';
                get_template_part( 'template-components/card', get_post_type() );
              echo '</article>';
            endwhile;

            echo '</div>';

            jellypress_numeric_pagination(); // Paginate if there are older posts

            else :
              get_template_part( 'template-parts/content', 'none' );
            endif; ?>

          </main>
        </div>
      <?php get_sidebar(); ?>

    </div>
  </div>
</section>
<?php
get_footer();
