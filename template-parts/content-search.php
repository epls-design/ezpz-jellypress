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
<!-- TODO: Replace with card? -->
  <article id="post-<?php the_ID(); ?>" <?php post_class('block bg-white'); ?>>
    <div class="row">
      <div class="col">
        <header class="entry-header">
          <?php the_title( sprintf( '<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>

          <?php if ( 'post' === get_post_type() ) : ?>
          <div class="entry-meta">
            <?php
            jellypress_posted_on();
            jellypress_posted_by();
            ?>
          </div>
          <?php endif; ?>
          <?php jellypress_post_thumbnail();// TODO: The Featured Image is kind of 'plonked' on here - on live builds it needs to be incorporated better into the overall design ?>
        </header>
        <div class="entry-summary">
          <?php jellypress_excerpt(); ?>
        </div>
        <?php jellypress_entry_footer(); ?>
        </div>
    </div>
  </article>
