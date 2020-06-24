<?php
/**
 * Basic theme setup including menus etc
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (! function_exists('jellypress_content_width') ) {
    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     * Priority 0 to make it available to lower priority callbacks.
     *
     * @global int $content_width
     */
    function jellypress_content_width()
    {
        // This variable is intended to be overruled from themes.
        // @link https://pineco.de/why-we-should-set-the-content_width-variable-in-wordpress-themes/#:~:text=The%20%24content_width%20global%20variable%20was,for%20images%2C%20videos%20and%20embeds.
        $GLOBALS['content_width'] = apply_filters('jellypress_content_width', 640);
    }
}
add_action('after_setup_theme', 'jellypress_content_width', 0);

add_action('after_setup_theme', 'jellypress_setup');
if (! function_exists('jellypress_setup') ) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function jellypress_setup()
    {
        /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on jellypress, use a find and replace
        * to change 'jellypress' to the name of your theme in all the template files.
        */
        load_theme_textdomain('jellypress', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
        add_theme_support('title-tag');

        /*
        * Enable support for Post Thumbnails on posts and pages.
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(
            array(
            'menu-primary' => esc_html__('Primary', 'jellypress'),
            )
        );

        /*
        * Switch default core markup for search form, comment form, and comments
        * to output valid HTML5.
        */
        add_theme_support(
            'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
            )
        );

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Gutenberg Supports
         * If the theme is going to heavily rely on Gutenberg block builder,
         * You can add a custom colour pallette and more
         * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#default-block-styles
         */

        // Add theme support for Gutenberg wide blocks
        add_theme_support('align-wide');

        // Prevent the user from being able to edit font-sizes
        add_theme_support('disable-custom-font-sizes');

        // TODO: Fix - this is a little buggy
        // Enable editor styles in Gutenberg
        //add_theme_support('editor-styles');
        //add_editor_style( 'dist/css/editor-style.min.css' );
    }
endif;
