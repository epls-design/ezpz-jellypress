<?php

/**
 * Functions necessary for the map block
 * TODO: Eventually it would be good to move to Leaflet maps instead of Google, as it's better for privacy
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Adds ACF options pages
 */
if (function_exists('acf_add_options_page')) {
  add_action('init', function () {
    // If Options Page doesnt exist, create it...
    $pages = acf_get_options_pages();
    if (!key_exists('theme-options', $pages)) {
      acf_add_options_page(
        array(
          'page_title'   => __('Theme Options', 'jellypress'),
          'menu_title'  => __('Theme Options', 'jellypress'),
          'menu_slug'   => 'theme-options',
          'capability'  => 'edit_posts',
          //'redirect'    => true,
          'icon_url' => 'dashicons-info',
          'position' => 90,
          'autoload' => true, // Speeds up load times
          'updated_message' => __("Successfully updated Theme options", 'jellypress'),
        )
      );
    }
  });
}

add_action('acf/include_fields', function () {
  if (!function_exists('acf_add_local_field_group')) {
    return;
  }

  acf_add_local_field_group(array(
    'key' => 'group_606724c228c08',
    'title' => __('Maps API Key', 'jellypress'),
    'fields' => array(
      array(
        'key' => 'field_606724c578b1f',
        'label' => __('Google Maps API Key', 'jellypress'),
        'name' => 'google_maps_api_key',
        'aria-label' => '',
        'type' => 'text',
        'instructions' => __('To display Google Maps on your website, you need an API key. Please add it here.', 'jellypress'),
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '33',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'options_page',
          'operator' => '==',
          'value' => 'theme-options',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
    'modified' => 1685014137,
  ));
});

/**
 * Renders Markers onto a map element using data from ACF
 */
function jellypress_display_map_markers($locations) {
  if ($locations) :

    wp_enqueue_script('googlemaps-init');

    echo '<div class="google-map">';
    foreach ($locations as $location) :

      if ($location_group = $location['location_group']) :
        $location_marker = $location_group['location_marker'];
        $location_icon = $location_group['location_icon'];
      endif;

      if ($tooltip_information = $location['tooltip_information']) :
        $location_tooltip_text = $tooltip_information['location_tooltip_text'];
        $display_address = $tooltip_information['display_address'];
      endif;

      if ($location_icon) {
        // Add an icon if the user has specified one
        $thumb = wp_get_attachment_image_src($location_icon, 'icon');
        $data_icon = 'data-icon="' . $thumb[0] . '"'; // Returns an array so get the first value
      } else {
        $data_icon = '';
      }

      // Construct Address HTML with valid schema.
      $address = '<div class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">';
      if ($location_marker['street_number'] || $location_marker['street_name']) $address .= '<span itemprop="streetAddress">' . $location_marker['street_number'] . ' ' . $location_marker['street_name'] . '</span><br/>';
      if ($location_marker['city']) $address .= '<span itemprop="addressLocality">' . $location_marker['city'] . '</span><br/>';
      if ($location_marker['state']) $address .= '<span itemprop="addressRegion">' . $location_marker['state'] . '</span><br/>';
      if ($location_marker['post_code']) $address .= '<span itemprop="postalCode">' . $location_marker['post_code'] . '</span><br/>';
      if ($location_marker['country']) $address .= '<span itemprop="addressCountry">' . $location_marker['country'] . '</span>';
      $address .= '</div>';

      if ($location_tooltip_text || $display_address == 1) :
        echo '<div class="marker" data-lat="' . $location_marker['lat'] . '" data-lng="' . $location_marker['lng'] . '" ' . $data_icon . '>';
        if ($location_tooltip_text)
          echo $location_tooltip_text;
        if ($display_address == 1) {
          echo $address;
        }
        echo '</div>';
      else : // Needs to have no white space or an empty tooltip will render
        echo '<div class="marker" data-lat="' . $location_marker['lat'] . '" data-lng="' . $location_marker['lng'] . '" ' . $data_icon . '></div>';
      endif;

    endforeach;
    echo '</div>';
  endif; // if ($locations)
}

/**
 * Adds Google Maps API Key if the user has added one to the options page
 */
add_action('acf/init', 'jellypress_google_maps_api_key');
function jellypress_google_maps_api_key() {
  $get_gmaps_api = get_global_option('google_maps_api_key');
  if ($get_gmaps_api) {
    acf_update_setting('google_api_key', $get_gmaps_api);
  }
}

add_action('wp_enqueue_scripts', function () {

  $get_gmaps_api = get_global_option('google_maps_api_key');
  if ($get_gmaps_api) {
    wp_register_script(
      'googlemaps',
      'https://maps.googleapis.com/maps/api/js?key=' . $get_gmaps_api . '&libraries=marker',
      null,
      null,
      null
    );
  }

  wp_register_script(
    'googlemaps-init',
    get_template_directory_uri() . '/template-parts/blocks/map/embed-google-maps.js',
    array('jquery', 'googlemaps'),
    filemtime(get_template_directory() . '/template-parts/blocks/map/embed-google-maps.js'),
    true
  );
});
