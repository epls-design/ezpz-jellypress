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
defined('ABSPATH') || exit;

$is_menu_off_canvas = true; // change this to determine the menu type
$theme_options = jellypress_get_acf_fields('60c219d0bd368', 'option');

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link rel="profile" href="https://gmpg.org/xfn/11">

  <?php
  // TODO: these are better hooking into wp_head. Can you add them to the theme-settings function?
  $custom_css = $theme_options['custom_css'];
  $font_links = $theme_options['font_links'];
  $header_picker = $theme_options['header_picker'];

  if ($font_links) echo $font_links;
  if ($custom_css) echo '<style>' . $custom_css . '</style>';

  // Changing the look of the header depending on what option they select

  $nav_logo = 'site-logo navbar-item';
  $nav_menu = 'navbar-menu';
  $nav_brand = 'navbar-brand site-branding';

  if ($header_picker == 'Option 2') {
    $nav_logo = $nav_logo . ' center-logo';
    $nav_menu = $nav_menu . ' center-menu';
    $nav_brand = $nav_brand . ' center-brand';
  }

  wp_head();

  ?>
</head>
<?php
if ($theme_options['translate_check']) {
?>
  <div class="translate-container-fixed">
    <?php echo do_shortcode('[gtranslate]'); ?>
  </div>
<?php
} ?>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <div id="page" class="site">

    <?php if (!is_page_template('page-simple.php')) : ?>

      <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'jellypress'); ?></a>

      <header id="masthead" class="site-header">
        <nav id="site-navigation" class="navbar main-navigation">
          <div class="container">
            <div class="<?php echo $nav_brand; ?>">

              <?php if ($logo_image = $theme_options['main_logo']) : ?>
                <a class="<?php echo $nav_logo ?>" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                  <?php echo wp_get_attachment_image($logo_image, 'site_logo'); ?>
                </a>
              <?php endif; ?>

              <button class="hamburger" type="button" aria-label="<?php _e('Toggles the website navigation', 'jellypress'); ?>" aria-controls="navbar-menu" aria-expanded="false">
                <span class="hamburger-box">
                  <span class="hamburger-inner"></span>
                </span>
              </button>
            </div>

            <?php if ($is_menu_off_canvas) : ?>
              <div id="navbar-menu" class="<?php echo $nav_menu; ?> is-off-canvas">
                <div class="navbar-top">
                  <button class="hamburger" type="button" aria-label="<?php _e('Toggles the website navigation', 'jellypress'); ?>" aria-controls="navbar-menu" aria-expanded="false">
                    <span class="hamburger-box">
                      <span class="hamburger-inner"></span>
                    </span>
                  </button>
                </div>
              <?php else : ?>
                <div id="navbar-menu" class="<?php echo $nav_menu; ?>">
                <?php endif; ?>
                <div class="navbar-end">
                  <?php
                  wp_nav_menu(array(
                    'theme_location' => 'menu-primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                  ));
                  ?>
                </div>
                </div>
              </div>
        </nav>
      </header>
    <?php endif; ?>
    <div id="content" class="site-content">
