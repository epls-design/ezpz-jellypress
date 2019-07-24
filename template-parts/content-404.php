<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

?>
<!-- TODO: Make this a more useful 404 with perhaps the most visited pages, blog posts etc -->
<section class="error-404 not-found">
    <div class="container">
        <div class="row">
            <div class="col">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'jellypress' ); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <p><?php esc_html_e( 'It looks like nothing was found at this location.', 'jellypress' ); ?></p>
                <p><a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">Return to the home page</a></p>
                <?php
                get_search_form();
                ?>

            </div><!-- .page-content -->
            </div>
        </div>
    </div>
</section><!-- .error-404 -->
