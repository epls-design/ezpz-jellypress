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
add_filter('acf/settings/load_json', 'jellypress_acf_json_load_point');
add_filter('acf/settings/save_json', 'jellypress_acf_json_save_point');

function jellypress_acf_json_load_point($paths) {
  // remove original path (optional)
  unset($paths[0]);
  // append path
  $paths[] = get_stylesheet_directory() . '/src/acf-json';
  // return
  return $paths;
}

function jellypress_acf_json_save_point($path) {
  // update path
  $path = get_stylesheet_directory() . '/src/acf-json';
  // return
  return $path;
}

/**
 * Restricts TinyMCE options for ACF Wysiwig field
 */
add_filter('acf/fields/wysiwyg/toolbars', 'jellypress_restrict_acf_tinymce_opts');
function jellypress_restrict_acf_tinymce_opts($toolbars) {
  $toolbars['Full'] = array(
    1 => array('formatselect', 'bold', 'italic', 'blockquote', 'bullist', 'numlist', 'link', 'unlink', 'spellchecker', 'wp_adv'),
    2 => array('styleselect', 'pastetext', 'removeformat', 'charmap', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo')
  );
  return $toolbars;
}

/**
 * Adds Google Maps API Key if the user has added one to the options page
 */
add_action('acf/init', 'jellypress_google_maps_api_key');
function jellypress_google_maps_api_key() {
  $get_gmaps_api = get_global_option('google_maps_api_key');
  if ($get_gmaps_api) {
    acf_update_setting('google_api_key', $get_gmaps_api);
  }
}

/**
 * Adds Dashicons to ACF allowing us to use them in labels etc.
 * Usage: <span class="dashicons dashicons-menu"></span>
 */
add_action('admin_init', 'jellypress_acf_dashicons_support');
function jellypress_acf_dashicons_support() {
  wp_enqueue_style('dashicons');
}

/**
 * Hooks into Flexible Content Fields to append useful titles to header handles,
 * so that editors can see at a glance what content is contained within a block.
 */
add_filter('acf/fields/flexible_content/layout_title', 'jellypress_acf_flexible_titles', 10, 4);
function jellypress_acf_flexible_titles($title, $field, $block_layout, $i) {
  $block_layout = get_row_layout();
  $block_bg_color = 'bg-' . strtolower(get_sub_field('background_color'));

  if ($disabled = get_sub_field('disable')) {
    echo '<div class="acf-block-disabled"></div>';
  }

  $block_show_from = get_sub_field('show_from');
  $block_show_until = get_sub_field('show_until');
  $current_wp_time = current_time('Y-m-d H:i:s');
  if (($block_show_from == NULL or $block_show_from <= $current_wp_time) and ($block_show_until == NULL or $block_show_until >= $current_wp_time)) {
  } else {
    echo '<div class="acf-block-disabled"></div>';
  }

  if ($block_title = get_sub_field('title')) {
    // If there is a title, use that as priority over anything else
    return '<span class="swatch ' . $block_bg_color . '"></span>' . jellypress_trimpara($block_title, 30) . '<span class="acf-handle-right">' . $title . '</span>';
  } elseif ($block_layout == 'image') {
    // If the layout is an image, try to use the image title or alt tag, before resorting to filename
    $image_id = get_sub_field('image');
    if ($image_title = get_the_title($image_id)) {
      // Start by looking for a title...
    } elseif ($image_title = get_post_meta($image_id, '_wp_attachment_image_alt', true)) {
      // If that fails, look for an Alt tag....
    } else {
      // If all else fails.... use the filename
      $image_title = get_post_meta($image_id, '_wp_attached_file', true);
    }
    return '<span class="swatch ' . $block_bg_color . '"></span>' . jellypress_trimpara($image_title, 50) . '<span class="acf-handle-right">' . $title . '</span>';
  } elseif ($block_layout == 'gallery') {
    // If the layout is a gallery, we want to find the first image with either a title or alt tag and append '+ $i'
    if ($images_images = get_sub_field('images')) :
      $i = 0;
      $gallery_img_title = '';
      foreach ($images_images as $images_image) :
        if (($images_image['alt'] != null or $images_image['title'] != null) and $gallery_img_title == null) {
          // If the image has a title or alt tag, and we have not previously found a qualifying image...
          if ($gallery_img_title = $images_image['title']) {
            // First use the title
          } else {
            $gallery_img_title = $images_image['alt'];
            // Otherwise use the alt text
          }
        };
        $i++;
      endforeach;
      if ($gallery_img_title and $i == 1) {
        // We found a good title, and there is only one image - return the title
        $images_list = $gallery_img_title;
      } elseif ($gallery_img_title and $i > 1) {
        // We found a good title and there is more than one images - append the total number of images -1
        $images_list = $gallery_img_title . ' and ' . ($i - 1) . ' more';
      } else {
        // No title found, echo out the amount of images
        $images_list = $i . ' images';
      }
    endif;
    return '<span class="swatch ' . $block_bg_color . '"></span>' . jellypress_trimpara($images_list, 50) . '<span class="acf-handle-right">' . $title . '</span>';
  } elseif ($block_layout == 'iframe') {
    $website_url = get_sub_field('website_url');
    return '<span class="swatch ' . $block_bg_color . '"></span>' . $website_url . '<span class="acf-handle-right">' . $title . '</span>';
  } else {
    // If nothing found, return the block name
    return '<span class="swatch ' . $block_bg_color . '"></span>' . $title;
  }
}

/**
 * Sanitizes ACF fields on save to prevent XSS.
 * https://support.advancedcustomfields.com/forums/topic/is-sanitization-required-for-front-end-form/
 * https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-form-kses.php
 */
add_filter('acf/update_value', 'jellypress_kses_acf', 10, 3);
function jellypress_kses_acf($data, $post_id, $field) {
  if (!is_array($data)) {
    // If it's not an array, sanitize
    if ($field['_name'] != 'unfiltered_html') {
      return wp_kses_post($data);
    } else {
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

/**
 * Speed up the post edit page
 * @link https://www.advancedcustomfields.com/blog/acf-pro-5-5-13-update/
 */
add_filter('acf/settings/remove_wp_meta_box', '__return_true');

/**
 * ACF / WPML Options
 * Return the value of an Options field from WPML's default language
 * @link https://barebones.dev/articles/acf-and-wpml-get-global-options-value/
 */
function jellypress_acf_set_language() {
  return acf_get_setting('default_language');
}
if (!function_exists('get_global_option')) :
  function get_global_option($name) {
    add_filter('acf/settings/current_language', 'jellypress_acf_set_language', 100);
    $option = get_field($name, 'option');
    remove_filter('acf/settings/current_language', 'jellypress_acf_set_language', 100);
    return $option;
  }
endif;

/**
 * Loop through ACF flexible repeater field 'sections' to find a WYSIWIG field
 *
 * @return string text from a WYSIWIG field
 */
function jellypress_excerpt_from_acf_flexible_content() {
  if (have_rows('sections')) {
    while (have_rows('sections')) : the_row();
      $layout = get_row_layout();
      // Loop through to find first WYSIWIG field - increase performance by only using specified layouts
      if (!isset($post_excerpt)) {
        if ($layout == 'text') {
          $post_excerpt = get_sub_field('text');
          //break;
        } elseif ($layout == 'magic-columns') {
          if (have_rows('columns')) :
            while (have_rows('columns')) : the_row();
              $post_excerpt = get_sub_field('text');
            //break 2;
            endwhile;
          endif;
        } elseif ($layout == 'text-columns') {
          if (have_rows('columns')) :
            while (have_rows('columns')) : the_row();
              $post_excerpt = get_sub_field('editor');
            //break 2;
            endwhile;
          endif;
        }
      }
    endwhile;
  }
  if (isset($post_excerpt)) return $post_excerpt;
  else return false;
}
