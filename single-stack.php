<?php

/**
 * The template for displaying a stack CPT.
 * These are only viewable by administrators as they are intended to be used on other posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();

// Redirect home if not an administrator
if (!current_user_can('edit_posts')) {
  wp_redirect(home_url('/'), 301);
  exit;
}

?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php while (have_posts()) :
        the_post();
        get_template_part('template-parts/layout/hero/hero', get_post_type());
        get_template_part('template-parts/blocks/acf-flexible-content/view'); // Get flexible content from ACF
      ?>
    </article>
  <?php
      endwhile; // End of the loop.
  ?>
  </main>
</div>

<?php
get_footer();
