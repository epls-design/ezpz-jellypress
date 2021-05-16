<?php
/**
 * Use images from a remote URL if working on the local development website
 * Replaces the need to download/sync lots of media content from a live to
 * local environment
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if(DEV_URL && PROD_URL) :
  $url1 = parse_url(DEV_URL);
  $url2 = parse_url(jellypress_get_full_url());
  if ($url1['host'] == $url2['host']){
    add_filter( 'wp_get_attachment_image_src', 'jellypress_wp_get_attachment_image_src' );
    add_filter( 'wp_calculate_image_srcset', 'jellypress_wp_calculate_image_srcset' );
    add_filter( 'wp_get_attachment_url', 'jellypress_wp_get_attachment_url' );
  }
endif;

if ( ! function_exists( 'jellypress_wp_get_attachment_image_src' ) ) :
  function jellypress_wp_get_attachment_image_src($image = array()) {
    if ( ! is_array( $image ) || empty( $image ) ) {
      return $image;
    }
    $wp_upload_dir = wp_upload_dir();
    $base_dir      = $wp_upload_dir['basedir'];
    $base_url      = $wp_upload_dir['baseurl'];
    $absolute_path = str_replace( $base_url, $base_dir, $image[0] );
    if ( file_exists( $absolute_path ) ) {
      return $image;
    }
    $find     = get_site_url();
    $replace  = PROD_URL;
    $image[0] = str_replace( $find, $replace, $image[0] );
    return $image;
  }
endif;

if ( ! function_exists( 'jellypress_wp_calculate_image_srcset' ) ) :
  function jellypress_wp_calculate_image_srcset($src = array()) {
    if ( is_array( $src ) && ! is_admin() ) {
      $wp_upload_dir = wp_upload_dir();
      $base_dir      = $wp_upload_dir['basedir'];
      $base_url      = $wp_upload_dir['baseurl'];
      $find          = get_site_url();
      $replace       = PROD_URL;
      foreach ( $src as $key => $val ) {
        $absolute_path = str_replace( $base_url, $base_dir, $val['url'] );
        if ( ! file_exists( $absolute_path ) ) {
          $val['url']  = str_replace( $find, $replace, $val['url'] );
          $src[ $key ] = $val;
        }
      }
    }
    return $src;
  }
endif;

if ( ! function_exists( 'jellypress_wp_get_attachment_url' ) ) :
  function jellypress_wp_get_attachment_url($url = '') {
    if ( is_admin() ) {
      return $url;
    }
    $wp_upload_dir = wp_upload_dir();
    $base_dir      = $wp_upload_dir['basedir'];
    $base_url      = $wp_upload_dir['baseurl'];
    $find          = get_site_url();
    $replace       = PROD_URL;
    $absolute_path = str_replace( $base_url, $base_dir, $url );
    if ( ! file_exists( $absolute_path ) ) {
      $url = str_replace( $find, $replace, $url );
    }
    return $url;
  }
endif;
