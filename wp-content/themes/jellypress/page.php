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
defined('ABSPATH') || exit;

get_header();
?>

<div id="primary" class="content-area">
  <main id="main" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php
      while (have_posts()) :
        the_post();
        get_template_part('template-parts/partials/hero', get_post_type());
        get_template_part('template-parts/partials/content', get_post_type());
        jellypress_show_password_form();
        get_template_part('template-parts/blocks/acf-flexible-content'); // Get flexible content from ACF
      ?>
    </article>
    <?php
        jellypress_get_comments();
      endwhile; // End of the loop.
  ?>
  </main>
</div>

<?php
get_footer();