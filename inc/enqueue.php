<?php
/**
 * Enqueue scripts and styles to theme
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if (! defined('FAVICON_DIR') ) {
    define('FAVICON_DIR', get_template_directory_uri() . '/dist/favicon');
}

if (! function_exists('jellypress_scripts') ) {
    /**
     * Enqueue scripts and styles.
     */
    function jellypress_scripts()
    {
        $theme_version = wp_get_theme()->get('Version'); // Get current version of theme
        $css_version = $theme_version . '.' . filemtime(get_template_directory() . '/style.css'); // Appends time stamp to help with cache busting
        $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/dist/js/site.min.js'); // Appends time stamp to help with cache busting

        // Enqueue Stylesheets
        wp_enqueue_style('jellypress-styles', get_stylesheet_uri(), array(), $css_version);
        wp_style_add_data('jellypress-styles', 'rtl', 'replace');
        //wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap', null, null, 'all');

        /**
         * Register Scripts but don't enqueue them until they are required.
         */

        wp_register_script(
          'jellypress-scripts',
          get_template_directory_uri() . '/dist/js/site.min.js',
          array('jquery'),
          $js_version,
          true
        );

      //  wp_register_script(
      //    'example-slider-plugin',
      //    get_template_directory_uri() . '/lib/example-slider-plugin.js',
      //    array(),
      //    $js_version,
      //    true
      //  );

        wp_register_script( 'youtube-api',
          '//www.youtube.com/iframe_api',
          array(),
          date('YW'),
          true
        );

        wp_register_script( 'vimeo-api',
          '//player.vimeo.com/api/player.js',
          array(),
          date('YW'),
          true
        );

        wp_register_script( 'aria-accordion',
          get_template_directory_uri() . '/lib/aria.accordion.min.js',
          array(),
          $js_version,
          true
        );

        wp_register_script(
          'magnific-popup',
          get_template_directory_uri() . '/lib/magnific-popup.min.js',
          array('jquery'),
          $js_version,
          true
        );

        wp_register_script(
          'splide-slider',
          get_template_directory_uri() . '/lib/splide.min.js',
          array(),
          $js_version,
          true
        );

        $get_gmaps_api = get_global_option('google_maps_api_key');
        if ($get_gmaps_api) {
          wp_register_script(
            'googlemaps',
            'https://maps.googleapis.com/maps/api/js?key='.$get_gmaps_api.'"',
            null,
            null,
            null
          );
        }

        /**
         * Enqueue Required scripts
         */
        wp_enqueue_script('jellypress-scripts');
        if (is_singular() && comments_open() && get_option('thread_comments') ) {
            wp_enqueue_script('comment-reply');
        }

        /**
         * Move jQuery to footer to reduce render-blocking
         */
        wp_scripts()->add_data('jquery', 'group', 1);
        wp_scripts()->add_data('jquery-core', 'group', 1);
        wp_scripts()->add_data('jquery-migrate', 'group', 1);
    }
}
add_action('wp_enqueue_scripts', 'jellypress_scripts');

if (! function_exists('jellypress_favicon_script') ) {
    /**
     * Hook Favicons into wp_head() using a range of sizes and specifications
     * TODO: Visit https://realfavicongenerator.net/ to generate the pack, replace the below with the latest standards as these often change
     */
    function jellypress_favicon_script()
    {
        echo '
    <link rel="apple-touch-icon" sizes="180x180" href="'.FAVICON_DIR.'/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="'.FAVICON_DIR.'/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="'.FAVICON_DIR.'/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="'.FAVICON_DIR.'/manifest.json">
    <link rel="mask-icon" href="'.FAVICON_DIR.'/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="'.FAVICON_DIR.'/favicon.ico">
    <meta name="msapplication-config" content="'.FAVICON_DIR.'/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    ';
    }
}
add_action('wp_head', 'jellypress_favicon_script');

if (! function_exists('jellypress_add_favicon_to_admin') ) {
    // Add favicon to admin areas
    function jellypress_add_favicon_to_admin()
    {
        $favicon_url = FAVICON_DIR.'/favicon.ico';
        echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
    }
}
add_action('login_head', 'jellypress_add_favicon_to_admin');
add_action('admin_head', 'jellypress_add_favicon_to_admin');

add_action('wp_head', 'jellypress_facebook_pixel');
if ( ! function_exists( 'jellypress_facebook_pixel' ) ) :
  function jellypress_facebook_pixel() {
    $facebook_pixel_api = get_global_option('facebook_pixel_id');
    if ($facebook_pixel_api) {
    // Note: This plugin might be useful later on ... https://github.com/seedorff/facebook-pixel-tracker-acf-edition/tree/master/inc
     ?>
    <!-- Facebook Pixel Code -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '<?php echo $facebook_pixel_api;?>');
      fbq('track', 'PageView');
      </script>
      <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=<?php echo $facebook_pixel_api;?>&ev=PageView&noscript=1"
      /></noscript>
    <!-- End Facebook Pixel Code -->
<?php  }
  }
endif;
