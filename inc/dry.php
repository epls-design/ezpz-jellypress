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

if (!function_exists('jellypress_display_email')) :
  /**
   * Use a shortcode to display an email address in a robot-obscuring way
   * If using php it's recommend to instead use jellypress_hide_email() as this
   * function simply acts as a wrapper.
   *
   * @param array $atts An array of shortcode attributes (valid: show_icon boolean, email string)
   * @param boolean $icon Whether to show an icon or not
   * @return string The Formatted Email Address
   */
  function jellypress_display_email($atts = null, $icon = true)
  {
    $args = shortcode_atts(array(
      'show_icon' => $icon,
      'email' => get_global_option('email_address') // Defaults to the email address saved in the options page
    ), $atts, 'jellypress-email');
    $args['show_icon'] = filter_var($args['show_icon'], FILTER_VALIDATE_BOOLEAN);

    $show_icon = $args['show_icon'];
    $email_address = $args['email'];

    $show_icon == true ? $return = jellypress_hide_email($email_address, true) : $return = jellypress_hide_email($email_address);

    if ($email_address) return $return;
  }
  add_shortcode('jellypress-email', 'jellypress_display_email');
endif;

if (!function_exists('jellypress_display_address')) :
  /**
   * Display postal address information from ACF options page
   * with the option to display an icon
   *
   * @param array $atts Array of options passed from the shortcode. Accepts one attr show_icon boolean
   * @param boolean $icon Whether to display a preceding icon (useful if calling this function from php directly)
   * @return string Formatted HTML address
   */
  function jellypress_display_address($atts = null, $icon = false)
  {
    $args = shortcode_atts(array(
      'show_icon' => $icon
    ), $atts, 'jellypress-address');
    $args['show_icon'] = filter_var($args['show_icon'], FILTER_VALIDATE_BOOLEAN);
    $show_icon = $args['show_icon'];

    // If show icon is true, define the icons to return else make them empty strings
    $icon = $show_icon == true ? jellypress_icon('location') : '';

    if ($address_street = get_global_option('address_street')) $address = '<span>' . $address_street . '</span>';
    if ($address_locality = get_global_option('address_locality')) $address .= '<span>' . $address_locality . '</span>';
    if ($address_region = get_global_option('address_region')) $address .= '<span>' . $address_region . '</span>';
    if ($address_country = get_global_option('address_country')) $address .= '<span>' . $address_country . '</span>';
    if ($address_postal = get_global_option('address_postal')) $address .= '<span>' . $address_postal . '</span>';

    if (isset($address)) {
      $address = '<div class="postal-address">' . $icon . '<div><span class="screen-reader-text" itemprop="name">' . get_bloginfo('name') . __(' Postal Address', 'jellypress') . '</span>' . $address . '</div></div>';
      return $address;
    }
  }
  add_shortcode('jellypress-address', 'jellypress_display_address');
endif;

/**
 * A function which takes a phone number string and country code, removes all spaces and non-numeric characters in the phone number and adds the dialing code to the front
 *
 * @param string $telephone_number
 * @param string $country_code
 * @return string Formatted Number
 */
if (!function_exists('jellypress_append_country_dialing_code')) :
  function jellypress_append_country_dialing_code($telephone_number, $country_code = null)
  {

    $cleansed_number = esc_attr(preg_replace("/[^0-9 ]/", "", $telephone_number)); // Strip all unwanted characters

    if ($country_code != '' && $country_code != null) {
      $country_code = '+' . $country_code;
      // Replace leading 0 with country code or insert if it doesn't exist
      $formatted_phone_number = str_replace(array(' ', '(0)'), '', $cleansed_number[0] === '0' ? $country_code . ltrim($cleansed_number, '0') : $country_code . $cleansed_number);
    } else {
      $formatted_phone_number = str_replace(' ', '', $cleansed_number); // Strip spaces
    }

    return $formatted_phone_number;
  }
endif;

if (!function_exists('jellypress_display_phone_number')) :
  /**
   * Displays the organisations phone number, taken from the ACF options page
   *
   * @param array $atts Array of atts from shortcode. Accepts one att show_icon boolean
   * @param boolean $icon Whether or not to show the icon. Useful if invoking this function from php directly.
   * @return string Formatted telephone number with UK +44 prefix in the anchor link
   */
  function jellypress_display_phone_number($atts = null, $icon = false)
  {
    $args = shortcode_atts(array(
      'show_icon' => $icon
    ), $atts, 'jellypress-phone');
    $args['show_icon'] = filter_var($args['show_icon'], FILTER_VALIDATE_BOOLEAN);
    $show_icon = $args['show_icon'];

    // If show icon is true, define the icons to return else make them empty strings
    $icon = $show_icon == true ? jellypress_icon('phone') : '';

    $phone_number = get_global_option('primary_phone_number');
    $link_number = jellypress_append_country_dialing_code($phone_number, get_global_option('dialing_code'));

    if ($phone_number) return '<span class="telephone-number"><span class="bold">' . $icon . __('Telephone:', 'jellypress') . '</span> <a href="tel:' . $link_number . '" rel="nofollow">' . $phone_number . '</a></span>';
  }
  add_shortcode('jellypress-phone', 'jellypress_display_phone_number');
