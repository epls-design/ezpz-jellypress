<?php

/**
 * Useful Helper functions and snippets
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Function which trims supplied text to a specified length.
 *
 * @param $text = Text to Trim
 * @param $maxchar = Maximum characters
 * @param string $end = Appended to text that gets trimmed
 * @return void
 */
function jellypress_trimpara($text, $maxchar, $end = '...') {
  // @link https://www.hashbangcode.com/article/cut-string-specified-length-php
  if (strlen($text) > $maxchar || $text == '') {
    $words = preg_split('/\s/', $text);
    $output = '';
    $i      = 0;
    while (1) {
      $length = strlen($output) + strlen($words[$i]);
      if ($length > $maxchar) {
        break;
      } else {
        $output .= " " . $words[$i];
        ++$i;
      }
    }
    $output .= $end;
  } else {
    $output = $text;
  }
  return $output;
}

/**
 * Display an SVG icon from the spritesheet
 */
function jellypress_icon($icon) {
  // Define SVG sprite file.
  $icon_path = get_theme_file_path('/dist/icons/' . $icon . '.svg');
  // If it exists, include it.
  if (file_exists($icon_path)) {
    $use_link = get_template_directory_uri() . '/dist/icons/icons.svg#icon-' . $icon;
    return '<svg class="icon icon__' . $icon . '"><use xlink:href="' . $use_link . '" /></use></svg>';
  } else {
    return '';
  }
}

/**
 * Calculates the approximate reading time for a string.
 * Sanitizes and removes tags to give an accurate read out.
 *
 * @param string $string = text to count
 * @param integer $wpm = Words Per Minute
 * @return integer Approximate reading time in minutes
 */
function jellypress_calculate_reading_time($string, $wpm = 265) {
  $text_content = strip_shortcodes($string);    // Remove shortcodes
  $str_content = strip_tags($text_content);   // Remove tags
  $word_count = str_word_count($str_content); // Count Words

  $reading_time = ceil($word_count / $wpm);
  return $reading_time;
}

/**
 * Wrapper function for get_all_custom_field_meta()
 * @link https://github.com/timothyjensen/acf-field-group-values
 * @param string $fieldgroup Field Group String
 * @param string/int $post_id Post ID to get data for, or can be 'option' or a termID
 * @param boolean $field_labels Whether to return field labels
 * @param array $cloned_fields In order to retrieve values for clone fields you must pass a third argument: all field group arrays that contain the fields that will be cloned
 * @return array Array of Data from ACF
 */
function jellypress_get_acf_fields($fieldgroup, $post_id = 'default', $field_labels = false, $cloned_fields = []) {
  if (is_plugin_active('acf-field-group-values/acf-field-group-values.php')) :
    if ($post_id == 'default') $post_id = get_the_ID();
    $field_group_array = json_decode(file_get_contents(get_stylesheet_directory() . "/src/acf-json/group_" . $fieldgroup . ".json"), true);
    $data = get_all_custom_field_meta($post_id, $field_group_array, $cloned_fields, $field_labels);
  elseif (current_user_can('administrator')) :
    echo '<div class="callout error">' . __('The ACF data can not be displayed. Have you installed the <a href="https://github.com/timothyjensen/acf-field-group-values" target="_blank" rel="nofollow" class="callout-link">ACF Field Group Values</a> plugin?', 'jellypress') . '</div>';
    $data = null;
  endif;
  return $data;
}

/**
 * Loops through an array and displays buttons if the array is not empty
 * Uses data from ACF repeater field
 */
function jellypress_display_cta_buttons($buttons, $classes = null) {
  if ($buttons) :
    if (isset($classes)) echo '<div class="button-list ' . $classes . '">';
    else echo '<div class="button-list">';
    foreach ($buttons as $button) :

      // Default button class and get variables from ACF
      $button_classes = 'button';
      $button_link = $button['button_link'];
      $button_color = $button['button_color'];
      $button_style = $button['button_style'];

      if ($button_color != 'default') {
        $button_classes .= ' ' . $button_color;
      }

      if ($button_style != 'filled') {
        // 'filled' is the default state so we don't need a class for this
        $button_classes .= ' ' . $button_style;
      };

      // Check if the URL is external, if so add a rel="external"
      $button_url = parse_url($button_link['url']);
      $blog_url = parse_url(get_bloginfo('url'));
      if ($button_url['host'] == $blog_url['host']) {
        echo '<a class="' . $button_classes . '" href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" target="' . $button_link['target'] . '">' . $button_link['title'] . '</a>';
      } else {
        // Outbound link - add rel=external
        echo '<a class="' . $button_classes . '" href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" target="' . $button_link['target'] . '" rel="external">' . $button_link['title'] . '</a>';
      }
    endforeach;
    echo '</div>';
  endif;
}

