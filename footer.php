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

?>

	</div><!-- #content -->

	<footer class="site-footer">
    <div class="container">
      <div class="row">
        <div class="site-info col" id="colophon"> <!-- TODO: RESEARCH how the %2$s and %1$s are pulled in - where from? Text domain? -->
            <p>&copy;
              <?php echo get_bloginfo( 'name' );?> 2018-<?php echo date('Y');?>
              <span class="sep"> | </span>
              <span class="eplsdesign">
              <?php
              /* translators: 1: Theme name, 2: Theme author. */
              printf( esc_html__( 'Web design and build by %2$s.', 'jellypress' ), 'jellypress', '<a href="https://epls.design/?utm_source=client&utm_medium=website&utm_campaign=jellypress" rel="author">EPLS Design</a>' );
              ?>
            </p>
        </div><!-- #colophon -->
      </div>
    </div>
	</footer><!-- .site-footer -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
