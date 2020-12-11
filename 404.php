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

// TODO: Replace with option to allow editor to customise 404 page

get_header();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <article class="error-404 not-found">
      <?php
      get_template_part( 'template-parts/layout/hero/hero', '404' );
      get_template_part( 'template-parts/layout/content/content', '404' );
      ?>
    </article>
  </main>
</div>

<?php
get_footer();
