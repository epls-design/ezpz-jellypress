<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

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
      </div><!-- /.entry-meta -->
      <?php endif; ?>
      <?php jellypress_post_thumbnail();// TODO: The Featured Image is kind of 'plonked' on here - on live builds it needs to be incorporated better into the overall design ?>
    </header><!-- /.entry-header -->
    <div class="entry-summary">
      <?php the_excerpt(); ?>
    </div><!-- /.entry-summary -->
    <?php jellypress_entry_footer(); ?>
  </article><!-- /#post-<?php the_ID(); ?> -->
</div><!-- /.row -->
