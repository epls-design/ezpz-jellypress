<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
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
            </header><!-- /.page-header -->

            <?php
            /* Start the Loop */
            while ( have_posts() ) :
              the_post();
              /**
              * Include the Post-Type-specific template for the content.
              * If you want to override this in the theme, then include a file
              * called content-___.php (where ___ is the Post Type name) and that will be used instead.
              */
              get_template_part( 'template-parts/content', get_post_type() );
            endwhile;
              jellypress_numeric_pagination(); // Custom Function to display pagination with numbered pages rather than simple next/prev links
            else :
              get_template_part( 'template-parts/content', 'none' );
            endif; ?>

          </main><!-- /#main -->
        </div><!-- /#primary .col -->
      <?php get_sidebar(); // TODO: Remove if you don't want a sidebar on your archive page ?>
    </div><!-- /.row -->
  </div><!-- /.container -->
<?php
get_footer();
