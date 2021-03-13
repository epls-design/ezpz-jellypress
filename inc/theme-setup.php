<?php
/**
 * Basic theme setup including menus etc
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if (! function_exists('jellypress_content_width') ) :
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
endif;
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

        /**
         * Register Nav Menus
         */
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
         * Register Image Sizes
         */

        add_image_size( 'icon', 40, 40, true ); // Used by Google Maps
        add_image_size( 'medium_landscape', 400, 300, true );

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

        // FIXME: - this is a little buggy
        // Enable editor styles in Gutenberg
        //add_theme_support('editor-styles');
        //add_editor_style( 'dist/css/editor-style.min.css' );
    }
endif;

add_filter('walker_nav_menu_start_el', 'jellypress_replace_menu_hash', 999);
/**
 * Hooks into Wordpress Menu to replace hashtag # with javascript:void(0)
 * Useful when you want to have a drop down parent without a corresponding page
 * @param string $menu_item item HTML
 * @return string item HTML
 */
if (! function_exists('jellypress_replace_menu_hash') ) :
  function jellypress_replace_menu_hash($menu_item) {
      if (strpos($menu_item, 'href="#"') !== false) {
          $menu_item = str_replace('href="#"', 'href="javascript:void(0);"', $menu_item);
      }
      return $menu_item;
  }
endif;

/**
 * Displays a Development flag if the website is local dev environment
 *
 * @return void
 */
if ( ! function_exists( 'jellypress_show_dev_flag' ) ) :
  function jellypress_show_dev_flag() {
    $dev_url = parse_url(DEV_URL); // Defined in functions.php
    $staging_url = parse_url(STAGING_URL); // Defined in functions.php
    $current_url = parse_url(jellypress_get_full_url());
    if ($dev_url['host'] == $current_url['host']){
      echo '<div class="dev-flag dev">' . __('Development Site', 'jellypress') . '</div>';
    }
    elseif ($staging_url['host'] == $current_url['host']){
      echo '<div class="dev-flag staging">' . __('Staging Site', 'jellypress') . '</div>';
    }
  }
endif;
// Hook into footer and admin footer
add_action('wp_footer', 'jellypress_show_dev_flag');
add_action('admin_footer', 'jellypress_show_dev_flag');

/**
 * Function which pulls data from ACF Options Page and displays this as structured JSON schema in the website header.
 */
add_action('wp_head', function() {
  if(is_front_page()) : // Only display on home page according to best practice

    $field_group_json = 'group_5ea7ebc9d7ff7.json'; // The field group that holds all schema content
    $field_group_array = json_decode( file_get_contents( get_stylesheet_directory() . "/assets/acf-json/{$field_group_json}" ), true );
    $schema_config = get_all_custom_field_meta( 'option', $field_group_array );

    $schema = array(
      '@context'  => "http://schema.org",
      '@type'     => $schema_config['schema_type'],
      'name'      => get_bloginfo('name'),
      'url'       => get_home_url(),
      'address'   => array(
        '@type'           => 'PostalAddress',
        'streetAddress'   => $schema_config['address_street'],
        'postalCode'      => $schema_config['address_postal'],
        'addressLocality' => $schema_config['address_locality'],
        'addressRegion'   => $schema_config['address_region'],
        'addressCountry'  => $schema_config['address_country'],
      ),
    );

    // LOGO
    if ($organisation_logo = $schema_config['organisation_logo']) {
      $schema['logo'] = wp_get_attachment_image_url( $organisation_logo, 'medium');
    }


    // IMAGE
    if ($organisation_image = $schema_config['organisation_image']) {
      $schema['image'] = wp_get_attachment_image_url( $organisation_image, 'medium');
    }

    // SOCIAL MEDIA
    if ($socials = $schema_config['social_channels']) {
      $schema['sameAs'] = array();
      foreach($socials as $channel):
        array_push($schema['sameAs'], $channel['url']);
      endforeach;
    }

    // PHONE
    if ($telephone = $schema_config['primary_phone_number']) {
      $link_number = jellypress_append_country_dialing_code($telephone, get_global_option( 'dialing_code'));
      $schema['telephone'] = $link_number;
    }

    // EMAIL
    if ($email = $schema_config['email_address']) {
      $schema['email'] = $email;
    }

    // CONTACT POINTS
    if ($contactPoints = $schema_config['departments']) {
      $schema['contactPoint'] = array();
      foreach($contactPoints as $contactPoint):
          $telephone = jellypress_append_country_dialing_code($contactPoint['phone_number'], $contactPoint['dialing_code']);

          $contact = array(
              '@type'       => 'ContactPoint',
              'contactType' => $contactPoint['department'],
              'telephone'   => $telephone
          );
          if ($email = $contactPoint['email_address']) {
            $contact['email'] = $email;
          }

          if ($telephone_opts = $contactPoint['telephone_opts']) {
            $contact['contactOption'] = $telephone_opts;
          }
          array_push($schema['contactPoint'], $contact);

      endforeach;
    }

    // OPENING HOURS
    if($opening_hours = $schema_config['opening_hours']) {
      $schema['openingHoursSpecification'] = array();
      foreach($opening_hours as $hours):
        $closed = $hours['closed'];
        $from   = $closed ? '00:00' : $hours['from'];
        $to     = $closed ? '00:00' : $hours['to'];
        $openings = array(
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => $hours['days'],
            'opens'     => $from,
            'closes'    => $to
        );
        array_push($schema['openingHoursSpecification'], $openings);
      endforeach;
    }

    // SPECIAL DAYS
    if($special_days = $schema_config['special_days']) {
      foreach($special_days as $day):
        $closed = $day['closed'];
        $date_from   = $day['date_from'];
        $date_to     = $day['date_to'];
        $time_from   = $closed ? '00:00' : $day['time_from'];
        $time_to     = $closed ? '00:00' : $day['time_to'];
        $special_days = array(
          '@type'        => 'OpeningHoursSpecification',
          'validFrom'    => $date_from,
          'validThrough' => $date_to,
          'opens'        => $time_from,
          'closes'       => $time_to
        );
        array_push($schema['openingHoursSpecification'], $special_days);
      endforeach;
    }
    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
  endif;
});
