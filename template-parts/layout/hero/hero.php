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
defined('ABSPATH') || exit;

?>
<header class="block hero hero-<?php echo get_post_type(); ?> bg-white">
  <div class="container">
    <div class="row">
      <div class="col">
        <div class="page-header">
          <?php
          jellypress_post_thumbnail('medium', 'alignright');
          the_title('<h1 class="page-title">', '</h1>');
          if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
              <?php
              jellypress_posted_on();
              jellypress_posted_by();
              ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</header>
