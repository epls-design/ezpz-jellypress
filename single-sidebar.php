<?php
/**
 * The template for displaying all single posts with a sidebar
 * Most projects using Jellypress tend to use ACF blocks for layout;
 * sidebars are becoming increasingly less common.
 *
 * This template is a legacy template in the event that the client wants a sidebar,
 * this layout can be used. Otherwise it can be deleted from the repository for
 * your theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

          <?php while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );

            jellypress_entry_footer(); // Call function from template-tags
            ?>
            </article><!-- /#post-<?php the_ID(); ?> -->
            <?php
            the_post_navigation();

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;
          endwhile; // End of the loop. ?>
        </main><!-- /#main -->
      </div><!-- /#primary .col -->
      <?php get_sidebar(); ?>
    </div><!-- /.row -->
  </div><!-- /.container -->
<?php
get_footer();
