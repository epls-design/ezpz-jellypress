<?php
/**
 * Functions which hook into ACF to add additional functionality to the website.
 *
 * @package jellypress
 */

/**
  * Adds an ACF options page for business information
  * TODO: Remove if not required by your theme and also remove the ACF field group in the front-end
  */
  if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(
      array(
        'page_title' 	=> 'Business Information',
        'menu_title'	=> 'Business Info',
        'menu_slug' 	=> 'business-information',
        'capability'	=> 'edit_posts',
        'icon_url' => 'dashicons-info',
        'position' => 20
      ));
  }

/**
 * Hides ACF settings on the live site. Settings will still be available locally.
 * This snippet is used because we are using ACF Json to manage field groups and keep them in sync
 * When pulling the database from the live site (eg. with WP Migrate DB), it is necessary
 * to sync the json back in - as the production site should never have the ACF fields
 * stored in the database (only the local site will have this)
 * @link https://www.awesomeacf.com/snippets/hide-the-acf-admin-menu-item-on-selected-sites/
 * @link https://support.advancedcustomfields.com/forums/topic/the-acf-json-workflow/
 */
function jellypress_hide_acf_admin() {
  // get the current site url
  $site_url = get_bloginfo( 'url' );
  // an array of protected site urls
  $protected_urls = array(
      'http://YOURURL.com', // TODO: Change for your live project
      'https://YOURURL.com', // TODO: Change for your live project
  );
  // check if the current site url is in the protected urls array
  if ( in_array( $site_url, $protected_urls ) ) {
      // hide the acf menu item
      return false;
  } else {
      // show the acf menu item
      return true;
  }
}

add_filter('acf/settings/show_admin', 'jellypress_hide_acf_admin');

/**
 * Sets up GAnalytics if the user has added a Google Tag ID to the options page
 */

function jellypress_google_tag_manager(){
  $get_gtag_id = get_field('gtag_id', 'option');
  if ($get_gtag_id) {
    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $get_gtag_id;?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo $get_gtag_id;?>');
    </script>
    <?php
    }
  }
add_action( 'wp_head', 'jellypress_google_tag_manager', 100 );

/**
 * Adds Google Maps API Key if the user has added one to the options page
 */

function jellypress_google_maps_api_key() {
  $get_gmaps_api = get_field('google_maps_api_key', 'option');
  if ($get_gmaps_api) {
  acf_update_setting('google_api_key', $get_gmaps_api);
  }
}
add_action('acf/init', 'jellypress_google_maps_api_key');

/**
 * Adds Dashicons to ACF allowing us to use them in labels etc.
 * Usage: <span class="dashicons dashicons-menu"></span>
 */

function jellypress_acf_dashicons_support() {
  wp_enqueue_style( 'dashicons' );
}

add_action( 'admin_init', 'jellypress_acf_dashicons_support' );

/**
 * Include ACF Color Swatch Field
 * Makes the Section BG look a lot nicer and more intuitive for the user
 * @link https://github.com/nickforddesign/acf-swatch
 */

add_filter('acf/swatch_settings/path', 'jellypress_acf_swatch_path', 10, 1);

function jellypress_acf_swatch_path( $path ) {

  $path = get_template_directory() . '/inc/acf-swatch';

  return $path;

}

add_filter('acf/swatch_settings/url', 'jellypress_acf_swatch_url', 10, 1);

function jellypress_acf_swatch_url( $url ) {

  $url = get_template_directory_uri() . '/inc/acf-swatch';

  return $url;

}

include( 'acf-swatch/acf-swatch.php' );
