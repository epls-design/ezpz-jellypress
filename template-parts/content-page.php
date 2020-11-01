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

<section class="block block__content bg-white">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="page-content">
          <?php
            the_content();
            wp_link_pages( array(
              'before' => '<footer class="page-links">' . esc_html__( 'Sections:', 'jellypress' ),
              'after'  => '</footer>',
            ));
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
