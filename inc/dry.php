<?php
/**
 * DON'T REPEAT YOURSELF!!!!
 * Miscellaneous functions that reduce repetition in the theme code.
 * E.g. querying and printing the same fields from ACF
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! function_exists( 'jellypress_show_cta_buttons' ) ) :
  /**
   * Prints HTML with meta information for the current author.
   */
  function jellypress_show_cta_buttons() {
    if ( have_rows( 'buttons' ) ) :
      echo '<div class="button-group">';
        while ( have_rows( 'buttons' ) ) : the_row();

          // Default button class and get variables from ACF
          $button_classes = 'button';
          $button_link = get_sub_field( 'button_link' );
          $button_style = get_sub_field( 'button_style' );

          if($button_style!='filled') {
            // 'filled' is the default state so we don't need a class for this
            $button_classes.= ' button__'.$button_style;
          };

          echo '<a class="'.$button_classes.'" href="'.$button_link['url'].'" target="'.$button_link['target'].'">'.$button_link['title'].'</a>';
        endwhile;
      echo '</div>';
      endif;
  }
endif;
