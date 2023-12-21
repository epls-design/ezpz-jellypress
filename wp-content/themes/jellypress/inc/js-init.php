<?php

/**
 * Various php functions which act as wrappers for Javascript, to output JS in the footer.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Initialises a Splide Slider.
 * @link https://splidejs.com/category/users-guide/
 * NOTE: In the longer term it might be better to have this all as a init.js file, and to use inline data attributes to pass the settings to the JS.
 */
function jellypress_splide_init(
  $slider_id,
  $items_xs = 1,
  $items_sm = 1,
  $items_md = 2,
  $items_lg = 3,
  $number_of_slides = null,
  $arrows = false,
  $dots = false,
  $interval = 4000,
  $speed = 400,
  $autoplay = true,
  $type = 'loop' // 'slide', 'loop' or 'fade'
) {
  wp_enqueue_script('splide-slider');

  $options = [
    'type' => $type,
    'rewind' => true,
    'autoplay' => $autoplay,
    'interval' => $interval,
    'speed' => $speed,
    'arrows' => $arrows,
    'pagination' => $dots,
    'autoWidth' => false,
    'perPage' => $items_xs,
    'gap' => '16px',
    'mediaQuery' => 'min',
    'arrowPath' => 'M9.1,32.5l2.2,3.1l21.9-15.4L11.4,4.4L9.1,7.4l17.6,12.7L9.1,32.5z',
    'breakpoints' => [
      1800 => [
        'gap' => '40px'
      ],
      1200 => [
        'perPage' => $items_lg,
        'gap' => '35px'
      ],
      900 => [
        'perPage' => $items_md,
        'gap' => '30px'
      ],
      600 => [
        'perPage' => $items_sm,
        'gap' => '20px'
      ],
    ]
  ];

  // Work out if number of slides is more than perPage and destroy as needed
  if ($number_of_slides) {
    if ($number_of_slides <= $items_xs) {
      // TODO: Doesnt seem to be working as expected
      $options['destroy'] = 'completely';
    } elseif ($number_of_slides <= $items_sm) {
      $options['breakpoints'][600]['destroy'] = true;
    } elseif ($number_of_slides <= $items_md) {
      $options['breakpoints'][900]['destroy'] = true;
    } elseif ($number_of_slides <= $items_lg) {
      $options['breakpoints'][1200]['destroy'] = true;
    }
  }

  // Remove anything thats not a number from the slider_id
  $js_slider_id = 'slider' . preg_replace('/\D/', '', $slider_id);

  // Encode options as JSON
  $options = json_encode($options);

  if ($arrows == 'slider') $arrows = "'" . $arrows . "'";
  $output =
    "<script type='text/javascript'>
    " . $js_slider_id . " = new Splide( '$slider_id', $options ).mount();
    </script>";
  $output = str_replace(array("\r", "\n", "  "), '', $output) . "\n";
  $func = function () use ($output) {
    print $output;
  };
  return $func;
}
