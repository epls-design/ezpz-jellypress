<?php
/**
 * Functions to help with embedding videos
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( !function_exists( 'jellypress_get_video_platform' )) :
  /**
   * Determines the platform of a video by analysing the URL
   *
   * @param string $video The URL that should be embedded.
   *
   */
  function jellypress_get_video_platform( $video ) {
    $platform = false;
    if ( strpos( $video, 'vimeo.com' ) !== false ) {
        $platform = 'vimeo';
    } elseif (
        strpos( $video, 'youtube.com' ) !== false ||
        strpos( $video, 'youtu.be' !== false ) ||
        strpos( $video, 'youtube-nocookie.com' !== false )) {
        $platform = 'youtube';
    }
    return $platform;
  }
endif;

if ( !function_exists( 'jellypress_embed_video' )) :
  /**
   * Prepares and displays an oembed video with play button
   *
   * @param string $video The URL that should be embedded.
   *
   */
  function jellypress_embed_video ( $video ) {

      $oembed = wp_oembed_get( $video ); // Full oEmbed Code
      $oembed = explode( ' src="', $oembed ); // Explode to put the URL and options into [1]
      if ( isset( $oembed[1] )) {
        $oembed[1] = explode( '" ', $oembed[1] ); // Put's the URL into [1]
        $oembed_url = $oembed[1][0]; // Get the URL from the array
        $platform = jellypress_get_video_platform( $oembed_url ); // Finds the platform from the URL

        if ( $platform === 'vimeo' ) {

          if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $oembed_url, $match)) {
            // Strip out the video ID
            $video_id = $match[3];
          }

          $vimeo_data = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$video_id.php")); // Use Vimeo API to get video information
          $video_thumbnail = $vimeo_data[0]['thumbnail_large']; // Set Thumbnail

          // Vimeo params
          $params = array (
              'byline'        => 0,
              'portrait'      => 0,
              'title'         => 0,
              'autoplay'      => 0,
              'color'         => '#ff0000'
          );
          $oembed_url = add_query_arg( $params, $oembed_url );
          wp_enqueue_script('vimeo-api');

        } elseif ( $platform === 'youtube' ) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $oembed_url, $match)) {
              // Strip out the video ID
              $video_id = $match[1];
            }
            $video_thumbnail = 'https://i.ytimg.com/vi/'.$video_id.'/maxresdefault.jpg'; // Get a high res thumbnail
            // YouTube params
            $params = array (
                'rel'            => 0,
                'autoplay'       => 0,
                'color'          => 'white',
                'modestbranding' => 1,
                'enablejsapi'    => 1,
                'controls'       => 1,
                'version'        => 3,
                'origin'         => str_replace( array( 'http://', 'https://' ), '', get_option( 'home' ))
            );
            $oembed_url = str_replace(array('youtube.com','youtu.be'),"youtube-nocookie.com",$oembed_url); // Use No Cookie version of YouTube
            $oembed_url = add_query_arg( $params, $oembed_url ); // Add query vars to URL
            wp_enqueue_script( 'youtube-api' );
        }
      if ( $platform ) { ?>
        <div class="video-wrapper">
          <div class="video-overlay" style="background-image:url('<?php echo $video_thumbnail;?>')">
            <button class="play platform-<?php esc_attr_e( $platform ); ?>" data-src="<?php echo esc_url( $oembed_url ); ?>" title="<?php _e('Play Video','jellypress');?>"><?php echo jellypress_icon( 'play' ); ?></button>
          </div>
          <div class="embed-container">
            <iframe width="640" height="390" type="text/html" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
        </div>
<?php } // if ( $platform )
    } // if ( isset( $oembed[1] ))
  }
endif;
