<?php

/**
 * Plugin Name:  EZPZ Client Role
 * Plugin URI:   https://github.com/epls-design
 * Description:  Creates a 'client' custom role with limited permissions. This role is intended for clients who need to access the admin area but should not be able to perform updates or access other restricted areas of the dashboard.
 * Version:      1.0.0
 * Author:       EPLS
 * Author URI:   https://epls.design
 * Text Domain:  ezpz-clientrole
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('ezpzClientRole')) {
  class ezpzClientRole {
    // Initialize the class
    function __construct() {
      add_action('init', array($this, 'create_role'));
      add_action('admin_init', array($this, 'remove_admin_menus'), 999);
      add_action('wp_before_admin_bar_render', array($this, 'remove_admin_bar_links'));
    }

    /** Creates the Role */
    function create_role() {
      global $wp_roles;
      if (!isset($wp_roles))
        $wp_roles = new WP_Roles();

      $client_role = $wp_roles->get_role('client-admin');

      // Create the client role if it doesn't already exist
      if (!$client_role) {
        $wp_roles->add_role(
          'client-admin',
          'Client',
          array(
            'assign_product_terms' => true,
            'assign_shop_coupon_terms' => true,
            'assign_shop_order_terms' => true,
            'create_posts' => true,
            'create_users' => true,
            'delete_others_pages' => true,
            'delete_others_posts' => true,
            'delete_others_products' => true,
            'delete_others_shop_coupons' => true,
            'delete_others_shop_orders' => true,
            'delete_pages' => true,
            'delete_posts' => true,
            'delete_private_pages' => true,
            'delete_private_posts' => true,
            'delete_private_products' => true,
            'delete_private_shop_coupons' => true,
            'delete_private_shop_orders' => true,
            'delete_product' => true,
            'delete_product_terms' => true,
            'delete_products' => true,
            'delete_published_pages' => true,
            'delete_published_posts' => true,
            'delete_published_products' => true,
            'delete_published_shop_coupons' => true,
            'delete_published_shop_orders' => true,
            'delete_shop_coupon' => true,
            'delete_shop_coupon_terms' => true,
            'delete_shop_coupons' => true,
            'delete_shop_order' => true,
            'delete_shop_order_terms' => true,
            'delete_shop_orders' => true,
            'delete_users' => true,
            'edit_dashboard' => true,
            'edit_others_pages' => true,
            'edit_others_posts' => true,
            'edit_others_products' => true,
            'edit_others_shop_coupons' => true,
            'edit_others_shop_orders' => true,
            'edit_pages' => true,
            'edit_posts' => true,
            'edit_private_pages' => true,
            'edit_private_posts' => true,
            'edit_private_products' => true,
            'edit_private_shop_coupons' => true,
            'edit_private_shop_orders' => true,
            'edit_product' => true,
            'edit_product_terms' => true,
            'edit_products' => true,
            'edit_published_pages' => true,
            'edit_published_posts' => true,
            'edit_published_products' => true,
            'edit_published_shop_coupons' => true,
            'edit_published_shop_orders' => true,
            'edit_shop_coupon' => true,
            'edit_shop_coupon_terms' => true,
            'edit_shop_coupons' => true,
            'edit_shop_order' => true,
            'edit_shop_order_terms' => true,
            'edit_shop_orders' => true,
            'edit_theme_options' => true,
            'edit_users' => true,
            'list_users' => true,
            'manage_categories' => true,
            'manage_options' => true,
            'manage_links' => true,
            'manage_product_terms' => true,
            'manage_shop_coupon_terms' => true,
            'manage_shop_order_terms' => true,
            'manage_woocommerce' => true,
            'moderate_comments' => true,
            'promote_users' => true,
            'publish_pages' => true,
            'publish_posts' => true,
            'publish_products' => true,
            'publish_shop_coupons' => true,
            'publish_shop_orders' => true,
            'read' => true,
            'read_private_pages' => true,
            'read_private_posts' => true,
            'read_private_products' => true,
            'read_private_shop_coupons' => true,
            'read_private_shop_orders' => true,
            'read_product' => true,
            'read_shop_coupon' => true,
            'read_shop_order' => true,
            'remove_users' => true,
            'unfiltered_html' => true,
            'unfiltered_upload' => true,
            'update_core' => true,
            'update_plugins' => true,
            'update_themes' => true,
            'upload_files' => true,
            'view_woocommerce_reports' => true,
            'wf2fa_activate_2fa_self' => true,
            // Gravity Forms
            'gravityforms_api_settings' => true,
            'gravityforms_create_form' => true,
            'gravityforms_delete_entries' => true,
            'gravityforms_delete_forms' => true,
            'gravityforms_edit_entries' => true,
            'gravityforms_edit_entry_notes' => true,
            'gravityforms_edit_forms' => true,
            'gravityforms_edit_settings' => true,
            'gravityforms_export_entries' => true,
            'gravityforms_logging' => true,
            'gravityforms_preview_forms' => true,
            'gravityforms_system_status' => true,
            'gravityforms_uninstall' => true,
            'gravityforms_view_addons' => true,
            'gravityforms_view_entries' => true,
            'gravityforms_view_entry_notes' => true,
            'gravityforms_view_settings' => true,
            'gravityforms_view_updates' => true,
            // Rank Math
            'rank_math_404_monitor' => true,
            'rank_math_admin_bar' => true,
            'rank_math_analytics' => true,
            'rank_math_general' => true,
            'rank_math_link_builder' => true,
            'rank_math_onpage_advanced' => true,
            'rank_math_onpage_analysis' => true,
            'rank_math_onpage_general' => true,
            'rank_math_onpage_snippet' => true,
            'rank_math_onpage_social' => true,
            'rank_math_redirections' => true,
            'rank_math_role_manager' => true,
            'rank_math_site_analysis' => true,
            'rank_math_sitemap' => true,
            'rank_math_titles' => true,
          )
        );
      }
    }

    function remove_admin_menus() {
      global $current_user;
      wp_get_current_user();

      if (!current_user_can('administrator')) {

        remove_menu_page('acf-field-group');
        remove_menu_page('options-general.php');

        remove_submenu_page('themes.php', 'themes.php');
        remove_submenu_page('index.php', 'update-core.php'); // Updates
        remove_submenu_page('tools.php', 'export.php'); // Export
        remove_submenu_page('tools.php', 'import.php'); // Import
        remove_submenu_page('tools.php', 'action-scheduler'); // Action Scheduler
        remove_submenu_page('woocommerce', 'wc-settings'); // Woocommerce Settings
        remove_submenu_page('woocommerce', 'wc-addons'); // Woocommerce Addons
        remove_submenu_page('woocommerce', 'wc-status'); // Woocommerce Status
        remove_submenu_page('rank-math', 'rank-math-status'); // RankMath Status
        remove_submenu_page('gf_edit_forms', 'gf_settings'); // Gravity Forms Settings
        remove_submenu_page('gf_edit_forms', 'gf_system_status'); // Gravity Forms Status
        remove_submenu_page('gf_edit_forms', 'gf_help'); // Gravity Forms Help
      }
    }

    /**
     * Removal of pages from "+ NEW" link in admin bar
     * @see https://developer.wordpress.org/reference/classes/wp_admin_bar/remove_menu/
     */
    function remove_admin_bar_links() {
      if (!current_user_can('administrator')) {
        global $wp_admin_bar;

        $wp_admin_bar->remove_menu('updates'); // Remove Update Link for Non Administrators

        //$wp_admin_bar->remove_menu('new-post');
        //$wp_admin_bar->remove_menu('new-page');
        //$wp_admin_bar->remove_menu('new-campaign'); // Remove a specific CPT
      }
    }
  }
}

/**
 * Initialize the plugin
 */
new ezpzClientRole();
