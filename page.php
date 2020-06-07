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

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php
        while ( have_posts() ) :
          the_post();
          get_template_part( 'template-parts/content', 'page' );
          get_template_part( 'template-parts/acf-flexible-content'); // Get flexible content from ACF

          jellypress_entry_footer(); // Call function from template-tags
          ?>
          </article><!-- /#post-<?php the_ID(); ?> -->
          <?php
          // If comments are open or we have at least one comment, load up the comment template.
          if ( comments_open() || get_comments_number() ) :
            comments_template();
          endif;
        endwhile; // End of the loop.
      ?>
  </main><!-- /#main -->
</div><!-- /#primary -->
<?php
// get_sidebar(); // By default, Jellypress does not support sidebars on page templates
get_footer();
