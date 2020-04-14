<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

?>

<section class="section section__intro">
  <div class="container"> <!-- TODO: Check that this is the most logical place for container, row and col -->
    <div class="row">
      <div class="col">
      <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
      </header><!-- .entry-header -->

      <?php jellypress_post_thumbnail();// TODO: Incorporate into theme
?>

      <div class="entry-content">
        <?php
        the_content();

        wp_link_pages( array(
          'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'jellypress' ),
          'after'  => '</div>',
        ) );
        ?>
      </div><!-- .entry-content -->

      <?php if ( get_edit_post_link() ) : ?>
        <footer class="entry-footer">
          <?php
          edit_post_link(
            sprintf(
              wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Edit <span class="screen-reader-text">%s</span>', 'jellypress' ),
                array(
                  'span' => array(
                    'class' => array(),
                  ),
                )
              ),
              wp_kses_post( get_the_title() )
            ),
            '<span class="edit-link">',
            '</span>'
          );
          ?>
        </footer><!-- .entry-footer -->
      <?php endif; ?>
      </div><!-- .col -->
    </div><!-- .row -->
  </div><!-- .container -->
</section><!-- .section__intro -->
