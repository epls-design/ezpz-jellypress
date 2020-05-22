<?php
/**
 * Template part for displaying content when no other more specific
 * partial exists. By default, this partial shows content for posts
 * either on single.php or archive.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

?>

<?php
  if (is_singular()) {
    echo '<section class="section bg-white"><div class="container">';
  }
  else { // single.php has the <article> tag included, we need to add it for archive posts ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class('section bg-white'); ?>>
<?php } ?>
  <div class="row">
    <div class="col">

<?php
  if ( is_singular() ) : // single.php
    echo '<header class="page-header">';
    the_title( '<h1 class="page-title">', '</h1>' );
  else : // archive.php or other archive type page
    echo '<header class="entry-header">';
    the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
  endif;

  jellypress_post_thumbnail();// TODO: The Featured Image is kind of 'plonked' on here - on live builds it needs to be incorporated better into the overall design

  if ( 'post' === get_post_type() ) : // Show if post
    ?>
    <div class="entry-meta">
      <?php
      jellypress_posted_on();
      jellypress_posted_by();
      ?>
    </div><!-- /.entry-meta -->
  <?php endif; ?>
</header><!-- /.entry-header -->
<div class="entry-content">
  <?php
  the_content( sprintf(
    wp_kses(
      /* translators: %s: Name of current post. Only visible to screen readers */
      __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'jellypress' ),
      array(
        'span' => array(
          'class' => array(),
        ),
      )
    ),
    wp_kses_post( get_the_title() )
  )
);
  wp_link_pages( array(
    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'jellypress' ),
    'after'  => '</div>',
  ) );
  ?>
</div><!-- /.entry-content -->

<?php
  if (! is_singular()) {
    // Only show if on an archive page.
    // This is called after ACF flexible content on single.php for single posts
    jellypress_entry_footer();
  }?>

      </div><!-- /.col -->
  </div><!-- /.row -->
<?php
  if (is_singular()) {
    echo '</div></section>';
  }
  else { ?>
  </article><!-- /#post-<?php the_ID(); ?> -->
<?php } ?>
