<?php
/**
 * The template for displaying all single posts.
 * Like the page template (page.php), this template assumes
 * the editor is using ACF flexible content blocks to craft their page layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
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
      <?php while ( have_posts() ) :
        the_post();
        get_template_part( 'template-parts/hero', get_post_type() );
        get_template_part( 'template-parts/content', get_post_type() );
        jellypress_show_password_form();
        get_template_part( 'template-parts/acf-flexible-content'); // Get flexible content from ACF
        jellypress_entry_footer(); // Call function from template-tags
        ?>
    </article>
        <?php
        jellypress_post_navigation();
        jellypress_get_comments();
      endwhile; // End of the loop. ?>
  </main>
</div>

<?php
// jellypress_sidebar(); // By default, Jellypress does not support sidebars on post templates. You can re-enable this if you like, but it will be quicker to use the template single-sidebar instead
get_footer();