/**
 * Renders Markers onto a map element using data from ACF
 */
function jellypress_display_map_markers($locations) {
  if ($locations) :

    wp_enqueue_script('googlemaps');

    echo '<div class="google-map">';
    foreach ($locations as $location) :

      if ($location_group = $location['location_group']) :
        $location_marker = $location_group['location_marker'];
        $location_icon = $location_group['location_icon'];
      endif;

      if ($tooltip_information = $location['tooltip_information']) :
        $location_tooltip_text = $tooltip_information['location_tooltip_text'];
        $display_address = $tooltip_information['display_address'];
      endif;

      if ($location_icon) {
        // Add an icon if the user has specified one
        $thumb = wp_get_attachment_image_src($location_icon, 'icon');
        $data_icon = 'data-icon="' . $thumb[0] . '"'; // Returns an array so get the first value
      } else {
        $data_icon = '';
      }

      // Construct Address HTML with valid schema.
      $address = '<div class="address" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">';
      if ($location_marker['street_number'] || $location_marker['street_name']) $address .= '<span itemprop="streetAddress">' . $location_marker['street_number'] . ' ' . $location_marker['street_name'] . '</span><br/>';
      if ($location_marker['city']) $address .= '<span itemprop="addressLocality">' . $location_marker['city'] . '</span><br/>';
      if ($location_marker['state']) $address .= '<span itemprop="addressRegion">' . $location_marker['state'] . '</span><br/>';
      if ($location_marker['post_code']) $address .= '<span itemprop="postalCode">' . $location_marker['post_code'] . '</span><br/>';
      if ($location_marker['country']) $address .= '<span itemprop="addressCountry">' . $location_marker['country'] . '</span>';
      $address .= '</div>';

      if ($location_tooltip_text || $display_address == 1) :
        echo '<div class="marker" data-lat="' . $location_marker['lat'] . '" data-lng="' . $location_marker['lng'] . '" ' . $data_icon . '>';
        if ($location_tooltip_text)
          echo jellypress_content($location_tooltip_text);
        if ($display_address == 1) {
          echo $address;
        }
        echo '</div>';
      else : // Needs to have no white space or an empty tooltip will render
        echo '<div class="marker" data-lat="' . $location_marker['lat'] . '" data-lng="' . $location_marker['lng'] . '" ' . $data_icon . '></div>';
      endif;

    endforeach;
    echo '</div>';
  endif; // if ($locations)
}

/**
 * Loop through ACF repeater in the options page to display
 * the organisation's social media channels in an icon list
 *
 * @return string Formatted HTML list of icons with anchor links
 */
add_shortcode('jellypress-socials', 'jellypress_display_socials');
function jellypress_display_socials() {
  if (have_rows('social_channels', 'option')) :
    $social_links_formatted = '<ul class="social-channels">';
    while (have_rows('social_channels', 'option')) : the_row();
      $socialnetwork = get_sub_field('network');
      $socialUrl = get_sub_field('url');
      $social_links_formatted .= '<li class="social-icon"><a href="' . $socialUrl . '" rel="noopener" title="' . __('Visit us on ', 'jellypress') . ucfirst($socialnetwork) . ' ">' . jellypress_icon($socialnetwork) . '</a></li>';
    endwhile;
    $social_links_formatted .= '</ul>';
    return $social_links_formatted;
  endif;
}

/**
 * A function which displays the wordpress content area (used to show a password form)
 * if the post is password protected and the_content() is empty (in which case we can assume
 * the page is designed entirely with ACF.)
 */
