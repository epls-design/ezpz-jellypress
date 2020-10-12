<?php
/**
 * Template part for displaying a 404 error (page not found)
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<section class="block error-404 not-found bg-white">
  <div class="container">
    <div class="row">
      <article class="col sm-12 md-8 lg-9">
        <header class="page-header">
          <h1 class="page-title"><?php esc_html_e( 'Page not found', 'jellypress' ); ?></h1>
        </header>
        <div class="page-content">
          <p>
            <?php esc_html_e( 'It looks like nothing was found at this location. Can we help you find the page you were looking for?', 'jellypress' ); ?>
          </p>
          <p>
            <a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">Home Page</a>
            <button class="button__outline" onCLick="history.back()"><?php _e('Back to last page', 'jellypress');?></button>
          </p>
        </div>
      </article>
      <aside class="col sm-12 md-4 lg-3 sidebar">
        <h2><?php _e('Search this website:', 'jellypress');?></h2>
        <?php
          get_search_form();
          the_widget( 'WP_Widget_Recent_Posts' );
        ?>
      </aside>
    </div>
  </div>
</section>
