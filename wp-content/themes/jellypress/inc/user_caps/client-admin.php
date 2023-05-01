<?php

/**
 * Adjust User Capabilities for user role 'client-admin'
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Hide pages in the Admin Menu from 'client-admin'
 */
add_action( 'admin_init', function () {
  global $user_ID;
  global $pagenow;

  if ( current_user_can( 'client-admin' ) ) {
    remove_menu_page( 'acf-field-group' ); // Don't want the client to edit ACF fields, this is a redundancy measure as the theme should hide this page on the prod site anyway
    remove_menu_page( 'tools.php' ); // Tools.php is a useless menu
    remove_submenu_page('themes.php', 'themes.php'); // We don't want the client seeing the theme information.
  };

  // Redirect if the user tries to access restricted content.
	$restricted_urls = array(
    'tools.php',
    'options-general.php',
    'edit.php?post_type=acf-field-group',
    'themes.php'
  );
  if (in_array($pagenow,$restricted_urls) && current_user_can( 'client-admin' )) {
    wp_redirect(admin_url());
    exit;
  }
});

add_action( 'admin_menu', function () {
  if(current_user_can('client-admin'))
    {
      $slugs = array(
        //'edit.php?post_type=campaign', // Remove a specific CPT
        //'edit.php', // Posts
        // 'organisation-information', // Options Page
      );
      foreach ($slugs as $slug) {
        remove_menu_page($slug);
      }
  }
}, 999);

add_action( 'wp_before_admin_bar_render', function () {
  if(current_user_can('client-admin'))
    {
      global $wp_admin_bar;
      //$wp_admin_bar->remove_menu('new-post');
      //$wp_admin_bar->remove_menu('new-page');
      //$wp_admin_bar->remove_menu('new-campaign'); // Remove a specific CPT
  }
});