function jellypress_show_password_form() {
  if ((empty(get_the_content())  || '' == get_post()->post_content) && post_password_required()) {
    echo '<section class="container block bg-white password-protected"><div class="row"><div class="col">';
    the_content();
    echo '</div></div></section>';
  }
}

/*************************************
 * FUNCTIONS TO HELP WITH EMBEDDING
 * VIDEO CONTENT FROM VIMEO OR YOUTUBE
 *************************************/

/**
 * Determines the platform of a video by analysing the URL
 * @param string $video The URL that should be embedded.
 */
function jellypress_get_video_platform($video) {
  $platform = false;
  if (strpos($video, 'vimeo.com') !== false) {
    $platform = 'vimeo';
  } elseif (
    strpos($video, 'youtube.com') !== false ||
    strpos($video, 'youtu.be' !== false) ||
    strpos($video, 'youtube-nocookie.com' !== false)
  ) {
    $platform = 'youtube';
  }
  return $platform;
}

/**
 * Prepares and displays an oembed video with play button
 * @param string $video The URL that should be embedded.
 */
function jellypress_embed_video($video, $aspect_ratio = '16x9', $autoplay = false) {

  $oembed = wp_oembed_get($video); // Full oEmbed Code
  $oembed = explode(' src="', $oembed); // Explode to put the URL and options into [1]
  if (isset($oembed[1])) {
    $oembed[1] = explode('" ', $oembed[1]); // Put's the URL into [1]
    $oembed_url = $oembed[1][0]; // Get the URL from the array
    $platform = jellypress_get_video_platform($oembed_url); // Finds the platform from the URL

    if ($platform === 'vimeo') {

      if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $oembed_url, $match)) {
        // Strip out the video ID
        $video_id = $match[3];
      }

      $vimeo_data = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php")); // Use Vimeo API to get video information
      $video_thumbnail_lq = $vimeo_data[0]['thumbnail_medium']; // Get a mid res thumbnail
      $video_thumbnail_hq = $vimeo_data[0]['thumbnail_large']; // Get a high res thumbnail

      // Vimeo params
      $params = array(
        'byline'        => 0,
        'portrait'      => 0,
        'title'         => 0,
        'autoplay'      => 0,
        'color'         => '#ff0000'
      );
      $oembed_url = add_query_arg($params, $oembed_url);
      wp_enqueue_script('vimeo-api');
    } elseif ($platform === 'youtube') {
      if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $oembed_url, $match)) {
        // Strip out the video ID
        $video_id = $match[1];
      }
      $video_thumbnail_lq = 'https://i.ytimg.com/vi/' . $video_id . '/mqdefault.jpg'; // Get a mid res thumbnail
      $video_thumbnail_hq = 'https://i.ytimg.com/vi/' . $video_id . '/maxresdefault.jpg'; // Get a high res thumbnail
      // YouTube params
      $params = array(
        'rel'            => 0,
        'autoplay'       => 0,
        'color'          => 'white',
        'modestbranding' => 1,
        'enablejsapi'    => 1,
        'controls'       => 1,
        'version'        => 3,
        'origin'         => str_replace(array('http://', 'https://'), '', get_option('home'))
      );
      $oembed_url = str_replace(array('youtube.com', 'youtu.be'), "youtube-nocookie.com", $oembed_url); // Use No Cookie version of YouTube
      $oembed_url = add_query_arg($params, $oembed_url); // Add query vars to URL
      wp_enqueue_script('youtube-api');
    }
    if ($platform) { ?>
      <div class="video-wrapper<?php if ($autoplay) echo ' video-autoplay'; ?>">
        <div class="video-overlay has-bg-img" style="background-image:url('<?php echo $video_thumbnail_lq; ?>')" data-bg-img="<?php echo $video_thumbnail_hq; ?>">
          <button class="play platform-<?php esc_attr_e($platform); ?>" data-src="<?php echo esc_url($oembed_url); ?>" title="<?php _e('Play Video', 'jellypress'); ?>"><?php echo jellypress_icon('play'); ?></button>
        </div>
        <div class="embed-container ratio-<?php echo $aspect_ratio; ?>">
          <iframe width="640" height="390" type="text/html" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
      </div>
<?php } // if ( $platform )
  } // if ( isset( $oembed[1] ))
}
