<?php

/**
 * Useful Helper functions and snippets
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Helper function to set up the attributes for a custom Gutenberg block
 *
 * @param array $block Gutenberg block
 * @return array $attributes Array of attributes for the block
 */
function jellypress_get_block_attributes($block) {
  $block_classes = 'block block-' . sanitize_title($block['title']);
  $block_type = $block['name'];

  // Merge in block classes from Gutenberg Editor
  if (isset($block['className'])) $block_classes .= ' ' . $block['className'];

  // Add background colour class if set
  // Example of how to override the background colour for a specific block

  $excluded_blocks = [
    'ezpz/page-hero',
    'ezpz/post-hero',
  ];
  if (in_array($block_type, $excluded_blocks)) {
    echo 'DO NOTHING';
    // Do nothing as it's set in the view.php
  } elseif (isset($block['backgroundColor'])) {
    $block_classes .= ' bg-' . $block['backgroundColor'];
  } else {
    $block_classes .= ' bg-white';
  }

  $bg_color = isset($block['backgroundColor']) ? $block['backgroundColor'] : 'white';

  /**
   * We are in a child block, so lets set the bg_color to the parent block's bg color
   */
  if (isset($block['parent'])) {
    isset($block['parent']['backgroundColor']) ? $bg_color = $block['parent']['backgroundColor'] : $bg_color;
  }

  // Remove 'is-style- ' from the block classes
  $block_classes = str_replace('is-style-', '', $block_classes);

  $attributes = array(
    'anchor' => isset($block['anchor']) ? "id='" . esc_attr($block['anchor']) . "'" : '',
    'class' => $block_classes,
    'block_id' => $block['id'],
    'bg_color' => $bg_color,
    'text_align' => isset($block['alignText']) ? 'text-' . $block['alignText'] : 'text-left',
  );

  if (isset($block['alignContent']))  $attributes['align_content'] = sanitize_title($block['alignContent']);

  if (!empty($block['align'])) $attributes['align'] = $block['align'];

  return $attributes;
}

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
function jellypress_icon($icon, $class = '') {
  // Define SVG sprite file.
  $icon_path = get_theme_file_path('/dist/icons/' . $icon . '.svg');
  // If it exists, include it.
  if (file_exists($icon_path)) {
    $use_link = get_template_directory_uri() . '/dist/icons/icons.svg?v=' . filemtime($icon_path) . '#icon-' . $icon;
    // Append filemtime to link to prevent caching in development.
    $classes = [
      'icon',
      'icon-' . $icon,
    ];
    if ($class) {
      $classes[] = $class;
    }
    return '<svg class="' . implode(" ", $classes) . '"><use xlink:href="' . $use_link . '" /></use></svg>';
  } else {
    return '';
  }
}

/**
 * Determine if a link is external
 *
 * @param string $url URL to check
 * @return bool True if the URL is external
 */
function is_link_external($url) {
  $url_host = parse_url($url, PHP_URL_HOST);
  $site_host = parse_url(get_site_url(), PHP_URL_HOST);
  return ($url_host && $url_host !== $site_host);
}

/**
 * Loops through an array and displays buttons if the array is not empty
 * Uses data from ACF repeater field
 */
function jellypress_display_cta_buttons($buttons, $bg_color, $classes = null) {
  if ($buttons) :
    if (isset($classes)) echo '<div class="button-list ' . $classes . '">';
    else echo '<div class="button-list">';

    switch ($bg_color) {
      case 'primary-500':
      case 'secondary-500':
      case 'black';
        $button_color = 'white';
        break;
      default:
        $button_color = ''; // Default to theme button colour
    }

    foreach ($buttons as $button) :

      // Default button class and get variables from ACF
      $button_classes = 'button';
      $button_link = $button['button_link'];
      $button_style = $button['button_style'];

      if ($button_color != null)
        $button_classes .= ' ' . $button_color;

      if ($button_style != 'filled') {
        // 'filled' is the default state so we don't need a class for this
        $button_classes .= ' ' . $button_style;
      };

      // Check if the URL is external, if so add a rel="external"
      $is_external = is_link_external($button_link['url']);
      if (!$is_external) {
        echo '<a class="' . $button_classes . '" href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" target="' . $button_link['target'] . '">' . $button_link['title'] . '</a>';
      } else {
        // Outbound link - add rel=external and force target=_blank
        $button_link['target'] = '_blank';
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
          echo $location_tooltip_text;
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

  if (strpos($video, 'iframe') === false) {
    $oembed = wp_oembed_get($video); // Full oEmbed Code
  } else {
    $oembed = $video;
  }

  // Get the title if it exists in the oembed code
  if (strpos($oembed, ' title="') !== false) {
    $title = explode(' title="', $oembed);
    $title = explode('"', $title[1]);
    $title = $title[0];
  } else {
    $title = '';
  }

  $oembed = explode(' src="', $oembed); // Explode to put the URL and options into [1]
  if (isset($oembed[1])) {
    $oembed[1] = explode('" ', $oembed[1]); // Put's the URL into [1]
    $oembed_url = $oembed[1][0]; // Get the URL from the array
    $platform = jellypress_get_video_platform($oembed_url); // Finds the platform from the URL

    if ($platform === 'vimeo') {

      if (!$video_thumbnail_lq) {

        if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $oembed_url, $match)) {
          // Strip out the video ID
          $video_id = $match[3];
        }

        $vimeo_data = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php")); // Use Vimeo API to get video information
        $video_thumbnail_lq = $vimeo_data[0]['thumbnail_medium']; // Get a mid res thumbnail
        $video_thumbnail_hq = $vimeo_data[0]['thumbnail_large']; // Get a high res thumbnail

      }

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

      if (!$video_thumbnail_lq) {

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $oembed_url, $match)) {
          // Strip out the video ID
          $video_id = $match[1];
        }
        $video_thumbnail_lq = 'https://i.ytimg.com/vi/' . $video_id . '/mqdefault.jpg'; // Get a mid res thumbnail
        $video_thumbnail_hq = 'https://i.ytimg.com/vi/' . $video_id . '/maxresdefault.jpg'; // Get a high res thumbnail

      }

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
      <figure class="video-wrapper<?php if ($autoplay) echo ' video-autoplay'; ?>">
        <div class="video-overlay has-bg-img" style="background-image:url('<?php echo $video_thumbnail_lq; ?>')" data-bg-img="<?php echo $video_thumbnail_hq; ?>">
          <button class="play platform-<?php esc_attr_e($platform); ?>" data-src="<?php echo esc_url($oembed_url); ?>" title="<?php _e('Play Video', 'jellypress'); ?>"><?php echo jellypress_icon('play'); ?></button>
        </div>
        <div class="embed-container ratio-<?php echo $aspect_ratio; ?>">
          <iframe width="640" height="390" type="text/html" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="<?php echo $title; ?>"></iframe>
        </div>
      </figure>
<?php } // if ( $platform )
  } // if ( isset( $oembed[1] ))
}
