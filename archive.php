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

/**
 * On archive pages, the user may wish to enable a sidebar, so we wrap the whole output in a .container .row
 * This is different to the layout of page.php
 */

get_header();
?>
  <div class="container">
    <div class="row">

      <div id="primary" class="content-area col">
        <main id="main" class="site-main">

          <?php if ( have_posts() ) : ?>
            <header class="page-header section bg-white">
              <?php
              the_archive_title( '<h1 class="page-title">', '</h1>' );
              the_archive_description( '<div class="archive-description">', '</div>' );
              ?>
            </header><!-- /.page-header -->

            <?php
            /* Start the Loop */
            while ( have_posts() ) :
              the_post();
              get_template_part( 'template-parts/content', get_post_type() );
            endwhile;
            jellypress_numeric_pagination(); // Paginate if there are older posts
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
