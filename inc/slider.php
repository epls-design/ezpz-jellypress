<?php
/**
 * Functions which support Splide slider
 * @link https://splidejs.com/category/users-guide/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if (!function_exists('jellypress_splide_init')):
  // Note: Breakpoints are below(val) rather than above, that is why perPage starts at items_lg
  function jellypress_splide_init(
    $slider_id,
    $items_xs = 1,
    $items_sm = 1,
    $items_md = 2,
    $items_lg = 3,
    $arrows='false',
    $dots='false',
    $interval=4000,
    $speed=400,
    $autoplay=true,
    $autoWidth=false,
    $type='loop', // 'slide', 'loop' or 'fade'
    $destroy= false
    ) {
    $output =
      "<script type='text/javascript'>
      new Splide( '$slider_id', {
        type: '$type',
        rewind: true, // Ignored in loop mode
        autoplay: '$autoplay',
        interval: '$interval',
        focus: 'center',
        speed: '$speed',
        arrowPath: 'M20,0l-3.6,3.6l13.8,13.8H0v5.2h30.1L16.4,36.4L20,40l20-20L20,0z',
        arrows: $arrows,
        autoWidth: '$autoWidth',
        pagination: $dots,
        perPage: '$items_lg',
        destroy: '$destroy',
        breakpoints: {
          600: {
            perPage: '$items_xs'
          },
          900: {
            perPage: '$items_sm'
          },
          1200: {
            perPage: '$items_md'
          },
        },
       } ).mount();
      </script>";
    $func = function () use($output) {
      print $output;
    };
    wp_enqueue_script('splide-slider');
    return $func;
  }
endif;
