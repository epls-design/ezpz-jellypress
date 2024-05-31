let ezpzVideoWrapper; // Temporary variable to store the video wrapper that was clicked

/**
 * Function to play Vimeo video
 */
function ezpzPlayVimeo() {
  event.target.play();
  //vimeoPlayer.play();
}

function ezpzPlayVideo(videoWrapper) {
  let $ = jQuery;
  ezpzVideoWrapper = videoWrapper;
  var $wrapper = videoWrapper,
    $button = $wrapper.find(".play"),
    $iframe = $wrapper.find("iframe"),
    iframe = $iframe[0];

  if ($button.hasClass("platform-vimeo")) {
    // No need to check for GDPR compliance for Vimeo videos as DNT is set to 1
    $wrapper.addClass("playing");
    $iframe.removeAttr("srcdoc");
  } else if ($button.hasClass("platform-youtube")) {
    let canPlay = false;
    // Does the button have a class of .requires-consent?
    if ($button.hasClass("requires-consent")) {
      // Does this site have compliance installed? If so, we need to check if the user has consented to marketing cookies
      if (typeof cmplz_has_consent === "function") {
        if (cmplz_has_consent("marketing")) {
          canPlay = true;
        } else {
          cmplz_set_consent("marketing", "allow");
          cmplz_fire_categories_event();
          cmplz_track_status();
          canPlay = true;
        }
      } else {
        // otherwise, set a cookie youtubeEmbedConsent to true for 1 year
        let date = new Date();
        date.setTime(date.getTime() + 365 * 24 * 60 * 60 * 1000);
        document.cookie =
          "youtubeEmbedConsent=true; expires=" +
          date.toUTCString() +
          "; path=/";
        canPlay = true;
      }
    } else {
      // GDPR is accepted already, play the video
      canPlay = true;
    }

    if (canPlay) {
      $wrapper.addClass("playing"); // Fades out the overlay
      $iframe.removeAttr("srcdoc");
    }
  }
}

jQuery(document).on("click touch", ".video-wrapper .play", function () {
  ezpzPlayVideo(jQuery(this).closest(".video-wrapper"));
});

jQuery(document).on("click touch", ".video-wrapper", function () {
  ezpzPlayVideo(jQuery(this));
});
