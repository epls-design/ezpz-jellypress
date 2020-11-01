<?php
/**
 * Template part for displaying content when no other more specific
 * partial exists. Used on single.php, as archive.php uses the card template
 * for content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<section class="block block__content bg-white">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="entry-content">
          <?php
          the_content();
          wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Sections:', 'jellypress' ),
            'after'  => '</div>',
          ));
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
