<?php

/**
 * Functions to help with embedding videos
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;


/*************************************
 * FUNCTIONS TO HELP WITH EMBEDDING
 * VIDEO CONTENT FROM VIMEO OR YOUTUBE
 *************************************/

/**
 * Determines the video platform from the URL
 */
function jellypress_get_video_platform($url) {
  // Check if the URL is for YouTube
  if (
    strpos($url, 'yout') !== false
  ) {
    return 'youtube';
  }
  // Check if the URL is for Vimeo
  elseif (strpos($url, 'vimeo') !== false) {
    return 'vimeo';
  }
  // If the URL is not for YouTube or Vimeo
  else {
    return 'unknown';
  }
}

/**
 * Function which processes a video (by full oembed or just Url) and returns the platform, thumbnails, title and oembed URL
 */
function jellypress_get_video_information($video, $platform = null) {

  // Store the data for return later
  $data = [];

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


    if (!$platform) {
      $platform = jellypress_get_video_platform($oembed_url);
    }

    if ($platform === 'vimeo') {

      if (!isset($video_thumbnail_lq)) {

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
        'autoplay'      => 1,
        'dnt'           => 1
      );
      $oembed_url = add_query_arg($params, $oembed_url);
    } elseif ($platform === 'youtube') {

      if (!isset($video_thumbnail_lq)) {

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $oembed_url, $match)) {
          // Strip out the video ID
          $video_id = $match[1];
        }
        $video_thumbnail_lq = 'https://i.ytimg.com/vi/' . $video_id . '/mqdefault.jpg'; // Get a mid res thumbnail
        $video_thumbnail_sq = 'https://i.ytimg.com/vi/' . $video_id . '/sddefault.jpg'; // Get a standard res thumbnail
        $video_thumbnail_hq = 'https://i.ytimg.com/vi/' . $video_id . '/maxresdefault.jpg'; // Get a high res thumbnail

        // If the file size of the HQ thumbnail is 1097 bytes, then the high res thumbnail doesn't exist, so use the mid res one instead
        $hq_headers = get_headers($video_thumbnail_hq, 1);
        $sq_headers = get_headers($video_thumbnail_sq, 1);

        if ($hq_headers['Content-Length'] == '1097') {
          if ($sq_headers['Content-Length'] != '1097') {
            $video_thumbnail_hq = $video_thumbnail_sq;
          } else {
            $video_thumbnail_hq = $video_thumbnail_lq;
          }
        }
      }

      // YouTube params
      $params = array(
        'rel'            => 0,
        'autoplay'       => 1,
        'modestbranding' => 1,
        'enablejsapi'    => 1,
        'controls'       => 1,
        'version'        => 3,
        'origin'         => get_option('home')
      );
      $oembed_url = add_query_arg($params, $oembed_url); // Add query vars to URL
    }
  }

  $data = array(
    'oembed_url' =>  $oembed_url,
    'video_thumbnail_lq' => $video_thumbnail_lq,
    'video_thumbnail_hq' => $video_thumbnail_hq,
    'title' => $title,
    'platform' => $platform
  );

  return $data;
}

/**
 * Prepares and displays an oembed video with play button
 * @param string $video The URL that should be embedded.
 */
function jellypress_embed_video($video, $aspect_ratio = '16x9', $platform = null, $caption = null) {

  $video_info = jellypress_get_video_information($video, $platform);

  // ENQUEUE THE APPROPRIATE SCRIPT
  if ($video_info['platform'] == 'youtube') {
    wp_enqueue_script('youtube-api');
  } elseif ($video_info['platform'] == 'vimeo') {
    wp_enqueue_script('vimeo-api');
  }

?>
  <figure>
    <div class="video-wrapper">
      <div class="video-overlay has-bg-img" style="background-image:url('<?php echo $video_info['video_thumbnail_lq']; ?>')" data-bg-img="<?php echo $video_info['video_thumbnail_hq']; ?>">

        <?php
        $has_marketing_consent = false;
        if (class_exists('COMPLIANZ')) {
          $has_marketing_consent = cmplz_has_consent('marketing');
        } else {
          // FALLBACK IF COMPLIANZ IS NOT INSTALLED
          $has_marketing_consent = isset($_COOKIE['youtubeEmbedConsent']) && $_COOKIE['youtubeEmbedConsent'] === 'true';
        }

        if (!$has_marketing_consent && $video_info['platform'] === 'youtube') {
          $button = [
            'class' => 'requires-consent play platform-' . $video_info['platform'],
            'title' => __('Confirm marketing consent to play this video', 'esher'),
            'text' => __('Throughout our website we use YouTube to host video content. By playing this video, you agree to allow YouTube to set marketing cookies on your device. Please see our cookie policy for more information or click here to play.', 'esher')
          ];
        } else {
          $button = [
            'class' => 'play platform-' . $video_info['platform'],
            'title' => __('Play Video', 'jellypress'),
            'text' => jellypress_icon('play')
          ];
        }
        ?>
        <button class="<?php echo $button['class']; ?>" title="<?php echo $button['title']; ?>"><?php echo $button['text']; ?></button>
      </div>
      <div class="embed-container ratio-<?php echo $aspect_ratio; ?>">
        <iframe width="640" height="390" type="text/html" frameborder="0" src="<?php echo esc_url($video_info['oembed_url']); ?>" srcdoc="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" title="<?php echo $video_info['title']; ?>"></iframe>
      </div>
    </div>
    <?php if ($caption) : ?>
      <figcaption class="wp-element-caption"><?php echo $caption; ?></figcaption>
    <?php endif; ?>
  </figure>
<?php
}

add_action('wp_enqueue_scripts', function () {

  wp_register_script(
    'youtube-api',
    '//www.youtube.com/iframe_api',
    array('video-embed'),
    date('YW'),
    true
  );

  wp_register_script(
    'vimeo-api',
    '//player.vimeo.com/api/player.js',
    array('video-embed'),
    date('YW'),
    true
  );

  wp_register_script(
    'video-embed',
    get_template_directory_uri() . '/template-parts/blocks/video/video-player.js',
    array('jquery'),
    filemtime(get_template_directory() . '/template-parts/blocks/video/video-player.js'),
    true
  );
});
