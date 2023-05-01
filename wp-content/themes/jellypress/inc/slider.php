<?php

/**
 * Functions which support Splide slider
 * @link https://splidejs.com/category/users-guide/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (!function_exists('jellypress_splide_init')) :
  // Note: Breakpoints are below(val) rather than above, that is why perPage starts at items_lg
  // Note: If you change the breakpoint settings in the SCSS you should change them here too.
  function jellypress_splide_init(
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
    wp_enqueue_script('splide-slider');
    return $func;
  }
endif;
