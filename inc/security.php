<?php
/**
 * Provides security enhancements.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * TODO: Currently testing - login prevention to wp-login.
 * Will eventually replace functionally provided by wps hide-login plugin
 * @link https://stackoverflow.com/questions/24090866/change-wordpress-admin-url
 *
 * @return void
 */

add_action('login_head', 'jellypress_login_protection');
if ( ! function_exists( 'jellypress_login_protection' ) ) :
  function jellypress_login_protection() {

    $QS = '?manager';

    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') $theRequest = "https://";
    else $theRequest = "http://";
    $theRequest .= $_SERVER['SERVER_NAME'] . '/' . 'wp-login.php' . '?'. $_SERVER['QUERY_STRING'];

    if ( site_url('/wp-login.php').$QS == $theRequest ) echo 'Query string matches';
    else header( 'Location: ' . site_url() );
  }
endif;
