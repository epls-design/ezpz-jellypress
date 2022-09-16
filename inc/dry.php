<?php

/**
 * DON'T REPEAT YOURSELF!!!!
 * Miscellaneous functions that reduce repetition in the theme code.
 * E.g. querying and printing the same fields from ACF
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (!function_exists('jellypress_display_cta_buttons')) :
  /**
   * Loops through an array and displays buttons if the array is not empty
   * Uses data from ACF repeater field
   */
  function jellypress_display_cta_buttons($buttons, $classes = null)
  {
    if ($buttons) :
      if (isset($classes)) echo '<div class="button-list ' . $classes . '">';
      else echo '<div class="button-list">';
      foreach ($buttons as $button) :

        // Default button class and get variables from ACF
        $button_classes = 'button';
        $button_link = $button['button_link'];
        $button_color = $button['button_color'];
        $button_style = $button['button_style'];

        if ($button_color != 'default') {
          $button_classes .= ' ' . $button_color;
        }

        if ($button_style != 'filled') {
          // 'filled' is the default state so we don't need a class for this
          $button_classes .= ' ' . $button_style;
        };

        // Check if the URL is external, if so add a rel="external"
        $button_url = parse_url($button_link['url']);
        $blog_url = parse_url(get_bloginfo('url'));
        if ($button_url['host'] == $blog_url['host']) {
          echo '<a class="' . $button_classes . '" href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" target="' . $button_link['target'] . '">' . $button_link['title'] . '</a>';
        } else {
          // Outbound link - add rel=external
          echo '<a class="' . $button_classes . '" href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" target="' . $button_link['target'] . '" rel="external">' . $button_link['title'] . '</a>';
        }
      endforeach;
      echo '</div>';
    endif;
  }
endif;

if (!function_exists('jellypress_display_map_markers')) :
  /**
   * Renders Markers onto a map element using data from ACF
   */
  function jellypress_display_map_markers($locations)
  {
    if ($locations) :

      wp_enqueue_script('googlemaps');

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
            echo jellypress_content($location_tooltip_text);
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
endif;

if (!function_exists('jellypress_display_socials')) :
  /**
   * Loop through ACF repeater in the options page to display
   * the organisation's social media channels in an icon list
   *
   * @return string Formatted HTML list of icons with anchor links
   */
  function jellypress_display_socials()
  {
    if (have_rows('social_channels', 'option')) :
      $social_links_formatted = '<ul class="social-channels">';
      while (have_rows('social_channels', 'option')) : the_row();
        $socialnetwork = get_sub_field('network');
        $socialUrl = get_sub_field('url');
        $social_links_formatted .= '<li class="social-icon"><a href="' . $socialUrl . '" rel="noopener" title="' . __('Visit us on ', 'jellypress') . ucfirst($socialnetwork) . ' ">' . jellypress_icon($socialnetwork) . '</a></li>';
      endwhile;
      $social_links_formatted .= '</ul>';
      return $social_links_formatted;
    endif;
  }
  add_shortcode('jellypress-socials', 'jellypress_display_socials');
endif;
