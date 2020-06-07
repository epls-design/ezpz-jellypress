<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <?php get_template_part( 'template-parts/content', '404' ); ?>
  </main><!-- /#main -->
</div><!-- /#primary -->
<?php
get_footer();
