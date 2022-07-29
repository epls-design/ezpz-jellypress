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

$site_opts = jellypress_get_acf_fields('60c219d0bd368', 'option');
$footer_selector = $site_opts['footer_selector'] ? $site_opts['footer_selector'] : null;

?>
</div><?php //#content .site-content
      ?>

<?php if (!is_page_template('page-simple.php')) : ?>

  <footer class="site-footer">
    <?php
    if ($footer_selector) :
      $post = $footer_selector; // Set $post global variable to the current post object
      setup_postdata($post); // Set up "environment" for template tags
      get_template_part('template-parts/blocks/acf-flexible-content/view'); // Get flexible content from ACF
      wp_reset_postdata();
    endif;
    ?>
    <div class="bg-neutral-200">
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
    </div>
  </footer>
<?php endif; ?>

</div>
<?php wp_footer(); ?>
</body>

</html>
