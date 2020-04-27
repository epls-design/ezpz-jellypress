<?php
/**
 * jellypress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package jellypress
 */

// DEFINE THEME CONSTANTS
if ( ! defined( 'HOME_URI' ) ) {
	define( 'HOME_URI', home_url() );
}
if ( ! defined( 'THEME_URI' ) ) {
	define( 'THEME_URI', get_template_directory_uri() );
}
if ( ! defined( 'THEME_IMG' ) ) {
	define( 'THEME_IMG', THEME_URI . '/assets/img' );
}
if ( ! defined( 'THEME_JS' ) ) {
	define( 'THEME_JS', THEME_URI . '/assets/js' );
}
if ( ! defined( 'THEME_FAVICONS' ) ) {
	define( 'THEME_FAVICONS', THEME_URI . '/assets/favicon' );
}

if ( ! function_exists( 'jellypress_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function jellypress_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on jellypress, use a find and replace
		 * to change 'jellypress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'jellypress', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-primary' => esc_html__( 'Primary', 'jellypress' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
		) );

		// Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Hook favicons into wp_head()
    // @link https://realfavicongenerator.net/ to generate the pack
    function jellypress_favicon_script() {
      echo '
      <link rel="apple-touch-icon" sizes="180x180" href="'.THEME_FAVICONS.'/apple-touch-icon.png">
      <link rel="icon" type="image/png" href="'.THEME_FAVICONS.'/favicon-32x32.png" sizes="32x32">
      <link rel="icon" type="image/png" href="'.THEME_FAVICONS.'/favicon-16x16.png" sizes="16x16">
      <link rel="manifest" href="'.THEME_FAVICONS.'/manifest.json">
      <link rel="mask-icon" href="'.THEME_FAVICONS.'/safari-pinned-tab.svg" color="#5bbad5">
      <link rel="shortcut icon" href="'.THEME_FAVICONS.'/favicon.ico">
      <meta name="msapplication-config" content="'.THEME_FAVICONS.'/browserconfig.xml">
      <meta name="theme-color" content="#ffffff">
      ';
    }
    add_action('wp_head', 'jellypress_favicon_script');

    // Add favicon to admin areas
    function add_favicon() {
        $favicon_url = THEME_FAVICONS.'/favicon.ico';
        echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
    }
    add_action('login_head', 'add_favicon');
    add_action('admin_head', 'add_favicon');

	}
endif;
add_action( 'after_setup_theme', 'jellypress_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function jellypress_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	$GLOBALS['content_width'] = apply_filters( 'jellypress_content_width', 640 );
}
add_action( 'after_setup_theme', 'jellypress_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * TODO: Remove if not required in your theme
 */
function jellypress_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'jellypress' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'jellypress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'jellypress_widgets_init' );

/**
 * Adds support for editor styles editor-style.css
 */
add_editor_style();

/**
 * Enqueue scripts and styles.
 */
function jellypress_scripts() {
  $theme_version = wp_get_theme()->get( 'Version' ); // Get theme version

  // Enqueue Stylesheets
  wp_enqueue_style('jellypress-style', get_stylesheet_uri(), null, $theme_version, 'all');
  wp_style_add_data( 'jellypress-style', 'rtl', 'replace' );

  // Enqueue Scripts
  wp_enqueue_script('site', THEME_JS.'/site.min.js', array('jquery'),$theme_version, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'jellypress_scripts' );

/**
 * Prevents Wordpress from automatically adding classes to pasted text, on <span> and <p> tagds (eg. class="p1")
 */
function jellypress_prevent_autotags($in) {
  $in['paste_preprocess'] = "function(pl,o){ o.content = o.content.replace(/p class=\"p[0-9]+\"/g,'p'); o.content = o.content.replace(/span class=\"s[0-9]+\"/g,'span'); }";
  return $in;
}
add_filter('tiny_mce_before_init', 'jellypress_prevent_autotags');

/**
 * Unset additional image sizes created by Wordpress since 5.3
 */

function jellypress_remove_default_image_sizes( $sizes) {
	//unset( $sizes['large']);
	//unset( $sizes['thumbnail']);
	//unset( $sizes['medium']);
	unset( $sizes['medium_large']);
	unset( $sizes['1536x1536']);
	unset( $sizes['2048x2048']);
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'jellypress_remove_default_image_sizes');

/**
 * Hides ACF settings on the live site. Settings will still be available locally.
 * This snippet is used because we are using ACF Json to manage field groups and keep them in sync
 * When pulling the database from the live site (eg. with WP Migrate DB), it is necessary
 * to sync the json back in - as the production site should never have the ACF fields
 * stored in the database (only the local site will have this)
 * @link https://www.awesomeacf.com/snippets/hide-the-acf-admin-menu-item-on-selected-sites/
 * @link https://support.advancedcustomfields.com/forums/topic/the-acf-json-workflow/
 */
function jellypress_hide_acf_admin() {
  // get the current site url
  $site_url = get_bloginfo( 'url' );
  // an array of protected site urls
  $protected_urls = array(
      'http://jellypress-website-url-here.com', // TODO: Change for your live project
      'https://jellypress-website-url-here.com', // TODO: Change for your live project
  );
  // check if the current site url is in the protected urls array
  if ( in_array( $site_url, $protected_urls ) ) {
      // hide the acf menu item
      return false;
  } else {
      // show the acf menu item
      return true;
  }
}

add_filter('acf/settings/show_admin', 'jellypress_hide_acf_admin');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customise editor
 */
require_once get_template_directory() . '/inc/editor.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
