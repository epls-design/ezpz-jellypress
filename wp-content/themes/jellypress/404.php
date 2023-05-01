<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (get_field('redirect_404', 'options')) :
  wp_safe_redirect(home_url()); // Redirect to home page
  exit;
endif;

get_header();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <article class="error-404 not-found">
      <?php
      get_template_part('template-parts/partials/hero', '404');
      get_template_part('template-parts/partials/content', '404');
      ?>
    </article>
  </main>
</div>

<?php
get_footer();
