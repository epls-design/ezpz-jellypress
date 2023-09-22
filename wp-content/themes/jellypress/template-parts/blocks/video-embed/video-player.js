/**
 * Function to play youTube embedded video
 */
function jellypressPlayYoutube() {
  event.target.playVideo();
}

function jellypressPlayVimeo() {
  event.target.play();
  //vimeoPlayer.play();
}

(function ($) {
  function jellypressPlayVideo(videoWrapper) {
    var $wrapper = videoWrapper,
      $button = $wrapper.find(".play"),
      $iframe = $wrapper.find("iframe"),
      iframe = $iframe[0];

    // console.log('button', $button);
    // console.log('wrapper', $wrapper);
    // console.log('iframe', iframe);

    $wrapper.addClass("playing"); // Fades out the overlay

    if ($button.hasClass("platform-vimeo")) {
      if (typeof $iframe.attr("src") === "undefined") {
        $iframe.attr("src", $button.data("src")); // Insert the src from the button's data-attr
        vimeoPlayer = new Vimeo.Player(iframe); // Create Vimeo Player
        vimeoPlayer.on("loaded", jellypressPlayVimeo); // Play when loaded
      } else {
        // If the video already has a src, play it
        vimeoPlayer = new Vimeo.Player(iframe);
        jellypressPlayVimeo();
      }
      vimeoPlayer.play();
    } else if ($button.hasClass("platform-youtube")) {
      if (typeof $iframe.attr("src") === "undefined") {
        $iframe.attr("src", $button.data("src")); // Insert the src from the button's data-attr

        var youTubePlayer = new YT.Player(iframe, {
          // Use the YouTube API to create a new player
          events: {
            onReady: jellypressPlayYoutube,
            //"onError": function (e) {
            //  console.log(e);
            //}
          },
        });

        // Fallback if the API doesn't work
        if (typeof youTubePlayer.playVideo === "undefined") {
          youTubePlayer = $iframe;
          $iframe.attr(
            "src",
            $iframe.attr("src").replace("autoplay=0", "autoplay=1")
          );
        }
      } else {
        // If the video already has a src, play it
        var youTubePlayer = new YT.Player(iframe);
        jellypressPlayYoutube;
      }
    }
  }

  $(document).on("click touch", ".video-wrapper", function () {
    jellypressPlayVideo($(this));
  });

  /**
   * Autoplay video with class .video-autoplay when scroll into view, if browser supports it
   */
  function jellypressPlayVideoOnScroll(element) {
    let $element = $(element); // Convert to jQuery element and get first result
    $button = $element.find(".play");
    jellypressPlayVideo($button.closest(".video-wrapper"));
  }

  /**
   * Sets up intersection observers for .video-autoplay on document ready
   */
  $(document).ready(function () {
    if ("IntersectionObserver" in window) {
      let jellypressVideoObserver = new IntersectionObserver(
        function (entries, observer) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              jellypressPlayVideoOnScroll(entry.target);
              jellypressVideoObserver.unobserve(entry.target);
            }
          });
        },
        { rootMargin: "0px 0px -50px 0px" }
      );

      let jellypressObservedVideos = [].slice.call(
        document.querySelectorAll(".video-autoplay")
      );
      jellypressObservedVideos.forEach(function (jellypressVideo) {
        jellypressVideoObserver.observe(jellypressVideo);
      });
    }
  });
})(jQuery);
