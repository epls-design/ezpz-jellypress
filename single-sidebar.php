<?php
/**
 * The template for displaying all single posts with a sidebar
 * Most projects using this boilerplate tend to use ACF blocks for layout;
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
          get_template_part( 'template-parts/layout/hero/hero', get_post_type() );
          get_template_part( 'template-parts/layout/content/content', get_post_type() );
          jellypress_show_password_form();
          jellypress_entry_footer(); // Call function from template-tags
          ?>
          </article>
          <?php
          jellypress_post_navigation();
          jellypress_get_comments();
        endwhile; // End of the loop. ?>
      </main>
    </div>
    <?php jellypress_sidebar(); ?>
  </div>
</div>

<?php
get_footer();
