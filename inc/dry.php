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

if ( ! function_exists( 'jellypress_show_cta_buttons' ) ) :
  /**
   * Prints HTML with meta information for the current author.
   */
  function jellypress_show_cta_buttons() {
    if ( have_rows( 'buttons' ) ) :
      echo '<div class="button-group">';
        while ( have_rows( 'buttons' ) ) : the_row();

          // Default button class and get variables from ACF
          $button_classes = 'button';
          $button_link = get_sub_field( 'button_link' );
          $button_style = get_sub_field( 'button_style' );

          if($button_style!='filled') {
            // 'filled' is the default state so we don't need a class for this
            $button_classes.= ' button__'.$button_style;
          };

          echo '<a class="'.$button_classes.'" href="'.$button_link['url'].'" target="'.$button_link['target'].'">'.$button_link['title'].'</a>';
        endwhile;
      echo '</div>';
      endif;
  }
endif;

if ( ! function_exists( 'jellypress_display_map_markers' ) ) :
  /**
   * Renders Markers onto a map element using data from ACF
   */
  function jellypress_display_map_markers() {
    if ( have_rows( 'location_group' ) ) :
      while ( have_rows( 'location_group' ) ) : the_row();
        $location_marker = get_sub_field( 'location_marker' );
        $location_icon = get_sub_field( 'location_icon' );
      endwhile;
    endif;

    if ( have_rows( 'tooltip_information' ) ) :
      while ( have_rows( 'tooltip_information' ) ) : the_row();
        $location_tooltip_text = get_sub_field( 'location_tooltip_text' );
        $display_address = get_sub_field( 'display_address' );
      endwhile;
    endif;

    if($location_icon) {
      // Add an icon if the user has specified one
      $thumb = $location_icon['sizes'][ 'icon' ];
      $data_icon = 'data-icon="'.$thumb.'"';
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
      if($location_tooltip_text) echo $location_tooltip_text;
      if($display_address == 1) {
        echo $address;
      }
    echo '</div>';

    else: // Needs to have no white space or an empty tooltip will render
    echo '<div class="marker" data-lat="'.$location_marker['lat'].'" data-lng="'.$location_marker['lng'].'" '.$data_icon.'></div>';
    endif;
    }
endif;
