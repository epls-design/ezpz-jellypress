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

if ( ! function_exists( 'jellypress_show_cta_buttons' ) ) :
  /**
   * Loops through an array and displays buttons if the array is not empty
   * Uses data from ACF repeater field
   */
  function jellypress_show_cta_buttons($buttons) {
    if ( $buttons ) :
      echo '<div class="button-group">';
        foreach( $buttons as $button ) :

          // Default button class and get variables from ACF
          $button_classes = 'button';
          $button_link = $button['button_link'];
          $button_style = $button['button_style'];

          if($button_style!='filled') {
            // 'filled' is the default state so we don't need a class for this
            $button_classes.= ' button__'.$button_style;
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

/**
 * Loops through an ACF repeater field to display the social media channels
 * this organisation is on.
 */
if ( ! function_exists( 'jellypress_display_socials' ) ) :
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
endif;

/**
 * Loops through an ACF repeater field to display the organisation's
 * phone numbers and departments
 */
if ( ! function_exists( 'jellypress_display_numbers' ) ) :
  function jellypress_display_numbers() {
    if ( have_rows( 'phone_numbers', 'option' ) ) :
      $phone_numbers_formatted = '<div itemscope itemtype="https://schema.org/Organization"><span class="screen-reader-text" itemprop="name">'.get_bloginfo('name').__(' contact numbers','jellypress').'</span><ul class="phone-numbers">';
      while ( have_rows( 'phone_numbers', 'option' ) ) : the_row();
        $phone_num = get_sub_field( 'phone_number' );

        // Convert to +44 UK Number and strip spaces
        $sanitized_num = esc_attr( str_replace( array (' ', '(0)'), '', $phone_num[0] === '0' ? '+44' . ltrim( $phone_num, '0' ) : $phone_num ));

        if(get_sub_field( 'department' )){$department = '<span class="bold">'.get_sub_field( 'department' ).': </span>';}
        else {$department = '';}

        $phone_numbers_formatted .= '<li>'.$department.'<a href="tel:'.$sanitized_num.'"><span itemprop="telephone" class="nowrap">'.$phone_num.'<span></a>';
      endwhile;
      $phone_numbers_formatted .= '</ul></div>';
    endif;
    return $phone_numbers_formatted;
  }
endif;

/**
 * Displays the organisation's email address in a robot-obscuring way
 */
if ( ! function_exists( 'jellypress_display_email' ) ) :
  function jellypress_display_email() {
    $email_address = get_field( 'email_address', 'option' );
    return jellypress_hide_email($email_address);
  }
endif;

/**
 * Displays the organisation's opening hours in a formatted table
 */
if ( ! function_exists( 'jellypress_display_opening_hours' ) ) :
  function jellypress_display_opening_hours() {
    // TODO: Rewrite the ACF group to be able to use https://schema.org/openingHours and inject into head as JSON
    if ( have_rows( 'opening_hours', 'option' ) ) :
      $opening_hours_formatted = '<table class="opening-hours"><thead><tr><th>'.__('Day','jellypress').'</th><th>'.__('Opening Hours','jellypress').'</th></tr></thead><tbody>';
      while ( have_rows( 'opening_hours', 'option' ) ) : the_row();
      $opening_hours_formatted .= '<tr><td>'.get_sub_field('days').'</td><td>'.get_sub_field('opening_time').' - '.get_sub_field('closing_time').'</td></tr>';
      endwhile;
      $opening_hours_formatted .= '</tbody></table>';
    endif;
    return $opening_hours_formatted;
  }
endif;

/**
 * Displays the organisation's address in an SEO friendly manner
 */
if ( ! function_exists( 'jellypress_display_address' ) ) :
  function jellypress_display_address() {
    $address = get_field( 'opening_hours', 'option' ); // TODO: Rewrite?
    // Construct Address HTML with valid schema. TODO: Split out and echo with all proper ItemProp
    // TODO: https://css-tricks.com/working-with-schemas-wordpress/
    $address_formatted = '<span itemscope itemtype="https://schema.org/Organization"><span class="screen-reader-text" itemprop="name">'.get_bloginfo('name').'</span><span class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">'.$address['address'].'</span></span>';
    return $address_formatted;
  }
endif;

// Add Shortcodes for use on the front-end
add_shortcode('jellypress-socials', 'jellypress_display_socials');
add_shortcode('jellypress-numbers', 'jellypress_display_numbers');
add_shortcode('jellypress-email', 'jellypress_display_email'); // TODO: Extend this to allow the user to pass an email address to the shortcode
add_shortcode('jellypress-address', 'jellypress_display_address');
add_shortcode('jellypress-opening', 'jellypress_display_opening_hours');
