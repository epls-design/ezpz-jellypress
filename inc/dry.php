<?php
/**
 * DON'T REPEAT YOURSELF!!!!
 * Miscellaneous functions that reduce repetition in the theme code.
 * E.g. querying and printing the same fields from ACF
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'jellypress_display_cta_buttons' ) ) :
  /**
   * Loops through an array and displays buttons if the array is not empty
   * Uses data from ACF repeater field
   */
  function jellypress_display_cta_buttons($buttons) {
    if ( $buttons ) :
      echo '<div class="button-group">';
        foreach( $buttons as $button ) :

          // Default button class and get variables from ACF
          $button_classes = 'button';
          $button_link = $button['button_link'];
          $button_style = $button['button_style'];

          if($button_style!='filled') {
            // 'filled' is the default state so we don't need a class for this
            $button_classes.= ' '.$button_style;
          };

          echo '<a class="'.$button_classes.'" href="'.$button_link['url'].'" target="'.$button_link['target'].'">'.$button_link['title'].'</a>';
        endforeach;
      echo '</div>';
    endif;
  }
endif;

if ( ! function_exists( 'jellypress_display_map_markers' ) ) :
  /**
   * Renders Markers onto a map element using data from ACF
   */
  function jellypress_display_map_markers($locations) {
    if($locations):

      wp_enqueue_script('googlemaps');

      echo '<div class="google-map">';
      foreach($locations as $location):

        if ( $location_group = $location['location_group'] ) :
            $location_marker = $location_group['location_marker'];
            $location_icon = $location_group['location_icon'];
        endif;

        if ( $tooltip_information = $location['tooltip_information'] ) :
            $location_tooltip_text = $tooltip_information['location_tooltip_text'];
            $display_address = $tooltip_information['display_address'];
        endif;

        if($location_icon) {
          // Add an icon if the user has specified one
          $thumb = wp_get_attachment_image_src($location_icon,'icon');
          $data_icon = 'data-icon="'.$thumb[0].'"'; // Returns an array so get the first value
        }
        else {
          $data_icon = '';
        }

        // Construct Address HTML with valid schema.
        $address = '<div class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">';
          if($location_marker['street_number'] || $location_marker['street_name']) $address .= '<span itemprop="streetAddress">'.$location_marker['street_number'].' '.$location_marker['street_name'].'</span><br/>';
          if($location_marker['city']) $address .= '<span itemprop="addressLocality">'.$location_marker['city'].'</span><br/>';
          if($location_marker['state']) $address .= '<span itemprop="addressRegion">'.$location_marker['state'].'</span><br/>';
          if($location_marker['post_code']) $address .= '<span itemprop="postalCode">'.$location_marker['post_code'].'</span><br/>';
          if($location_marker['country']) $address .= '<span itemprop="addressCountry">'.$location_marker['country'].'</span>';
        $address .= '</div>';

        if($location_tooltip_text || $display_address == 1) :
          echo '<div class="marker" data-lat="'.$location_marker['lat'].'" data-lng="'.$location_marker['lng'].'" '.$data_icon.'>';
            if($location_tooltip_text)
              jellypress_content($location_tooltip_text);
            if($display_address == 1) {
              echo $address;
            }
          echo '</div>';
        else: // Needs to have no white space or an empty tooltip will render
          echo '<div class="marker" data-lat="'.$location_marker['lat'].'" data-lng="'.$location_marker['lng'].'" '.$data_icon.'></div>';
        endif;

      endforeach;
      echo '</div>';
    endif; // if ($locations)
    }
endif;

if ( ! function_exists( 'jellypress_display_socials' ) ) :
  /**
   * Loops through an ACF repeater field to display the social media channels
   * this organisation is on.
   */
  function jellypress_display_socials() {
    if ( have_rows( 'social_channels', 'option' ) ) :
      $social_links_formatted = '<ul class="social-channels">';
      while ( have_rows( 'social_channels', 'option' ) ) : the_row();
        $socialNetwork = get_sub_field( 'network' );
        $socialUrl = get_sub_field( 'url' );
        $social_links_formatted.= '<li class="social-icon"><a href="'.$socialUrl.'" rel="noopener">'.jellypress_icon($socialNetwork).'</a></li>';
      endwhile;
      $social_links_formatted .= '</ul>';
    endif;
    return $social_links_formatted;
  }
  add_shortcode('jellypress-socials', 'jellypress_display_socials');
endif;

if ( ! function_exists( 'jellypress_display_email' ) ) :
  /**
   * Displays the organisation's email address from ACF in a robot-obscuring way
   */
  function jellypress_display_email() {
    $email_address = get_field( 'email_address', 'option' );
    return jellypress_hide_email($email_address);
  }
  add_shortcode('jellypress-email', 'jellypress_display_email'); // TODO: Extend this to allow the user to pass an email address to the shortcode
endif;

