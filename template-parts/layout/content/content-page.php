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

// Only display if the content is not empty. This only really exists to prevent duplicate get_password UIs if using both ACF and the content
if ( get_the_content() && '' != get_post()->post_content ):

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

<?php endif;
