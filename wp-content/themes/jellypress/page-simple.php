<?php

/**
 * Template Name: Simple Page (No Header/Footer)
 * This template is the exact same as page.php, however if a page uses this template,
 * the header and footer branding are not displayed. This is great for producing a standalone
 * page, coming soon page etc as the website navigation is not displayed.
 * See conditional `if ( !is_page_template( 'page-simple.php' ) )` inside footer.php and header.php
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
