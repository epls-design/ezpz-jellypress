<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

?>
</div><?php //#content .site-content
      ?>

<footer class="site-footer">
  <div class="container">
    <div class="row">
      <div class="site-info col" id="colophon">
        <p class="small">
          <?php echo jellypress_copyright(); ?>
          <span class="sep"> | </span>
          <span>
            <?php
            /* translators: 1: Theme author and link to website. */
            printf(
              esc_html__('Website design and build by %1$s', 'jellypress'),
              '<a class="developer" href="https://epls.design/?utm_source=client&utm_medium=website&utm_campaign=' . sanitize_title(get_bloginfo('name')) . '" rel="author">EPLS Design; full service brand, creative, design and digital agency based in Winchester, UK.</a>'
            );
            ?>
          </span>
        </p>
      </div>
    </div>
  </div>
</footer>

</div>
<?php wp_footer(); ?>
</body>

</html>