<?php
/**
 * Template part for displaying hero content when no other more specific
 * partial exists eg on single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<header class="block hero hero-<?php echo get_post_type();?> bg-white">
  <div class="container">
    <div class="row">
      <div class="col">
        <header class="page-header">
          <?php
          the_title( '<h1 class="page-title">', '</h1>' );
          jellypress_post_thumbnail(); // TODO: The Featured Image is kind of 'plonked' on here - on live builds it needs to be incorporated better into the overall design
          if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
              <?php
              jellypress_posted_on();
              jellypress_posted_by();
              ?>
            </div>
          <?php endif; ?>
        </header>
      </div>
    </div>
  </div>
</header>

