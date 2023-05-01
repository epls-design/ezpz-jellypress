<?php

/**
 * Various php functions which act as wrappers for Javascript, to output JS in the footer.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Native Javascript function to initialise a countdown clock
 *
 * @param string $element_id The id of the element to attach the countdown to
 * @param datetime $deadline The date and time to countdown to
 * @return void
 */
function jellypress_countdown_init($element_id, $deadline) {
  // FIXME: Countdowns dont seem to work in Safari?
  $output =
    "<script type='text/javascript'>
      const countTo = '$deadline';
      jfInitializeClock('$element_id', countTo);
      </script>";
  $output = str_replace(array("\r", "\n", "  "), '', $output) . "\n";
  $func = function () use ($output) {
    print $output;
  };
  return $func;
}

/**
 * Initialises a Splide Slider.
 * @link https://splidejs.com/category/users-guide/
 * NOTE: In the longer term it might be better to have this all as a init.js file, and to use inline data attributes to pass the settings to the JS.
 */
function jellypress_splide_init(
  // Note: Breakpoints are below(val) rather than above, that is why perPage starts at items_lg
  // Note: If you change the breakpoint settings in the SCSS you should change them here too.
  $slider_id,
  $items_xs = 1,
  $items_sm = 1,
  $items_md = 2,
  $items_lg = 3,
  $arrows = 'false',
  $dots = 'false',
  $interval = 4000,
  $speed = 400,
  $autoplay = 'true',
  $autoWidth = 'false',
  $type = 'loop', // 'slide', 'loop' or 'fade'
  $destroy = 'false'
) {
  wp_enqueue_script('splide-slider');

  if ($arrows == 'slider') $arrows = "'" . $arrows . "'";
  // todo: decide when focus: is necessary
  $output =
    "<script type='text/javascript'>
    new Splide( '$slider_id', {
      type: '$type',
      rewind: true,
      autoplay: $autoplay,
      interval: $interval,
      speed: $speed,
      arrows: $arrows,
      pagination: $dots,
      autoWidth: $autoWidth,
      destroy: $destroy,
      perPage: $items_lg,
      gap: '16px',
      breakpoints: {
        1800: {
          gap: '40px'
        },
        1200: {
          perPage: $items_md,
          gap: '35px'
        },
        900: {
          perPage: $items_sm,
          gap: '30px'
        },
        600: {
          perPage: $items_xs,
          gap: '20px'
        },
      },
    } ).mount();
    </script>";
  // Add in if required:
  // focus: 'center',
  // arrowPath: 'M20,0l-3.6,3.6l13.8,13.8H0v5.2h30.1L16.4,36.4L20,40l20-20L20,0z',
  $output = str_replace(array("\r", "\n", "  "), '', $output) . "\n";
  $func = function () use ($output) {
    print $output;
  };
  return $func;
}

/**
 * Initialises a Magnific Popup modal.
 * @link https://dimsemenov.com/plugins/magnific-popup/documentation.html#content-types
 *
 * @param string $modal_selector -> selector eg #element_id or .element_id used to initiate MagnificPopup
 * @param string $delegate -> selector eg .element_id used to delegate MagnificPopup
 * @param bool $is_gallery -> true if the modal is a gallery
 * @param string $modal_type -> 'image', 'iframe', 'inline', 'ajax', 'html' or 'video'
 * @param string $close_button_inside -> 'true' or 'false' whether the close button is inside the modal or not
 * @return void
 */
function jellypress_modal_init($modal_selector, $delegate = null, $is_gallery = true, $modal_type = 'image', $close_button_inside = 'false') {
  wp_enqueue_script('magnific-popup');

  // Code to output in the DOM
  $output =
    '<script type="text/javascript">
    (function($) {
      $("' . $modal_selector . '").magnificPopup({
        type: "' . $modal_type . '",';
  if ($delegate) $output .= '
        delegate: "' . $delegate . '", ';
  if ($is_gallery) $output .= '
        gallery:{
          enabled: true,
          preload: [2,2]
        },';
  $output .= '
        closeBtnInside: ' . $close_button_inside . ',
        removalDelay: 400,
        mainClass: "modal-fade"
      });
    })( jQuery );
</script>
  ';
  $output = str_replace(array("\r", "\n", "  "), '', $output) . "\n";
  $func = function () use ($output) {
    print $output;
  };
  return $func;
}
