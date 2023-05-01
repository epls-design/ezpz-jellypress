<?php

/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 * @link https://github.com/woocommerce/woocommerce/wiki/Declaring-WooCommerce-support-in-themes
 *
 * @return void
 */
add_action('after_setup_theme', 'jellypress_woocommerce_setup');
function jellypress_woocommerce_setup() {
  add_theme_support(
    'woocommerce',
    array(
      'thumbnail_image_width' => 150,
      'single_image_width'    => 300,
      'product_grid'          => array(
        'default_rows'    => 3,
        'min_rows'        => 1,
        'default_columns' => 4,
        'min_columns'     => 1,
        'max_columns'     => 6,
      ),
    )
  );
  add_theme_support('wc-product-gallery-zoom');
  add_theme_support('wc-product-gallery-lightbox');
  add_theme_support('wc-product-gallery-slider');
}

/**
 * Enqueue Woocommerce overrides.
 * @return void
 */
add_action('wp_enqueue_scripts', 'jellypress_woocommerce_scripts');
function jellypress_woocommerce_scripts() {
  $theme_version = wp_get_theme()->get('Version'); // Get current version of theme
  $wc_version = $theme_version . '.' . filemtime(get_template_directory() . '/dist/woocommerce.min.css'); // Appends time stamp to help with cache busting
  wp_enqueue_style('jellypress-woocommerce-style', get_template_directory_uri() . '/dist/woocommerce.min.css', array(), $wc_version);
}

/**
 * Uncomment to disable WooCommerce default styles, for debugging
 */
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Remove woocommerce class from body - this can cause issues with buttons etc in the header
 */
add_filter('body_class', function (array $classes) {
  if (in_array('woocommerce', $classes)) {
    unset($classes[array_search('woocommerce', $classes)]);
  }
  return $classes;
});

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
add_filter('body_class', 'jellypress_woocommerce_active_body_class');
function jellypress_woocommerce_active_body_class($classes) {
  $classes[] = 'woocommerce-active';
  return $classes;
}

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
add_filter('woocommerce_output_related_products_args', 'jellypress_woocommerce_related_products_args');
function jellypress_woocommerce_related_products_args($args) {
  $defaults = array(
    'posts_per_page' => 3,
    'columns'        => 3,
  );

  $args = wp_parse_args($defaults, $args);

  return $args;
}

/**
 * Remove default WooCommerce wrapper.
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Before Content.
 * Wraps all WooCommerce content in wrappers which match the theme markup.
 *
 * @return void
 */
add_action('woocommerce_before_main_content', 'jellypress_woocommerce_wrapper_before');
function jellypress_woocommerce_wrapper_before() {
  echo '<div id="primary" class="content-area woocommerce"><main id="main" class="site-main block bg-white"><div class="container"><div class="row"><div class="col">';
}

/**
 * After Content.
 * Closes the wrapping divs.
 *
 * @return void
 */
add_action('woocommerce_after_main_content', 'jellypress_woocommerce_wrapper_after');
function jellypress_woocommerce_wrapper_after() {
  echo '</div></div></div></main></div>';
}

/**
 * Cart Fragments.
 *
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
add_filter('woocommerce_add_to_cart_fragments', 'jellypress_woocommerce_cart_link_fragment');
function jellypress_woocommerce_cart_link_fragment($fragments) {
  ob_start();
  jellypress_woocommerce_cart_link();
  $fragments['a.cart-contents'] = ob_get_clean();
  return $fragments;
}

/**
 * Cart Link.
 *
 * Displayed a link to the cart including the number of items present and the cart total.
 *
 * @return void
 */
function jellypress_woocommerce_cart_link() {
?>
  <a href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'jellypress'); ?>">
    <?php
    $item_count_text = sprintf(
      /* translators: number of items in the mini cart. */
      _n('%d item', '%d items', WC()->cart->get_cart_contents_count(), 'jellypress'),
      WC()->cart->get_cart_contents_count()
    );
    ?>
    <?php echo jellypress_icon('cart'); ?>
    <span class="cart-contents">
      <span class="amount"><?php echo wp_kses_data(WC()->cart->get_cart_subtotal()); ?></span> <span class="count"><?php echo esc_html($item_count_text); ?></span>
    </span>
  </a>
<?php
}

/**
 * Display Header Cart.
 *
 * @return void
 */
function jellypress_woocommerce_header_cart() {
  if (is_cart()) {
    $class = 'current-menu-item';
  } else {
    $class = '';
  }
?>
  <ul id="site-header-cart" class="site-header-cart">
    <li class="<?php echo esc_attr($class); ?>">
      <?php jellypress_woocommerce_cart_link(); ?>
    </li>
    <li>
      <?php
      $instance = array(
        'title' => 'Your Basket',
      );
      the_widget('WC_Widget_Cart', $instance);
      ?>
    </li>
  </ul>
<?php
}

/**
 * Disables the sidebar on WooCommerce pages
 */
add_action('init', 'jellypress_disable_woocommerce_sidebar');
function jellypress_disable_woocommerce_sidebar() {
  remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
}
