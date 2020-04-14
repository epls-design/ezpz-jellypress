<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

get_header();
?>

<div id="primary" class="content-area col">
  <main id="main" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php
        while ( have_posts() ) :
          the_post();

          get_template_part( 'template-parts/content', 'page' );
          get_template_part( 'template-parts/acf-flexible-content'); // Get flexible content from ACF

          // If comments are open or we have at least one comment, load up the comment template.
          if ( comments_open() || get_comments_number() ) :
            comments_template();
          endif;

        endwhile; // End of the loop.
        ?>
    </article><!-- #post-<?php the_ID(); ?> -->
  </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar(); // TODO: Remove if no support for sidebars in your theme
get_footer();
