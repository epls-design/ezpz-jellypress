<?php

/**
 * Plugin Name:  EZPZ Dependencies
 * Plugin URI:   https://github.com/epls-design
 * Description:  Forcibly installs dependencies required by this website.
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-dependencies
 */

/* TODO: In time, we may replace this with a composer set up */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

// Include the TGM Plugin Activation helper
require_once(plugin_dir_path(__FILE__) . 'ezpz-assets/tgm-plugin-activation.php');

add_action(
  'tgmpa_register',
  function () {

    $plugins = array(
      array(
        'name'         => 'ACF Field Group Values',
        'slug'         => 'acf-field-group-values',
        'source'       => 'https://github.com/timothyjensen/acf-field-group-values/archive/master.zip',
        'required'     => true,
        'force_activation'  => true,
        'force_deactivation'  => true,
        'external_url' => 'https://github.com/timothyjensen/acf-field-group-values',
      ),
      array(
        'name'         => 'Advanced Custom Fields PRO',
        'slug'         => 'advanced-custom-fields-pro',
        'source'       => 'https://github.com/wp-premium/advanced-custom-fields-pro/archive/master.zip',
        'required'     => true,
        'force_activation'  => true,
        'external_url' => 'https://github.com/wp-premium/advanced-custom-fields-pro',
      ),
      array(
        'name'      => 'User Role Editor',
        'slug'      => 'user-role-editor',
        'required'     => true,
        'force_activation'  => true,
      ),
      array(
        'name'      => 'WPS Hide Login',
        'slug'      => 'wps-hide-login',
        'required'     => true,
        'force_activation'  => true,
      ),
      array(
        'name'      => 'Wordfence Security',
        'slug'      => 'wordfence',
      ),
      array(
        'name'      => 'ACF: Better Search',
        'slug'      => 'acf-better-search',
      ),
      array(
        'name'      => 'Rank Math SEO',
        'slug'      => 'seo-by-rank-math',
      ),
    );
    $config = array(
      'id'           => 'ezpz-dependencies',
      'default_path' => '',
      'menu'         => 'tgmpa-install-plugins',
      'parent_slug'  => 'plugins.php',
      'capability'   => 'administrator',
      'has_notices'  => true,
      'dismissable'  => true,
      'dismiss_msg'  => '',
      'is_automatic' => true,
      'message'      => '',

    );
    tgmpa($plugins, $config);
  }
);
