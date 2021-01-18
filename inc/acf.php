<?php
/**
 * Functions which hook into ACF to add additional functionality to the website.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
* Move location of ACF-JSON local Json folder
* https://www.advancedcustomfields.com/resources/local-json/
*/
if (! function_exists('jellypress_acf_json_load_point') ) {
    function jellypress_acf_json_load_point( $paths )
    {

        // remove original path (optional)
        unset($paths[0]);

        // append path
        $paths[] = get_stylesheet_directory() . '/assets/acf-json';

        // return
        return $paths;
    }
}
if (! function_exists('jellypress_acf_json_save_point') ) {
    function jellypress_acf_json_save_point( $path )
    {

        // update path
        $path = get_stylesheet_directory() . '/assets/acf-json';

        // return
        return $path;
    }
}
add_filter('acf/settings/load_json', 'jellypress_acf_json_load_point');
add_filter('acf/settings/save_json', 'jellypress_acf_json_save_point');


if (!function_exists('jellypress_restrict_acf_tinymce_opts')) {
    /**
     * Restricts TinyMCE options for ACF Wysiwig field
     */
    function jellypress_restrict_acf_tinymce_opts( $toolbars )
    {
        $toolbars['Full' ] = array(
        1 => array('formatselect', 'bold', 'italic', 'blockquote', 'bullist', 'numlist', 'link', 'unlink', 'spellchecker', 'wp_adv'),
        2 => array('styleselect', 'pastetext', 'removeformat', 'charmap', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo' )
        );
        return $toolbars;
    }
}
add_filter('acf/fields/wysiwyg/toolbars', 'jellypress_restrict_acf_tinymce_opts');

/**
  * Adds an ACF options page for organisation information
  */
if(function_exists('acf_add_options_page') ) {
    acf_add_options_page(
        array(
        'page_title'     => __('Jellypress Information and SEO', 'jellypress'),
        'menu_title'    => __('Jellypress Info', 'jellypress'),
        'menu_slug'     => 'organisation-information',
        'capability'    => 'edit_posts',
        'icon_url' => 'dashicons-info',
        'position' => 90,
        'autoload' => true, // Speeds up load times
        'updated_message' => __("Successfully updated organisation information", 'jellypress'),
        )
    );
}

if (! function_exists('jellypress_hide_acf_admin') ) {
    /**
     * Hides ACF settings on the live site. Settings will still be available locally.
     * This snippet is used because we are using ACF Json to manage field groups and keep them in sync
     * When pulling the database from the live site (eg. with WP Migrate DB), it is necessary
     * to sync the json back in - as the production site should never have the ACF fields
     * stored in the database (only the local site will have this)
     *
     * @link https://www.awesomeacf.com/snippets/hide-the-acf-admin-menu-item-on-selected-sites/
     * @link https://support.advancedcustomfields.com/forums/topic/the-acf-json-workflow/
     */
    function jellypress_hide_acf_admin()
    {
        // get the current site url
        $site_url = get_bloginfo('url');
        // an array of development environment URLs
        $dev_urls = array(
         DEV_URL, // Constant defined in functions.php
        );
        // check if the current site url is in the protected urls array
        if (!in_array($site_url, $dev_urls) ) {
            // hide the acf menu item
            return false;
        } else {
            // show the acf menu item
            return true;
        }
    }
}
add_filter('acf/settings/show_admin', 'jellypress_hide_acf_admin');

if (! function_exists('jellypress_google_tag_manager') ) {
    /**
     * Sets up GAnalytics if the user has added a Google Tag ID to the options page
     */

    function jellypress_google_tag_manager()
    {
        $get_gtag_id = get_global_option('gtag_id');
        if ($get_gtag_id) {
            ?>
      <!-- Global site tag (gtag.js) - Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $get_gtag_id;?>"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $get_gtag_id;?>');
      </script>
            <?php
        }
    }
}
add_action('wp_head', 'jellypress_google_tag_manager', 100);

if (! function_exists('jellypress_google_maps_api_key') ) {
    /**
     * Adds Google Maps API Key if the user has added one to the options page
     */

    function jellypress_google_maps_api_key()
    {

        $get_gmaps_api = get_global_option('google_maps_api_key');
        if ($get_gmaps_api) {
            acf_update_setting('google_api_key', $get_gmaps_api);
        }
    }
}
add_action('acf/init', 'jellypress_google_maps_api_key');

if (! function_exists('jellypress_acf_dashicons_support') ) {
    /**
     * Adds Dashicons to ACF allowing us to use them in labels etc.
     * Usage: <span class="dashicons dashicons-menu"></span>
     */

    function jellypress_acf_dashicons_support()
    {
        wp_enqueue_style('dashicons');
    }
}
add_action('admin_init', 'jellypress_acf_dashicons_support');

/**
 * Extend WordPress search to include custom fields
 * Note: if using Search and Filter Pro, this isn't always reliable and
 * it's best to use a plugin like "Search Everything" or try
 * https://gist.github.com/charleslouis/5924863
 *
 * @link https://adambalee.com/search-wordpress-by-custom-fields-without-a-plugin/
 */

/**
 * Join posts and postmeta tables
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
if (! function_exists('jellypress_search_acf_join') ) {
    function jellypress_search_acf_join( $join )
    {
        global $wpdb;
        if (is_search() ) {
            $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
        }
        return $join;
    }
}
add_filter('posts_join', 'jellypress_search_acf_join');

/**
* Modify the search query with posts_where
* http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
*/
if (! function_exists('jellypress_search_acf_where') ) {
    function jellypress_search_acf_where( $where )
    {
        global $pagenow, $wpdb;
        if (is_search() ) {
            $where = preg_replace(
                "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
                "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where
            );
        }
        return $where;
    }
}
add_filter('posts_where', 'jellypress_search_acf_where');

/**
* Prevent duplicates
* http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
*/
if (! function_exists('jellypress_search_acf_distinct') ) {
    function jellypress_search_acf_distinct( $where )
    {
        global $wpdb;
        if (is_search() ) {
            return "DISTINCT";
        }
        return $where;
    }
}
add_filter('posts_distinct', 'jellypress_search_acf_distinct');

/**
 * Hooks into Flexible Content Fields to append useful titles to header handles,
 * so that editors can see at a glance what content is contained within a block.
 */
if (! function_exists('jellypress_acf_flexible_titles') ) {
    function jellypress_acf_flexible_titles($title, $field, $block_layout, $i)
    {
        $block_layout = get_row_layout();
        $block_bg_color = 'bg-'.strtolower(get_sub_field('background_color'));

        if($disabled = get_sub_field('disable')) {
          echo '<div class="acf-block-disabled"></div>';
        }

        $block_show_from = get_sub_field('show_from');
        $block_show_until = get_sub_field('show_until');
        $current_wp_time = current_time('Y-m-d H:i:s');
        if (($block_show_from == NULL OR $block_show_from <= $current_wp_time) AND ($block_show_until == NULL OR $block_show_until>= $current_wp_time)) {
        }
        else {
          echo '<div class="acf-block-disabled"></div>';
        }

        if($block_title = get_sub_field('title')) {
            // If there is a title, use that as priority over anything else
            return '<span class="swatch '.$block_bg_color.'"></span>'.jellypress_trimpara($block_title,30).'<span class="acf-handle-right">'.$title.'</span>';
        }
        elseif($block_layout == 'image') {
            // If the layout is an image, try to use the image title or alt tag, before resorting to filename
            $image_id = get_sub_field('image');
            if($image_title = get_the_title($image_id)) {
                // Start by looking for a title...
            }
            elseif ($image_title = get_post_meta($image_id, '_wp_attachment_image_alt', true)) {
                // If that fails, look for an Alt tag....
            }
            else {
                // If all else fails.... use the filename
                $image_title = get_post_meta($image_id, '_wp_attached_file', true);
            }
            return '<span class="swatch '.$block_bg_color.'"></span>'.jellypress_trimpara($image_title,50).'<span class="acf-handle-right">'.$title.'</span>';
        }
        elseif($block_layout == 'gallery') {
            // If the layout is a gallery, we want to find the first image with either a title or alt tag and append '+ $i'
            if ($images_images = get_sub_field('images') ) :
                $i = 0;
                $gallery_img_title = '';
                foreach ( $images_images as $images_image ):
                    if(($images_image['alt']!= null OR $images_image['title']!= null) AND $gallery_img_title == null) {
                        // If the image has a title or alt tag, and we have not previously found a qualifying image...
                        if($gallery_img_title = $images_image['title']) {
                                // First use the title
                        }
                        else {
                            $gallery_img_title = $images_image['alt'];
                            // Otherwise use the alt text
                        }
                    };
                    $i++;
                endforeach;
                if($gallery_img_title AND $i==1) {
                    // We found a good title, and there is only one image - return the title
                    $images_list = $gallery_img_title;
                }
                elseif ($gallery_img_title AND $i>1) {
                    // We found a good title and there is more than one images - append the total number of images -1
                    $images_list = $gallery_img_title.' and '.($i - 1).' more';
                }
                else {
                    // No title found, echo out the amount of images
                    $images_list = $i.' images';
                }
            endif;
            return '<span class="swatch '.$block_bg_color.'"></span>'.jellypress_trimpara($images_list,50).'<span class="acf-handle-right">'.$title.'</span>';
        }
        elseif($block_layout == 'iframe') {
          $website_url = get_sub_field( 'website_url' );
          return '<span class="swatch '.$block_bg_color.'"></span>'.$website_url.'<span class="acf-handle-right">'.$title.'</span>';
      }
        else {
            // If nothing found, return the block name
            return '<span class="swatch '.$block_bg_color.'"></span>'.$title;
        }
    }
}
add_filter('acf/fields/flexible_content/layout_title', 'jellypress_acf_flexible_titles', 10, 4);

/**
* Sanitizes ACF fields on save to prevent XSS.
* https://support.advancedcustomfields.com/forums/topic/is-sanitization-required-for-front-end-form/
* https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-form-kses.php
*/
if (! function_exists('jellypress_kses_acf') ) :
    function jellypress_kses_acf( $data, $post_id, $field )
    {
        if (!is_array($data)) {
            // If it's not an array, sanitize
            if($field['_name'] != 'unfiltered_html') {
              return wp_kses_post($data);
            }
            else {
              // if fieldName = 'unfiltered_html' don't sanitize
              return $data;
            }
        }
        $return = array();
        if (count($data)) {
            // If it's an array (eg. repeater, group, etc) repeat this function on each value
            foreach ($data as $index => $value) {
                $return[$index] = jellypress_kses_acf($value, $post_id, $field);
            }
        }
        return $return;
    }
endif;
add_filter('acf/update_value', 'jellypress_kses_acf', 10, 3);

/**
 * Speed up the post edit page
 * @link https://www.advancedcustomfields.com/blog/acf-pro-5-5-13-update/
 */
add_filter('acf/settings/remove_wp_meta_box', '__return_true');

/**
 * Hooks into ACF Save Post to allow the editor to clone Flexible Content Fields from another post
 * @Link https://support.advancedcustomfields.com/forums/topic/copy-flexible-content-layout-from-one-post-to-another/
 */
add_action( 'acf/save_post', 'jellypress_import_blocks_from_other_post', 1 );
if (! function_exists('jellypress_import_blocks_from_other_post') ) :
  function jellypress_import_blocks_from_other_post( $post_id ) {

    // Bail early if no ACF data or if 'enable_block_import' is not TRUE
    if ( empty( $_POST['acf'] ) || $_POST['acf']['field_5fa6c5ffe671c'] != 1 ) {
      return;
    }

    // If the post already has 'sections' (Page Blocks field_5d403805052c2) return
    if ( is_array( $_POST['acf']['field_5d403805052c2'] ) && ! empty( $_POST['acf']['field_5d403805052c2'] ) ) {
      return;
    }
    else {
      $current_page_flex_blocks = array(); // Set up empty array

      // Determine which pages/posts to import from field_5fa6c2d0efc19 'import_source' (only one is enabled by default but using the Relationship Field which returns an array)
      $post_blocks_to_import = $_POST['acf']['field_5fa6c2d0efc19'];

      // If there aren't any blocks to import, skip the rest.
      if ( empty( $post_blocks_to_import ) ) {
        return;
      }
      // Loop through all posts to get the flexible content data from them
      foreach ( $post_blocks_to_import as $post_id ) {
        $blocks_from_post = get_field_object( 'sections', $post_id, false, true );
        if ( ! empty( $blocks_from_post['value'] ) ) {
          $current_page_flex_blocks = array_merge( $current_page_flex_blocks, $blocks_from_post['value'] );
        }
      }
      // Insert the found data into 'sections' field_5d403805052c2
      $_POST['acf']['field_5d403805052c2'] = $current_page_flex_blocks;

      // Clear out the import fields
      $_POST['acf']['field_5fa6c2d0efc19'] = array(); // Relationship Field
      $_POST['acf']['field_5fa6c5ffe671c'] = 0; // 'enable_block_import' Boolean
    }
  }
endif;


/**
 * A function which hooks into ACF/Save_Post to insert content into the_excerpt automatically.
 * The function will first look for a specified field (eg. 'post_excerpt'), and if this doesn't exist,
 * will loop through the 'sections' flexible content field looking for the first block of text
 * It then sanitizes output and trims the length to 220 chars.
 *
 * This function is used because when a page is built entirely with flexible content layouts,
 * no excerpt can be auto-generated and if the editor does not use Yoast properly there will be no meta-description
 * shown to search engines.
 *
 * @Link https://support.advancedcustomfields.com/forums/topic/set-wordpress-excerpt-and-post-thumbnail-based-on-custom-field/
 * TODO: Instead of abusing the_excerpt() it might be better to use the_content() ?
 *
 */
add_action('acf/save_post', 'jellypress_excerpt_from_acf', 50);
if (! function_exists('jellypress_excerpt_from_acf') ) :

  function jellypress_excerpt_from_acf($post_id) {

    // TODO: If you are using a specific field for excerpts, be sure to update it here.
    // It's also possible to do a get_post_type() check if different field names exist for different post_types
    $post_excerpt   = get_field( 'post_excerpt', $post_id );

    if(!$post_excerpt) {
      // If the specified field doesn't exist, try to get the text from ACF flexible content
      if ( have_rows( 'sections', $post_id ) ) {
        while ( have_rows( 'sections', $post_id ) ) : the_row();
        $layout = get_row_layout();
        if($layout == 'text' || $layout == 'text-media' || $layout == 'text-columns') {
          // We are making a big assumption that these will be among the first field types used on the page - as they are the best for text content.
          if($layout == 'text') {
            $post_excerpt = get_sub_field( 'text' );
            break;
          }
          elseif($layout == 'text-media') {
            $post_excerpt = get_sub_field( 'text' );
            break;
          }
          elseif($layout == 'text-columns') {
            if ( have_rows( 'columns' ) ) :
              while ( have_rows( 'columns' ) ) : the_row();
                $post_excerpt = get_sub_field('editor');
                break 2;
              endwhile;
            endif;
          }
        }
        endwhile;
        $post_excerpt = jellypress_trimpara(wp_strip_all_tags($post_excerpt),220); // TODO: Could look at replacing with the_excerpt filters.
      }
    }
    if ( ( !empty( $post_id ) ) AND ( $post_excerpt ) ) {
      $post_array     = array(
        'ID'            => $post_id,
        'post_excerpt'	=> $post_excerpt // Use if you want to replace the excerpt
        // TODO: Add an option to replace the_content --> That way the user can still add a manual excerpt
      );
      //remove_action('save_post', 'jellypress_excerpt_from_acf', 50); // Unhook this function so it doesn't loop infinitely
      wp_update_post( $post_array );
      //add_action( 'save_post', 'jellypress_excerpt_from_acf', 50); // Re-hook this function
    }
  }
endif;

// TODO: Add a variable to Server Time message displaying current server time. https://saika.li/snippets-acf-hooks/ gets part way but the field updates with the replaced value on save.

/**
 * ACF / WPML Options
 * Return the value of an Options field from WPML's default language
 * @link https://barebones.dev/articles/acf-and-wpml-get-global-options-value/
 */
if ( ! function_exists( 'jellypress_acf_set_language' ) ) :
  function jellypress_acf_set_language() {
    return acf_get_setting('default_language');
  }
endif;
if ( ! function_exists( 'get_global_option' ) ) :
  function get_global_option($name) {
    add_filter('acf/settings/current_language', 'jellypress_acf_set_language', 100);
    $option = get_field($name, 'option');
    remove_filter('acf/settings/current_language', 'jellypress_acf_set_language', 100);
    return $option;
  }
endif;