endif;

if (!function_exists('jellypress_display_opening_hours')) :
  /**
   * Displays the organisations opening hours, by looping through a repeater on the ACF options page
   *
   * @param array $atts Atts from the shortcode. Accepts one att show_icons boolean
   * @param boolean $icon Whether to show an icon. Useful if invoking this function from php
   * @return string Formatted HTML table of opening hours.
   */
  function jellypress_display_opening_hours($atts = null, $icon = false)
  {
    $args = shortcode_atts(array(
      'show_icons' => $icon
    ), $atts, 'jellypress-opening');
    $args['show_icons'] = filter_var($args['show_icons'], FILTER_VALIDATE_BOOLEAN);
    $show_icons = $args['show_icons'];

    // If show icon is true, define the icons to return else make them empty strings
    $days_icon = $show_icons == true ? jellypress_icon('calendar') : '';
    $times_icon = $show_icons == true ? jellypress_icon('clock') : '';

    if (have_rows('opening_hours', 'option')) :
      $opening_hours_formatted = '<table class="opening-hours"><thead><tr><th>' . $days_icon . __('Day(s)', 'jellypress') . '</th><th>' . $times_icon . __('Opening Hours', 'jellypress') . '</th></tr></thead><tbody>';
      while (have_rows('opening_hours', 'option')) : the_row();
        $closed = get_sub_field('closed');
        $from   = get_sub_field('from');
        $to     = get_sub_field('to');
        if ($closed) {
          $opening_hours = __('Closed', 'jellypress');
        } else {
          $opening_hours = $from . ' - ' . $to;
        }
        $days = implode(", ", get_sub_field('days')); // Split into a comma sep string
        $days = str_replace(array('Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'), array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'sunday'), $days); // Replace with actual words
        $opening_hours_formatted .= '<tr><td>' . $days . '</td><td>' . $opening_hours . '</td></tr>';
      endwhile;
      $opening_hours_formatted .= '</tbody></table>';
      return $opening_hours_formatted;
    endif;
  }
  add_shortcode('jellypress-opening', 'jellypress_display_opening_hours');
endif;

if (!function_exists('jellypress_display_departments')) :
  /**
   * Displays an organisation's additional contact information based on data
   * from an ACF options page.
   *
   * @param array $atts Array of atts from shortcode. Accepts one att show_icons boolean
   * @param boolean $icon Whether to show the icons or not. Useful if invoking from php.
   * @return string Formatted HTML table
   */
  function jellypress_display_departments($atts = null, $icon = false)
  {
    $args = shortcode_atts(array(
      'show_icons' => $icon
    ), $atts, 'jellypress-departments');
    $args['show_icons'] = filter_var($args['show_icons'], FILTER_VALIDATE_BOOLEAN);
    $show_icons = $args['show_icons'];

    // If show icon is true, define the icons to return else make them empty strings
    $depts_icon = $show_icons == true ? jellypress_icon('department') : '';
    $phone_icon = $show_icons == true ? jellypress_icon('phone') : '';
    $email_icon = $show_icons == true ? jellypress_icon('email') : '';

    if (have_rows('departments', 'option')) :
      $phone_numbers_formatted = '<table class="department-contacts"><thead><tr><th>' . $depts_icon . __('Department', 'jellypress') . '</th><th>' . $phone_icon . __('Phone Number', 'jellypress') . '</th><th>' . $email_icon . __('Email Address', 'jellypress') . '</th></tr></thead><tbody>';
      while (have_rows('departments', 'option')) : the_row();
        $department = get_sub_field('department');
        $phone_number   = get_sub_field('phone_number');
        $email_address     = get_sub_field('email_address');

        $link_number = jellypress_append_country_dialing_code($phone_number, get_sub_field('dialing_code'));

        $phone_numbers_formatted .= '<tr><td class="bold">' . $department . '</td><td><a href="tel:' . $link_number . '" rel="nofollow">' . $phone_number . '</a></td><td>' . jellypress_hide_email($email_address) . '</td></tr>';
      endwhile;
      $phone_numbers_formatted .= '</tbody></table>';
      return $phone_numbers_formatted;
    endif;
  }
  add_shortcode('jellypress-departments', 'jellypress_display_departments');
endif;
