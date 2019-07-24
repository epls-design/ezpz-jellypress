<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

?>
<div class="row">
  <article id="post-<?php the_ID(); ?>" <?php post_class('col'); ?>>
    <header class="entry-header">
      <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

      <?php if ( 'post' === get_post_type() ) : ?>
      <div class="entry-meta">
        <?php
        jellypress_posted_on();
        jellypress_posted_by();
        ?>
      </div><!-- .entry-meta -->
      <?php endif; ?>
    </header><!-- .entry-header -->

    <?php jellypress_post_thumbnail(); ?> <!-- TODO: Incorporate into theme -->

    <div class="entry-summary">
      <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer">
      <?php jellypress_entry_footer(); ?>
    </footer><!-- .entry-footer -->
  </article><!-- #post-<?php the_ID(); ?> -->
</div>
