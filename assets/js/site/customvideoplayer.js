/**
 * Function to play youTube embedded video
 */
function playYouTubeVideo() {
  event.target.playVideo();
}

function playVimeoVideo() {
  event.target.play();
  //vimeoPlayer.play();
}

(function ($) {

  $(document).on('click touch', '.video-wrapper', function () {
    var $wrapper = $(this),
        $button = $wrapper.find('.play'),
        $iframe = $wrapper.find('iframe'),
        iframe = $iframe[0];

    $wrapper.addClass('playing'); // Fades out the overlay

    if ($button.hasClass('platform-vimeo')) {
      if (typeof $iframe.attr('src') === 'undefined') {
        $iframe.attr('src', $button.data('src')); // Insert the src from the button's data-attr
        vimeoPlayer = new Vimeo.Player(iframe); // Create Vimeo Player
        vimeoPlayer.on('loaded', playVimeoVideo); // Play when loaded
      } else {
        // If the video already has a src, play it
        vimeoPlayer = new Vimeo.Player(iframe);
        playVimeoVideo();
      }
      vimeoPlayer.play();
    }
    else if ($button.hasClass('platform-youtube')) {
      if (typeof $iframe.attr('src') === 'undefined') {
        $iframe.attr('src', $button.data('src')); // Insert the src from the button's data-attr

        var youTubePlayer = new YT.Player(iframe, {
          // Use the YouTube API to create a new player
          events: {
            "onReady": playYouTubeVideo,
            //"onError": function (e) {
            //  console.log(e);
            //}
          },
        });

        // Fallback if the API doesn't work
        if (typeof youTubePlayer.playVideo === 'undefined') {
          youTubePlayer = $iframe;
          $iframe.attr('src', $iframe.attr('src').replace('autoplay=0', 'autoplay=1'));
        }
      }
      else {
        // If the video already has a src, play it
        var youTubePlayer = new YT.Player(iframe);
        playYouTubeVideo;
      }
  }
  });
})(jQuery);
