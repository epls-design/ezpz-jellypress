<?php
/**
 * Custom shortcodes used by the theme
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! function_exists( 'jellypress_address_shortcode' ) ) :
  function jellypress_address_shortcode() {
    $address = get_field( 'address', 'option' ); // TODO: Rewrite?
    // Construct Address HTML with valid schema. TODO: Split out and echo with all proper ItemProp
    $address_formatted = '<span itemscope itemtype="https://schema.org/Organization"><span class="screen-reader-text" itemprop="name">'.get_bloginfo('name').'</span><span class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">'.$address['address'].'</span></span>';
    return $address_formatted;
  }
endif;
add_shortcode('jellypress-address', 'jellypress_address_shortcode');

if ( ! function_exists( 'jellypress_numbers_shortcode' ) ) :
  function jellypress_numbers_shortcode() {
    if ( have_rows( 'phone_number', 'option' ) ) :
      $phone_numbers_formatted = '<div itemscope itemtype="https://schema.org/Organization"><span class="screen-reader-text" itemprop="name">'.get_bloginfo('name').'</span><ul class="phone-numbers">';
      while ( have_rows( 'phone_number', 'option' ) ) : the_row();
        $phone_num = get_sub_field( 'phone_number' ); // TODO: Rewrite?
        $sanitized_num = sanitize_text_field(preg_replace("/[^0-9]/", "", $phone_num ));
        $phone_numbers_formatted .= '<li><span class="bold">'.get_sub_field( 'phone_number_title' ).': </span><a href="tel:'.$sanitized_num.'"><span itemprop="telephone" class="nowrap">'.$phone_num.'<span></a>';
      endwhile;
      $phone_numbers_formatted .= '</ul></div>';
    endif;
    return $phone_numbers_formatted;
  }
endif;
add_shortcode('jellypress-numbers', 'jellypress_numbers_shortcode');

if ( ! function_exists( 'jellypress_socials_shortcode' ) ) :
  function jellypress_socials_shortcode() {
    if ( have_rows( 'social_channels', 'option' ) ) : // TODO: Rewrite?
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
add_shortcode('jellypress-socials', 'jellypress_socials_shortcode');
