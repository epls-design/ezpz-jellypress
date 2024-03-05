<?php

/**
 * Functions necessary for the buttons block
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Loops through an array and displays buttons if the array is not empty
 * Uses data from ACF repeater field
 */
function jellypress_display_cta_buttons($buttons, $bg_color, $classes = null) {
  if ($buttons) :
    if (isset($classes)) echo '<div class="button-list ' . $classes . '">';
    else echo '<div class="button-list">';

    switch ($bg_color) {
      case 'primary-500':
      case 'secondary-500':
      case 'black';
        $button_color = 'white';
        break;
      default:
        $button_color = ''; // Default to theme button colour
    }

    foreach ($buttons as $button) :

      // Default button class and get variables from ACF
      $button_classes = 'button';
      $button_link = $button['button_link'];
      $button_style = $button['button_style'];

      if ($button_color != null)
        $button_classes .= ' ' . $button_color;

      if ($button_style != 'filled') {
        // 'filled' is the default state so we don't need a class for this
        $button_classes .= ' ' . $button_style;
      };

      // Check if the URL is external, if so add a rel="external"
      $is_external = is_link_external($button_link['url']);
      if (!$is_external) {
        echo '<a class="' . $button_classes . '" href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" target="' . $button_link['target'] . '">' . $button_link['title'] . '</a>';
      } else {
        // Outbound link - add rel=external and force target=_blank
        $button_link['target'] = '_blank';
        echo '<a class="' . $button_classes . '" href="' . $button_link['url'] . '" title="' . $button_link['title'] . '" target="' . $button_link['target'] . '" rel="external">' . $button_link['title'] . '</a>';
      }
    endforeach;
    echo '</div>';
  endif;
}