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
<?php
/**
 * On single post pages, the user may wish to enable a sidebar, so we wrap the whole output in a .container .row
 * This is different to the layout of page.php
 */
 ?>
<div class="section">
  <div class="container">
    <div class="row">

      <div id="primary" class="content-area col">
        <main id="main" class="site-main" role="main">

          <?php while ( have_posts() ) :
            the_post();

            get_template_part( 'template-parts/content', get_post_type() );
            get_template_part( 'template-parts/acf-flexible-content'); // Get flexible content from ACF
            // Note that if you have enabled the sidebar, the ACF content may look weird as it is designed to work best on a full-width template!

            jellypress_entry_footer(); // Call function from template-tags

            the_post_navigation();

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
              comments_template();
            endif;
          endwhile; // End of the loop. ?>

        </main><!-- /#main -->
      </div><!-- /#primary .col -->
      <?php get_sidebar(); // TODO: Remove if you don't want a sidebar on your post page ?>
    </div><!-- /.row -->
  </div><!-- /.container -->
</div><!-- /.section -->
<?php
get_footer();
