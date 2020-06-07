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
defined( 'ABSPATH' ) || exit;

?>
	</div><!-- /#content .site-content -->
	<footer class="site-footer">
    <div class="container">
      <div class="row">
        <div class="site-info col" id="colophon">
          <p class="small">
            <?php echo jellypress_copyright();?>
            <span class="sep"> | </span>
            <span class="mattweetdesign">
            <?php
            /* translators: 1: Theme author and link to website. */
            printf( esc_html__( 'Website design and build by %1$s.', 'jellypress' ), '<a href="https://www.mattweet.com/?utm_source=client&utm_medium=website&utm_campaign=jellypress" rel="author">Matt Weet</a>' );
            ?>
            </span>
          </p>
        </div><!-- /#colophon -->
      </div><!-- /.row -->
    </div><!-- /.container -->
	</footer><!-- /.site-footer -->
</div><!-- /#page -->
<?php wp_footer(); ?>
</body>
</html>
