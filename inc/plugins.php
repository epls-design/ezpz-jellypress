<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Plugin-Activation
 * @subpackage Example
 * @version    2.6.1 for parent theme Jellypress
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Plugin-Activation
 */


add_action( 'tgmpa_register', 'jellypress_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function jellypress_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

    /**
     * Plugins required by the theme
     */

		// Include from the WordPress Plugin Repository.
		array(
			'name'      => 'ACF Color Swatches',
			'slug'      => 'acf-color-swatches',
      'required'  => true,
      'force_activation'  => true,
    ),

    // Include from an external source
		array(
			'name'         => 'ACF Field Group Values',
			'slug'         => 'acf-field-group-values',
			'source'       => 'https://github.com/timothyjensen/acf-field-group-values/archive/master.zip',
      'required'     => true,
      'force_activation'  => true,
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
    // TODO: Add ezpz Clean up plugin - linked to Github repo. Not sure how this wrks when updated so test it.

    /**
     * Plugins recommended by the theme
     * These aren't necessary for functionality but are plugins
     * used most often in web builds.
     */

		// Include from the WordPress Plugin Repository.
		array(
			'name'      => 'ACF Content Analysis for Yoast SEO',
			'slug'      => 'acf-content-analysis-for-yoast-seo',
    ),
    array(
			'name'      => 'Autoptimize',
			'slug'      => 'autoptimize',
    ),
    array(
			'name'      => 'GDPR Cookie Consent',
			'slug'      => 'cookie-law-info',
    ),
    array(
			'name'      => 'User Role Editor',
			'slug'      => 'user-role-editor',
		),
    array(
			'name'      => 'Stream',
			'slug'      => 'stream',
    ),
    array(
			'name'      => 'Wordfence Security',
			'slug'      => 'wordfence',
    ),
    array(
			'name'      => 'WP Migrate DB',
			'slug'      => 'wp-migrate-db',
    ),
    array(
			'name'      => 'WPS Hide Login',
			'slug'      => 'wps-hide-login',
    ),
    array(
			'name'      => 'Yoast SEO',
			'slug'      => 'wordpress-seo',
		),
	);

	/*
	 * Array of configuration settings.
	 */
	$config = array(
		'id'           => 'jellypress',
		'parent_slug'  => 'plugins.php',
		'capability'   => 'edit_plugins',
		'dismissable'  => false,
		'is_automatic' => false,
		'strings'      => array(
			'page_title'                      => __( 'Install Required Theme Plugins', 'jellypress' ),
			'menu_title'                      => __( 'Theme Plugins', 'jellypress' ),
			'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). */
				'This theme requires the following plugin to operate correctly: %1$s.',
				'This theme requires the following plugins to operate correctly: %1$s.',
				'jellypress'
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). */
				'This theme recommends the following plugin to add additional functionality: %1$s.',
				'This theme recommends the following plugins to add additional functionality: %1$s.',
				'jellypress'
			),
		),
	);
	tgmpa( $plugins, $config );
}
