<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php or page.php file exists.
 *
 * Note: For the majority of projects, more specific templates will be in use,
 * so it is unlikely this template will be called or need to be edited
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>
<div id="primary" class="content-area ">
  <main id="main" class="site-main">
    <?php
      if ( have_posts() ) :
        if ( is_home() && ! is_front_page() ) : ?>
          <header>
            <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
          </header>
        <?php endif;

    // If Archive page, wrap content in a container
    if(!is_singular()) {
      echo '<div class="container">';
    }
    /* Start the Loop */
    while ( have_posts() ) :
      the_post();
      /**
        * Include the Post-Type-specific template for the content.
        * If you want to override this in a theme, then include a file
        * called content-___.php (where ___ is the Post Type name)
        * and that will be used instead.
        */
      get_template_part( 'template-parts/content', get_post_type() );

      if(is_singular()) {
        // Only get ACF if it's not an archive page
        get_template_part( 'template-parts/acf-flexible-content'); // Get flexible content from ACF
      }
    endwhile;
    if(!is_singular()) {
      echo '</div>'; // .container
    }
    jellypress_numeric_pagination(); // Paginate if there are older posts
  else :
    echo '<section class="block bg-white"><div class="container"><div class="row"><div class="col">';
      get_template_part( 'template-parts/content', 'none' );
    echo '</div></div></div></section>';
  endif;
  ?>
  </main>
</div>
<?php
// get_sidebar(); // By default, Jellypress does not support sidebars on page templates
get_footer();
