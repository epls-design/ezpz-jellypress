<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<section class="section section-0 section__content bg-white">
  <div class="container">
    <div class="row">
      <div class="col">
        <header class="page-header">
          <?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
          <?php jellypress_post_thumbnail(); // TODO: The Featured Image is kind of 'plonked' on here - on live builds it needs to be incorporated better into the overall design
?>
        </header><!-- /.page-header -->
        <div class="page-content">
          <?php the_content(); ?>
          <?php
            wp_link_pages( array(
              'before' => '<footer class="page-links">' . esc_html__( 'Sections:', 'jellypress' ),
              'after'  => '</footer>',
            ));
          ?>
        </div><!-- /.page-content -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section><!-- /.section -->
