<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div id="page" class="site">

<?php if ( !is_page_template( 'page-simple.php' ) ) : ?>

  <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'jellypress' ); ?></a>

    <header id="masthead" class="site-header">
      <nav id="site-navigation" class="navbar main-navigation">
        <div class="container">
          <div class="navbar-brand site-branding">

            <span class="site-title navbar-item" style="display:block">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
              <?php
                $jellypress_description = get_bloginfo( 'description', 'display' );
                if ( $jellypress_description || is_customize_preview() ) : ?>
                <br/><span class="site-description"><?php echo $jellypress_description; ?></span>
              <?php endif; ?>
            </span>
            <!-- EXAMPLE OF EMBEDDING CLIENT LOGO -->
            <!--<a class="site-logo navbar-item" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
              <?php _e('<img src="'.get_stylesheet_directory_uri().'/dist/img/client-logo.svg'.'" alt="'.get_bloginfo( 'description', 'display' ).'">', 'jellypress'); ?>
            </a>-->

            <button class="hamburger" type="button" aria-label="Menu" aria-controls="navbar-menu" aria-expanded="false">
              <span class="hamburger-label">Menu</span>
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
            </button>
          </div>
          <div id="navbar-menu" class="navbar-menu">
            <div class="navbar-start">
              <?php
                wp_nav_menu( array(
                  'theme_location' => 'menu-primary',
                  'menu_id'        => 'primary-menu',
                  'container'      => false,
                ) );
              ?>
            </div>
            <div class="navbar-end">
              <a href="#" class="button secondary">Example Button</a>
              <?php if ( class_exists( 'woocommerce' ) ) jellypress_woocommerce_header_cart(); ?>
            </div>
          </div>
        </div>
      </nav>
    </header>
<?php endif; ?>
    <div id="content" class="site-content">
