<?php
/**
 * Declare widgets
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * TODO: Remove this file if not required by your theme
 */

if ( ! function_exists( 'jellypress_widgets_init' ) ) {
  function jellypress_widgets_init() {
    register_sidebar( array(
      'name'          => esc_html__( 'Sidebar', 'jellypress' ),
      'id'            => 'default-sidebar',
      'description'   => esc_html__( 'Add your widgets here.', 'jellypress' ),
      'before_widget' => '<section id="%1$s" class="widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h2 class="widget-title">',
      'after_title'   => '</h2>',
    ) );
  }
}
add_action( 'widgets_init', 'jellypress_widgets_init' );
