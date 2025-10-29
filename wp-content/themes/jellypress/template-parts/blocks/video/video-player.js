let ezpzVideoWrapper; // Temporary variable to store the video wrapper that was clicked

/**
 * Platform-specific configuration
 */
const PLATFORM_CONFIG = {
  vimeo: {
    cookieName: "embeddedVimeoConsent",
    className: "platform-vimeo",
  },
  youtube: {
    cookieName: "embeddedYoutubeConsent",
    className: "platform-youtube",
  },
};

/**
 * Set a cookie with 1 year expiration
 */
function setConsentCookie(cookieName) {
  const date = new Date();
  date.setTime(date.getTime() + 365 * 24 * 60 * 60 * 1000);
  document.cookie = `${cookieName}=true; expires=${date.toUTCString()}; path=/`;
}

/**
 * Handle GDPR consent for embedded videos
 */
function handleConsent(platform) {
  // Check if Complianz GDPR plugin is available
  if (typeof cmplz_has_consent === "function") {
    if (cmplz_has_consent("marketing")) {
      return true;
    }

    // Request consent and grant it
    cmplz_set_consent("marketing", "allow");
    cmplz_fire_categories_event();
    cmplz_track_status();
    return true;
  }

  // Fallback: set platform-specific cookie
  setConsentCookie(PLATFORM_CONFIG[platform].cookieName);
  return true;
}

/**
 * Check if video can play based on consent requirements
 */
function canPlayVideo($button, platform) {
  // If no consent required, allow playback
  if (!$button.hasClass("requires-consent")) {
    return true;
  }
  return handleConsent(platform);
}

/**
 * Start video playback
 */
function startVideoPlayback($wrapper, $iframe) {
  $wrapper.addClass("playing");
  $iframe.removeAttr("srcdoc");
}

/**
 * Function to play video (Vimeo or YouTube)
 */
function ezpzPlayVideo(videoWrapper) {
  const $ = jQuery;
  ezpzVideoWrapper = videoWrapper;

  const $wrapper = videoWrapper;
  const $button = $wrapper.find(".play");
  const $iframe = $wrapper.find("iframe");

  // Determine platform
  let platform = null;
  if ($button.hasClass(PLATFORM_CONFIG.vimeo.className)) {
    platform = "vimeo";
  } else if ($button.hasClass(PLATFORM_CONFIG.youtube.className)) {
    platform = "youtube";
  }

  // Exit if platform not recognized
  if (!platform) {
    console.warn("Unknown video platform");
    return;
  }

  // Check consent and play if allowed
  if (canPlayVideo($button, platform)) {
    startVideoPlayback($wrapper, $iframe);
  }
}

/**
 * Event listeners
 */
jQuery(document).on("click touch", ".video-wrapper .play", function () {
  ezpzPlayVideo(jQuery(this).closest(".video-wrapper"));
});

jQuery(document).on("click touch", ".video-wrapper", function () {
  ezpzPlayVideo(jQuery(this));
});
