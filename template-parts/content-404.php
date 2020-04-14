<?php
/**
 * Template part for displaying a 404 error (page not found)
 *
 * @package jellypress
 */

?>
<!-- TODO: Make this a more useful 404 with perhaps the most visited pages, blog posts etc -->
<section class="error-404 not-found">
  <div class="container">
    <div class="row">
      <div class="col md-8 lg-9">
        <header class="page-header">
          <h1 class="page-title"><?php esc_html_e( 'Page not found', 'jellypress' ); ?></h1>
        </header><!-- .page-header -->
        <div class="page-content">
          <p>
            <?php esc_html_e( 'It looks like nothing was found at this location. Can we help you find the page you were looking for?', 'jellypress' ); ?>
          </p>
          <p>
            <a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">Home Page</a>
            <button class="button__outline" onCLick="history.back()">Back to last page</button>
          </p>
        </div><!-- .page-content -->
      </div><!-- .col -->
      <aside class="col md-4 lg-3 sidebar">
        <h2>Search this website:</h2>
        <?php
          get_search_form();
          the_widget( 'WP_Widget_Recent_Posts' );
        ?>
      </aside>
    </div><!-- .row -->
  </div><!-- .container -->
</section><!-- .error-404 -->
