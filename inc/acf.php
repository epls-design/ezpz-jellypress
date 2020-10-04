<?php
/**
 * Functions which hook into ACF to add additional functionality to the website.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

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
        2 => array('styleselect', 'forecolor', 'pastetext', 'removeformat', 'charmap', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo' )
        );
        return $toolbars;
    }
}
add_filter('acf/fields/wysiwyg/toolbars', 'jellypress_restrict_acf_tinymce_opts');

/**
  * Adds an ACF options page for business information
  * TODO: Remove if not required by your theme and also remove the ACF field group in the front-end
  */
if(function_exists('acf_add_options_page') ) {
    acf_add_options_page(
        array(
        'page_title'     => __('About Jellypress', 'jellypress'),
        'menu_title'    => __('About Jellypress', 'jellypress'),
        'menu_slug'     => 'organisation-information',
        'capability'    => 'edit_posts',
        'icon_url' => 'dashicons-info',
        'position' => 20,
        'autoload' => true, // Speeds up load times
        'update_button' => __('Save updates', 'jellypress'),
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
        $get_gtag_id = get_field('gtag_id', 'option');
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
        $get_gmaps_api = get_field('google_maps_api_key', 'option');
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

if (! function_exists('jellypress_searchable_acf') ) {
    /**
     * jellypress_searchable_acf list all the custom fields we want to include in our search query]
     *
     * @return [array] [list of custom fields]
     */
    function jellypress_searchable_acf()
    {
        $jellypress_searchable_acf = array("title", "sub_title", "excerpt_short", "excerpt_long", "xyz", "myACF");
        return $jellypress_searchable_acf;
    }
}

/**
 * Extend WordPress search to include custom fields
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
 * so that editors can see at a glance what content is contained within a section.
 */
if (! function_exists('jellypress_acf_flexible_titles') ) {
    function jellypress_acf_flexible_titles($title, $field, $layout, $i)
    {
        $layout = get_row_layout();
        $background_color = 'bg-'.strtolower(get_sub_field('background_color'));

        if($block_title = get_sub_field('title')) {
            // If there is a title, use that as priority over anything else
            return '<span class="swatch '.$background_color.'"></span>'.jellypress_trimpara($block_title,30).'<span class="acf-handle-right">'.$title.'</span>';
        }
        elseif($layout == 'image') {
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
            return '<span class="swatch '.$background_color.'"></span>'.jellypress_trimpara($image_title,50).'<span class="acf-handle-right">'.$title.'</span>';
        }
        elseif($layout == 'gallery') {
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
            return '<span class="swatch '.$background_color.'"></span>'.jellypress_trimpara($images_list,50).'<span class="acf-handle-right">'.$title.'</span>';
        }
        elseif($layout == 'iframe') {
          $website_url = get_sub_field( 'website_url' );
          return '<span class="swatch '.$background_color.'"></span>'.$website_url.'<span class="acf-handle-right">'.$title.'</span>';
      }
        else {
            // If nothing found, return the block name
            return '<span class="swatch '.$background_color.'"></span>'.$title;
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
    function jellypress_kses_acf( $data )
    {
        if (!is_array($data)) {
            // If it's not an array, sanitize
            return wp_kses_post($data);
        }
        $return = array();
        if (count($data)) {
            // If it's an array (eg. repeater, group, etc) repeat this function on each value
            foreach ($data as $index => $value) {
                $return[$index] = jellypress_kses_acf($value);
            }
        }
        return $return;
    }
endif;
add_filter('acf/update_value', 'jellypress_kses_acf', 10, 1);

/**
 * Speed up the post edit page
 * @link https://www.advancedcustomfields.com/blog/acf-pro-5-5-13-update/
 */
add_filter('acf/settings/remove_wp_meta_box', '__return_true');

/**
 * Remove support for WP Editor if you are using ACF exlusively for content
 */
if (! function_exists('jellypress_remove_wp_editor') ) :
  function jellypress_remove_wp_editor() {
    //remove_post_type_support( 'page', 'editor' );
  }
  add_action('init', 'jellypress_remove_wp_editor');
endif;

// TODO: Add a variable to Server Time message displaying current server time. https://saika.li/snippets-acf-hooks/ gets part way but the field updates with the replaced value on save.