if ( ! function_exists( 'jellypress_display_address' ) ) :
  /**
   * Displays the organisation's information from ACF Options Page
   */
  function jellypress_display_address() {
    $address = '<div class="postal-address"><span class="screen-reader-text" itemprop="name">'.get_bloginfo('name').__(' Postal Address','jellypress').'</span>';
    if($address_street = get_field( 'address_street', 'option' )) $address .= '<span>'.$address_street.'</span>';
    if($address_locality = get_field( 'address_locality', 'option' )) $address .= '<span>'.$address_locality.'</span>';
    if($address_region = get_field( 'address_region', 'option' )) $address .= '<span>'.$address_region.'</span>';
    if($address_country = get_field( 'address_country', 'option' )) $address .= '<span>'.$address_country.'</span>';
    if($address_postal = get_field( 'address_postal', 'option' )) $address .= '<span>'.$address_postal.'</span>';
    $address .= '</div>';
    return $address;
  }
  add_shortcode('jellypress-address', 'jellypress_display_address');
endif;

if ( ! function_exists( 'jellypress_display_phone_number' ) ) :
  /**
   * Displays the organisation's phone number from ACF
   *
   * @return void
   */
  function jellypress_display_phone_number() {
    $phone_number = get_field( 'primary_phone_number', 'option' );
    $country_code = '+44';

    if($phone_number[0] != '0') {
      // Add leading 0 back in for visual display purposes
      $phone_number = '0'.$phone_number;
    }
    $display_number = esc_attr(preg_replace("/[^0-9 ]/", "", $phone_number )); // Strip all unwanted characters
    $link_number = str_replace( array (' ', '(0)'), '', $display_number[0] === '0' ? $country_code . ltrim( $display_number, '0' ) : $display_number );

    return '<div class="telephone-number"><span class="bold">'.__('Telephone:','jellypress').'</span> <a href="tel:'.$link_number.'">'.$display_number.'</a></div>';
  }
  add_shortcode('jellypress-phone', 'jellypress_display_phone_number');
endif;

if ( ! function_exists( 'jellypress_display_opening_hours' ) ) :
  /**
   * Displays the organisation's opening hours in a formatted table
   */
  function jellypress_display_opening_hours() {
    if ( have_rows( 'opening_hours', 'option' ) ) :
      $opening_hours_formatted = '<table class="opening-hours"><thead><tr><th>'.__('Day(s)','jellypress').'</th><th>'.__('Opening Hours','jellypress').'</th></tr></thead><tbody>';
      while ( have_rows( 'opening_hours', 'option' ) ) : the_row();
        $closed = get_sub_field('closed');
        $from   = get_sub_field('from');
        $to     = get_sub_field('to');
        if($closed) {
          $opening_hours = __('Closed', 'jellypress');
        }
        else {
          $opening_hours = $from.' - '.$to;
        }
        $days = implode(", ", get_sub_field('days')); // Split into a comma sep string
        $days = str_replace(array('Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'), array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), $days); // Replace with actual words
        $opening_hours_formatted .= '<tr><td>'.$days.'</td><td>'.$opening_hours.'</td></tr>';
      endwhile;
      $opening_hours_formatted .= '</tbody></table>';
    endif;
    return $opening_hours_formatted;
  }
  add_shortcode('jellypress-opening', 'jellypress_display_opening_hours');
endif;

if ( ! function_exists( 'jellypress_display_departments' ) ) :
  /**
   * Loops through ACF repeater to display the organisation's
   * departments with contact information
   */
  function jellypress_display_departments() {
    if ( have_rows( 'departments', 'option' ) ) :
      $phone_numbers_formatted = '<table class="department-contacts"><thead><tr><th>'.__('Department','jellypress').'</th><th>'.__('Phone Number','jellypress').'</th><th>'.__('Email Address','jellypress').'</th></tr></thead><tbody>';
      while ( have_rows( 'departments', 'option' ) ) : the_row();
        $department = get_sub_field('department');
        $phone_number   = get_sub_field('phone_number');
        $email_address     = get_sub_field('email_address');
        if($phone_number[0] != '0') {
          // Add leading 0 back in for visual display purposes
          $phone_number = '0'.$phone_number;
        }
        $country_code = '+44';
        $display_number = esc_attr(preg_replace("/[^0-9 ]/", "", $phone_number )); // Strip all unwanted characters
        $link_number = str_replace( array (' ', '(0)'), '', $display_number[0] === '0' ? $country_code . ltrim( $display_number, '0' ) : $display_number );

        $phone_numbers_formatted .= '<tr><td class="bold">'.$department.'</td><td><a href="tel:'.$link_number.'">'.$display_number.'</a></td><td>'.jellypress_hide_email($email_address).'</td></tr>';
      endwhile;
      $phone_numbers_formatted .= '</tbody></table>';
    endif;
    return $phone_numbers_formatted;
  }
  add_shortcode('jellypress-departments', 'jellypress_display_departments');
endif;

// TODO: Make the telephone numbers into functions to make them more Dry
