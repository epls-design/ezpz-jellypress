<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
<?php
/**
 * On search pages, the user may wish to enable a sidebar, so we wrap the whole output in a .container .row
 * This is different to the layout of page.php
 */
 ?>
<section class="section bg-white">
  <div class="container">
    <div class="row">

      <div id="primary" class="content-area col">
        <main id="main" class="site-main">

          <?php if ( have_posts() ) : // Search results found... ?>
              <header class="page-header">
                <h1 class="page-title">
                  <?php
                    /* translators: %s: search query. */
                    printf( esc_html__( 'Search Results for: %s', 'jellypress' ), '<span>' . get_search_query() . '</span>' );
                    ?>
                </h1>
              </header><!-- /.page-header -->

              <?php
                /* Start the Loop */
                while ( have_posts() ) :
                  the_post();
                  get_template_part( 'template-parts/content', 'search' );
                endwhile;
                the_posts_navigation();

              else :
                get_template_part( 'template-parts/content', 'none' );
              endif; ?>

          </main><!--/#main -->
        </div><!-- /#primary .col -->
      <?php get_sidebar(); // TODO: Remove if you don't want a sidebar on your search results page ?>
    </div><!-- /.row -->
  </div><!-- /.container -->
</section><!-- /.section -->
<?php
get_footer();
