<?php
/**
 * Template part for displaying a simple post card
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<div class="card">

  <?php
  // TODO: Replace this with a template tag, or modify jellypress_post_thumbnail so it is better in the future
  if(has_post_thumbnail()) : ?>
    <figure class="post-thumbnail card-image">
      <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
        <?php the_post_thumbnail('post-thumbnail'); ?>
      </a>
    </figure>
  <?php endif; ?>

  <header class="card-section entry-header">
    <?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" aria-hidden="true" tabindex="-1">', '</a></h3>' ); ?>
  </header><!-- /.card-section -->

  <?php // TODO: If has excerpt ?>
  <div class="card-section entry-content">
    <?php the_excerpt(); // TODO: Write a new function for this, to trimpara and if no excerpt generate some dynamically - maybe from first ACF block? ?>
  </div><!-- /.card-section -->

  <footer class="card-section card-footer">
    <a class="button button__small" href="<?php the_permalink();?>" rel="bookmark"><?php _e('Continue Reading <span class="screen-reader-text">'.get_the_title().'</span>', 'jellypress');?></a>
  </footer><!-- /.card-section -->

</div><!-- /.card -->
