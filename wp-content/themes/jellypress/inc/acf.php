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
add_filter('acf/settings/load_json', 'jellypress_acf_json_load_point', 10, 1);
add_filter('acf/settings/save_json', 'jellypress_acf_json_save_point', 10, 1);

function jellypress_acf_json_load_point($paths) {
  // remove original path (optional)
  unset($paths[0]);
  // append path
  $paths[] = get_template_directory() . '/src/acf-json';
  // return
  return $paths;
}

function jellypress_acf_json_save_point($path) {
  // update path
  $path = get_template_directory() . '/src/acf-json';
  // return
  return $path;
}

/**
 * Restricts TinyMCE options for ACF Wysiwig field
 */
add_filter('acf/fields/wysiwyg/toolbars', 'jellypress_restrict_acf_tinymce_opts');
function jellypress_restrict_acf_tinymce_opts($toolbars) {

  $toolbars['Basic'] = array(
    1 => array('bold', 'italic', 'link', 'unlink', 'bullist', 'numlist'),
  );

  $toolbars['Full'] = array(
    1 => array('formatselect', 'bold', 'italic', 'bullist', 'numlist', 'link', 'unlink', 'spellchecker', 'wp_adv'),
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
 * Displays a placeholder in Gutenberg editor if ACF field is empty
 * @param string|array $field The ACF field(s) to check.
 * @param string $placeholder The placeholder text to display.
 * @param bool $is_preview True during backend preview render.
 */
function jellypress_acf_placeholder($field, $placeholder, $is_preview) {
  if ($is_preview) {

    if (is_array($field)) {
      $field = array_filter($field);
    }

    if (empty($field))
      echo '<p class="acf-placeholder">' . $placeholder . '</p>';
  }
}
