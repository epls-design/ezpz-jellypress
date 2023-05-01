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

<?php if (!is_page_template('page-simple.php')) : ?>

<footer class="site-footer">
  <div class="container">
    <div class="row">
      <div class="site-info col" id="colophon">
        <p class="small">
          <?php echo jellypress_copyright(); ?>
          <span class="sep"> | </span>
          <span class="eplsdesign">
            <?php
              /* translators: 1: Theme author and link to website. */
              printf(esc_html__('Website design and build by %1$s', 'jellypress'), '<a href="https://epls.design/?utm_source=client&utm_medium=website&utm_campaign=' . sanitize_title(get_bloginfo('name')) . '" rel="author">EPLS Design</a>');
              ?>
          </span>
        </p>
      </div>
    </div>
  </div>
</footer>

<?php endif; ?>

</div>
<?php wp_footer(); ?>
</body>

</html>