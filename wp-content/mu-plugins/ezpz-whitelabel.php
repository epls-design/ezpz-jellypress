<?php

/**
 * Plugin Name:  EZPZ White Label
 * Plugin URI:   https://github.com/epls-design
 * Description:  White-labels the Admin Areas of the website
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-whitelabel
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('ezpzWhiteLabel')) {
  class ezpzWhiteLabel {

    // Initialize the class
    function __construct() {
      $this->login_white_label();
      $this->admin_branding();
    }

    /**
     * Applies White Label branding and enhancements to the login screen
     */
    public function login_white_label() {
      /* WHITE LABEL ADMIN DASHBOARD: Change Admin Logo and background image */
      function login_logo() {
        echo '<style type="text/css">
        h1 a { background-image:url("' . esc_url(plugins_url('/ezpz-assets/epls.svg', __FILE__)) . '") !important; background-size: 110px 110px !important;height: 110px !important; width: 110px !important; margin-bottom: 20px !important; padding-bottom: 0 !important; }
        body.login {background-image:url(' . esc_url(plugins_url('/ezpz-assets/epls-mural.png', __FILE__)) . '); background-position: bottom right; background-repeat: no-repeat;background-size: 35%;}
        </style>';
      }
      add_action('login_head', 'login_logo');

      /* SELECTS THE 'REMEMBER ME' OPTION BY DEFAULT */
      function check_rememberme() {
        echo "<script>document.getElementById('rememberme').checked = true;</script>";
      }
      add_filter('login_footer', 'check_rememberme');

      /* WHITE LABEL ADMIN DASHBOARD: Replace Link URL */
      function login_url() {
        return 'https://epls.design';
      }
      add_filter('login_headerurl', 'login_url');

      /* WHITE LABEL ADMIN DASHBOARD: Change login logo hover text */
      function login_logo_title() {
        return 'Website designed and developed by epls.design';
      }
      add_filter('login_headertext', 'login_logo_title');

      /* IF A USER LOGIN FAILS DON'T TELL THEM WHAT ITEM WAS INCORRECT (USERNAME/PASSWORD) */
      function failed_login_text() {
        return __('Login failed because either your username or password was incorrect. Please try again.', 'ezpz-whitelabel');
      }
      add_filter('login_errors', 'failed_login_text');
    }


    /**
     * Applies White Label branding and enhancements to the admin area
     */
    public function admin_branding() {
      /* WHITE LABEL ADMIN DASHBOARD: Change Footer Thankyou Text */
      function modify_admin_footer() {
        echo '<span id="footer-thankyou">Website designed and developed by <a href="https://epls.design" target="_blank">epls.design</a></span>';
      }
      add_filter('admin_footer_text', 'modify_admin_footer');

      /* WHITE LABEL ADMIN DASHBOARD: Replace logo in admin bar */
      function admin_bar_logo() {
        global $wp_admin_bar;
        echo '
          <style type="text/css">
          #wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
            background-image:url(' . esc_url(plugins_url('/ezpz-assets/epls-mini.svg', __FILE__)) . ') !important;
            background-position: 0 0;
            color:rgba(0, 0, 0, 0);
            }
            #wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
            background-position: 0 0;
            }
          </style>
          ';
      }
      add_action('wp_before_admin_bar_render', 'admin_bar_logo');

      /* WHITE LABEL ADMIN DASHBOARD: Add contact info box onto Dashboard */
      function support_widget() {
        wp_add_dashboard_widget('wp_dashboard_widget', 'Website support', 'support_info');
      }
      function support_info() {
        echo '
        <h1>Website support</h1>
        <p>For website support, maintenance and further developments contact <strong>epls.design</strong> on the details below:</p>
        <ul>
        <img src="' . esc_url(plugins_url('/ezpz-assets/epls.svg', __FILE__)) . '"  class="alignright" style="height:80px;width:80px;" alt="epls.design logo">
        <li><strong>Website:</strong> <a href="https://epls.design">epls.design</a></li>
        <li><strong>Email:</strong> <a href="mailto:support@epls.design">support@epls.design</a></li>
        <li><strong>Telephone:</strong> <a href="tel:01962 795019">01962 795019</a></li>
        </ul>';
      }
      add_action('wp_dashboard_setup', 'support_widget');
    }
  }
}

/**
 * Initialize the plugin
 */
new ezpzWhiteLabel();
