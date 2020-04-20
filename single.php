<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package jellypress
 */

get_header();
?>
<div id="content" class="site-content">
  <div class="container">
    <div class="row">
      <div id="primary" class="content-area col">
        <main id="main" class="site-main">
          <?php
        while ( have_posts() ) :
          the_post();

          get_template_part( 'template-parts/content', get_post_type() );
          get_template_part( 'template-parts/acf-flexible-content'); // Get flexible content from ACF

          the_post_navigation();

          // If comments are open or we have at least one comment, load up the comment template.
          if ( comments_open() || get_comments_number() ) :
            comments_template();
          endif;

        endwhile; // End of the loop.
        ?>

        </main><!-- /#main -->
      </div><!-- /#primary -->

      <?php
get_sidebar(); // TODO: Remove if no support for sidebars in your theme
get_footer();
