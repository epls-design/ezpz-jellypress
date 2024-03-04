let ezpzVideoWrapper; // Temporary variable to store the video wrapper that was clicked

/**
 * Function to play youTube embedded video
 */
function ezpzPlayYoutube() {
  event.target.playVideo();
}
/**
 * Function to play Vimeo video
 */
function ezpzPlayVimeo() {
  event.target.play();
  //vimeoPlayer.play();
}

/**
 * Function to handle YouTube consent
 */
function ezpzYTConsent(consent) {
  if (consent == true) {
    localStorage.setItem("youtubeConsent", "true");
    ezpzPlayVideo(ezpzVideoWrapper);
  } else {
    localStorage.setItem("youtubeConsent", "false");
  }

  // Close modal then remove all traces of it and any other modals
  let modals = document.querySelectorAll(".modal.gdpr");
  modals.forEach(function (modal) {
    modal.close();
    modal.remove();
  });
}

/**
 * Function to display consent modal
 */
function ezpzConsentModal() {
  // Create a new dialog element in the dom with class .modal
  var modal = document.createElement("dialog");
  modal.classList.add("modal");
  modal.classList.add("bg-white");
  modal.classList.add("gdpr");
  modal.innerHTML = `
    <div class="modal-content gdpr">
      <h2>Cookie Consent</h2>
      <p>Throughout our website we use YouTube to host video content. By playing video content on this website, you agree to allow YouTube to set cookies on your device. Please see our <a href="/cookies" target="_blank">cookie policy</a> for more information.</p>
        <div class="button-list">
        <button class="button" onclick="ezpzYTConsent(true);">I consent and wish to play this video</button>
        <button class="button ghost neutral small" onclick="ezpzYTConsent(false);">I do not consent</button>
      </div>
    </div>
  `;

  // Append the dialog to the body
  document.body.appendChild(modal);

  // Open the dialog
  modal.showModal();

  // Focus on the first button in the modal
  modal.querySelector("button").focus();
}

function ezpzPlayVideo(videoWrapper) {
  let $ = jQuery;
  ezpzVideoWrapper = videoWrapper;
  var $wrapper = videoWrapper,
    $button = $wrapper.find(".play"),
    $iframe = $wrapper.find("iframe"),
    iframe = $iframe[0];

  if ($button.hasClass("platform-vimeo")) {
    if (typeof $iframe.attr("src") === "undefined") {
      $iframe.attr("src", $button.data("src")); // Insert the src from the button's data-attr
      vimeoPlayer = new Vimeo.Player(iframe); // Create Vimeo Player
      vimeoPlayer.on("loaded", ezpzPlayVimeo); // Play when loaded
    } else {
      // If the video already has a src, play it
      vimeoPlayer = new Vimeo.Player(iframe);
      ezpzPlayVimeo();
    }
    vimeoPlayer.play();
    $wrapper.addClass("playing"); // Fades out the overlay
  } else if ($button.hasClass("platform-youtube")) {
    if (localStorage.getItem("youtubeConsent") === "true") {
      // User consented, proceed with video playback
      let closestFigure = videoWrapper.closest("figure");
      if (closestFigure.length) {
        closestFigure.find(".gdpr-youtube").fadeOut(200);
      }

      if (typeof $iframe.attr("src") === "undefined") {
        $iframe.attr("src", $button.data("src")); // Insert the src from the button's data-attr

        var youTubePlayer = new YT.Player(iframe, {
          // Use the YouTube API to create a new player
          events: {
            onReady: ezpzPlayYoutube,
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
        ezpzPlayYoutube();
      }
      $wrapper.addClass("playing"); // Fades out the overlay
    } else {
      return ezpzConsentModal();
    }
  }
}

jQuery(document).on("click touch", ".video-wrapper", function () {
  ezpzPlayVideo(jQuery(this));
});

/**
 * Autoplay video with class .video-autoplay when scroll into view, if browser supports it
 * TODO: Check Cookies with Vimeo and only play if user has consented
 */
function ezpzPlayVideoOnScroll(element) {
  let $element = jQuery(element); // Convert to jQuery element and get first result
  $button = $element.find(".play");
  ezpzPlayVideo($button.closest(".video-wrapper"));
}

/**
 * Sets up intersection observers for .video-autoplay on document ready
 */
jQuery(document).ready(function () {
  if ("IntersectionObserver" in window) {
    let ezpzVideoObserver = new IntersectionObserver(
      function (entries, observer) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            ezpzPlayVideoOnScroll(entry.target);
            ezpzVideoObserver.unobserve(entry.target);
          }
        });
      },
      { rootMargin: "0px 0px -50px 0px" }
    );

    let ezpzObservedVideos = [].slice.call(
      document.querySelectorAll(".video-autoplay")
    );
    ezpzObservedVideos.forEach(function (ezpzVideo) {
      ezpzVideoObserver.observe(ezpzVideo);
    });
  }
});
