<?php

/**
 * Functions which get the user's design settings from ACF and use them on the front-end
 * Uses a function forked from https://github.com/gdkraus/wcag2-color-contrast/blob/master/wcag2-color-contrast.php to calculate color contrast
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// calculates the luminosity of an given RGB color
// the color code must be in the format of RRGGBB
// the luminosity equations are from the WCAG 2 requirements
// http://www.w3.org/TR/WCAG20/#relativeluminancedef

if (!function_exists('ezpz_calculate_luminosity')) :
  function ezpz_calculate_luminosity($color) {

    $r = hexdec(substr($color, 0, 2)) / 255; // red value
    $g = hexdec(substr($color, 2, 2)) / 255; // green value
    $b = hexdec(substr($color, 4, 2)) / 255; // blue value
    if ($r <= 0.03928) {
      $r = $r / 12.92;
    } else {
      $r = pow((($r + 0.055) / 1.055), 2.4);
    }

    if ($g <= 0.03928) {
      $g = $g / 12.92;
    } else {
      $g = pow((($g + 0.055) / 1.055), 2.4);
    }

    if ($b <= 0.03928) {
      $b = $b / 12.92;
    } else {
      $b = pow((($b + 0.055) / 1.055), 2.4);
    }

    $luminosity = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    return $luminosity;
  }
endif;

// calculates the luminosity ratio of two colors
// the luminosity ratio equations are from the WCAG 2 requirements
// http://www.w3.org/TR/WCAG20/#contrast-ratiodef

if (!function_exists('ezpz_calculate_luminosity_ratio')) :
  function ezpz_calculate_luminosity_ratio($color1, $color2) {
    $l1 = ezpz_calculate_luminosity($color1);
    $l2 = ezpz_calculate_luminosity($color2);

    if ($l1 > $l2) {
      $ratio = (($l1 + 0.05) / ($l2 + 0.05));
    } else {
      $ratio = (($l2 + 0.05) / ($l1 + 0.05));
    }
    return $ratio;
  }
endif;

// returns an array with the results of the color contrast analysis
// it returns akey for each level (AA and AAA, both for normal and large or bold text)
// it also returns the calculated contrast ratio
// the ratio levels are from the WCAG 2 requirements
// http://www.w3.org/TR/WCAG20/#visual-audio-contrast (1.4.3)
// http://www.w3.org/TR/WCAG20/#larger-scaledef

if (!function_exists('ezpz_evaluate_color_contrast')) :
  function ezpz_evaluate_color_contrast($color1, $color2) {
    $ratio = ezpz_calculate_luminosity_ratio($color1, $color2);

    $colorEvaluation["levelAANormal"] = ($ratio >= 4.5 ? 'pass' : 'fail');
    $colorEvaluation["levelAALarge"] = ($ratio >= 3 ? 'pass' : 'fail');
    $colorEvaluation["levelAAMediumBold"] = ($ratio >= 3 ? 'pass' : 'fail');
    $colorEvaluation["levelAAANormal"] = ($ratio >= 7 ? 'pass' : 'fail');
    $colorEvaluation["levelAAALarge"] = ($ratio >= 4.5 ? 'pass' : 'fail');
    $colorEvaluation["levelAAAMediumBold"] = ($ratio >= 4.5 ? 'pass' : 'fail');
    $colorEvaluation["ratio"] = $ratio;

    //return $colorEvaluation;
    return $ratio;
  }
endif;

if (!function_exists('ezpz_get_contrast_color')) :

  function ezpz_get_contrast_color(
    $hexColor,
    $colorOne = null, // We have to do this because we can't use a function as an arg
    $colorTwo = "#ffffff"
  ) {

    if ($colorOne == null) {
      $colorOne = get_field('text_colour', 'option') ? get_field('text_colour', 'option') : '#1f2933'; // Same as neutral, 900
    }

    $contrast_one = ezpz_evaluate_color_contrast($hexColor, $colorOne);
    $contrast_two = ezpz_evaluate_color_contrast($hexColor, $colorTwo);

    if ($contrast_one > $contrast_two) return $colorOne;
    else return $colorTwo;
  }
endif;

add_action('admin_head', 'ezpz_get_theme_design_options');
add_action('wp_head', 'ezpz_get_theme_design_options');


if (!function_exists('ezpz_get_theme_design_options')) :

  function ezpz_get_theme_design_options() {

    $colour_options_data = jellypress_get_acf_fields('60c219d0bd368', 'option');

    // Main colours
    $theme_primary_colour = $colour_options_data['primary_colour'] ? $colour_options_data['primary_colour'] : '#7B91B0';
    $theme_primary_dark = $colour_options_data['primary_colour_dark'] ? $colour_options_data['primary_colour_dark'] : '#3c4e67';
    $theme_primary_light = $colour_options_data['primary_colour_light'] ? $colour_options_data['primary_colour_light'] : '#d2dae5';

    $theme_secondary_colour = $colour_options_data['secondary_colour'] ? $colour_options_data['secondary_colour'] : '#af8a41';
    $theme_secondary_dark = $colour_options_data['secondary_colour_dark'] ? $colour_options_data['secondary_colour_dark'] : '#4a3b1c';
    $theme_secondary_light = $colour_options_data['secondary_colour_light'] ? $colour_options_data['secondary_colour_light'] : '#dbc79e';

    //Text Colours
    $theme_text_colour = $colour_options_data['text_colour'] ? $colour_options_data['text_colour'] : '#191a1a'; // Same as neutral, 900
    $theme_h1_colour = $colour_options_data['h1'] ? $colour_options_data['h1'] : '#3e4041'; // Same as neutral, 800
    $theme_h2_colour = $colour_options_data['h2'] ? $colour_options_data['h2'] : '#3e4041'; // Same as neutral, 800
    $theme_h3_colour = $colour_options_data['h3'] ? $colour_options_data['h3'] : '#3e4041'; // Same as neutral, 800
    $theme_h4_colour = $colour_options_data['h4'] ? $colour_options_data['h4'] : '#3e4041'; // Same as neutral, 800
    $theme_h5_colour = $colour_options_data['h5'] ? $colour_options_data['h5'] : '#3e4041'; // Same as neutral, 800
    $theme_h6_colour = $colour_options_data['h6'] ? $colour_options_data['h6'] : '#3e4041'; // Same as neutral, 800

    $theme_link_colour = $colour_options_data['link_colour'] ? $colour_options_data['link_colour'] : '#2F2BAC';
    $theme_link_active_colour = $colour_options_data['link_active_colour'] ? $colour_options_data['link_active_colour'] : '#7337AC';
    $theme_link_visited_colour = $colour_options_data['link_visited_colour'] ? $colour_options_data['link_visited_colour'] : '#A246AC';

    //Fonts
    $default_font = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"'; // Use System Font
    $theme_body_font = $colour_options_data['body_font'] ? $colour_options_data['body_font'] : $default_font;
    $theme_h1_font = $colour_options_data['h1_font'] ? $colour_options_data['h1_font'] : $default_font;
    $theme_h2_font = $colour_options_data['h2_font'] ? $colour_options_data['h2_font'] : $default_font;
    $theme_h3_font = $colour_options_data['h3_font'] ? $colour_options_data['h3_font'] : $default_font;
    $theme_h4_font = $colour_options_data['h4_font'] ? $colour_options_data['h4_font'] : $default_font;
    $theme_h5_font = $colour_options_data['h5_font'] ? $colour_options_data['h5_font'] : $default_font;
    $theme_h6_font = $colour_options_data['h6_font'] ? $colour_options_data['h6_font'] : $default_font;

    // Navigation Elements
    $theme_nav_background_colour = $colour_options_data['nav_background_colour'] ? $colour_options_data['nav_background_colour'] : '#2F71B3';
    $theme_nav_font = $colour_options_data['nav_font'] ? $colour_options_data['nav_font'] : $default_font;
    $theme_nav_link_colour = $colour_options_data['nav_link_colour'] ? $colour_options_data['nav_link_colour'] : '#ffffff';
    $theme_nav_link_hover_colour = $colour_options_data['nav_link_hover_colour'] ? $colour_options_data['nav_link_hover_colour'] : '#ffffff';
    $theme_nav_link_hover_background_color = $colour_options_data['nav_link_hover_background_color'] ? $colour_options_data['nav_link_hover_background_color'] : '#24578A';
    $theme_nav_sublink_colour = $colour_options_data['nav_sublink_colour'] ? $colour_options_data['nav_sublink_colour'] : '#2F71B3';
    $theme_nav_sublink_hover_colour = $colour_options_data['nav_sublink_hover_colour'] ? $colour_options_data['nav_sublink_hover_colour'] : '#24578A';
    $theme_nav_sublink_background_colour = $colour_options_data['nav_sublink_background_colour'] ? $colour_options_data['nav_sublink_background_colour'] : '#ffffff';
    $theme_nav_sublink_hover_background_colour = $colour_options_data['nav_sublink_hover_background_colour'] ? $colour_options_data['nav_sublink_hover_background_colour'] : '#ffffff';

    //Button Elements
    $theme_button_border_width = $colour_options_data['button_border_width'] ? $colour_options_data['button_border_width'] : '2';
    $theme_button_border_radius = $colour_options_data['button_border_radius'] ? $colour_options_data['button_border_radius'] : '4';
    $theme_button_font_family = $colour_options_data['button_font_family'] ? $colour_options_data['button_font_family'] : $default_font;

    $theme_button_primary_colour = $colour_options_data['button_primary_colour'] ? $colour_options_data['button_primary_colour'] : '#7B91B0';
    $theme_button_primary_hover_colour = $colour_options_data['button_primary_hover_colour'] ? $colour_options_data['button_primary_hover_colour'] : '#3c4e67';
    $theme_button_primary_hover_text_colour = $colour_options_data['button_primary_hover_text_colour'] ? $colour_options_data['button_primary_hover_text_colour'] : '#ffffff';
    $theme_button_primary_text_colour = $colour_options_data['button_primary_text_colour'] ? $colour_options_data['button_primary_text_colour'] : '#000000';

    $theme_button_secondary_colour = $colour_options_data['button_secondary_colour'] ? $colour_options_data['button_secondary_colour'] : '#af8a41';
    $theme_button_secondary_text_colour = $colour_options_data['button_secondary_text_colour'] ? $colour_options_data['button_secondary_text_colour'] :  '#ffffff';
    $theme_button_secondary_hover_colour = $colour_options_data['button_secondary_hover_colour'] ? $colour_options_data['button_secondary_hover_colour'] : '#4a3b1c';
    $theme_button_secondary_hover_text_colour = $colour_options_data['button_secondary_hover_text_colour'] ? $colour_options_data['button_secondary_hover_text_colour'] : '#000000';

    //Card Elements
    $theme_card_border_radius = $colour_options_data['card_border_radius'] ? $colour_options_data['card_border_radius'] : '4';
    $theme_card_border_width = $colour_options_data['card_border_width'] ? $colour_options_data['card_border_width'] : '1';

    //Using the functions to convert to em or rem
    jellypress_px_convert($theme_button_border_width);
    jellypress_px_convert($theme_button_border_radius);
    jellypress_px_convert($theme_card_border_radius);
    jellypress_px_convert($theme_card_border_width);

    echo '<style type="text/css">'; ?>

    :root {
    --primary_colour: <?php echo $theme_primary_colour; ?>;
    --primary_colour_dark: <?php echo $theme_primary_dark; ?>;
    --primary_colour_light: <?php echo $theme_primary_light; ?>;
    --secondary_colour: <?php echo $theme_secondary_colour; ?>;
    --secondary_colour_dark: <?php echo $theme_secondary_dark; ?>;
    --secondary_colour_light: <?php echo $theme_secondary_light; ?>;

    --text_colour: <?php echo $theme_text_colour; ?>;
    --h1_colour: <?php echo $theme_h1_colour; ?>;
    --h2_colour: <?php echo $theme_h2_colour; ?>;
    --h3_colour: <?php echo $theme_h3_colour; ?>;
    --h4_colour: <?php echo $theme_h4_colour; ?>;
    --h5_colour: <?php echo $theme_h5_colour; ?>;
    --h6_colour: <?php echo $theme_h6_colour; ?>;

    --link_colour: <?php echo $theme_link_colour; ?>;
    --link_active_colour: <?php echo $theme_link_active_colour; ?>;
    --link_visited_colour: <?php echo $theme_link_visited_colour; ?>;

    --body_font: <?php echo $theme_body_font; ?>;
    --h1_font: <?php echo $theme_h1_font; ?>;
    --h2_font: <?php echo $theme_h2_font; ?>;
    --h3_font: <?php echo $theme_h3_font; ?>;
    --h4_font: <?php echo $theme_h4_font; ?>;
    --h5_font: <?php echo $theme_h5_font; ?>;
    --h6_font: <?php echo $theme_h6_font; ?>;

    --nav_background_colour: <?php echo $theme_nav_background_colour; ?>;
    --nav_font: <?php echo $theme_nav_font; ?>;
    --nav_link_colour: <?php echo $theme_nav_link_colour; ?>;
    --nav_link_hover_colour: <?php echo $theme_nav_link_hover_colour; ?>;
    --nav_link_hover_background_color: <?php echo $theme_nav_link_hover_background_color; ?>;
    --nav_sublink_colour: <?php echo $theme_nav_sublink_colour; ?>;
    --nav_sublink_hover_colour: <?php echo $theme_nav_sublink_hover_colour; ?>;
    --nav_sublink_background_colour: <?php echo $theme_nav_sublink_background_colour; ?>;
    --nav_sublink_hover_background_colour: <?php echo $theme_nav_sublink_hover_background_colour; ?>;

    --button_border_width: <?php echo $theme_button_border_width; ?>;
    --button_border_radius: <?php echo $theme_button_border_radius; ?>;
    --button_font_family: <?php echo $theme_button_font_family; ?>;

    --button_primary_text_colour: <?php echo $theme_button_primary_text_colour; ?>;
    --button_primary_hover_colour: <?php echo $theme_button_primary_hover_colour; ?>;
    --button_primary_hover_text_colour: <?php echo $theme_button_primary_hover_text_colour; ?>;
    --button_primary_colour: <?php echo $theme_button_primary_colour; ?>;

    --button_secondary_colour: <?php echo $theme_button_secondary_colour; ?>;
    --button_secondary_text_colour: <?php echo $theme_button_secondary_text_colour; ?>;
    --button_secondary_hover_colour: <?php echo $theme_button_secondary_hover_colour; ?>;
    --button_secondary_hover_text_colour: <?php echo $theme_button_secondary_hover_text_colour; ?>;

    --card_border_radius: <?php echo $theme_card_border_radius; ?>;
    --card_border_width: <?php echo $theme_card_border_width; ?>;

    // TODO: I Don't think these are used anywhere. Can we remove them?
    --light: #ffffff;
    --dark: #000000;
    }

    <?php

    $theme_bg_colors = array(
      '.bg-primary' => $theme_primary_colour,
      '.bg-primary-dark' => $theme_primary_dark,
      '.bg-primary-light' => $theme_primary_light,
      '.bg-secondary' => $theme_secondary_colour,
      '.bg-secondary-dark' => $theme_secondary_dark,
      '.bg-secondary-light' => $theme_secondary_light,
      '.bg-white' => '#ffffff',
      '.bg-black' => '#000000',
      '.bg-neutral-900' => "#1f2933",
      '.bg-neutral-800' => "#33404d",
      '.bg-neutral-700' => "#3f4d5a",
      '.bg-neutral-600' => "#515f6c",
      '.bg-neutral-500' => "#6e7c8c",
      '.bg-neutral-400' => "#9aa5b1",
      '.bg-neutral-300' => "#cad1d8",
      '.bg-neutral-200' => "#e5e8eb",
      '.bg-neutral-100' => "#f5f7fa"
    );

    foreach ($theme_bg_colors as $css_class => $bg_color) {

    ?>
      <?php echo $css_class; ?> {
      color: <?php echo ezpz_get_contrast_color($bg_color); ?>;
      }
      <?php

      if ($theme_h1_colour) { ?>
        <?php echo $css_class; ?> h1 {
        color: <?php echo ezpz_get_contrast_color($bg_color, $theme_h1_colour); ?>
        }

        <?php if ($theme_h2_colour) { ?>
          <?php echo $css_class; ?> h2 {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_h2_colour); ?>
          }
        <?php }

        if ($theme_h3_colour) { ?>
          <?php echo $css_class; ?> h3 {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_h3_colour); ?>
          }
        <?php }

        if ($theme_h4_colour) { ?>
          <?php echo $css_class; ?> h4 {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_h4_colour); ?>
          }
        <?php }

        if ($theme_h5_colour) { ?>
          <?php echo $css_class; ?> h5 {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_h5_colour); ?>
          }
        <?php }

        if ($theme_h6_colour) { ?>
          <?php echo $css_class; ?> h6 {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_h6_colour); ?>
          }
        <?php }

        if ($theme_link_colour) { ?>
          <?php echo $css_class; ?> a {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_link_colour, '#eeeeee'); ?>
          }
        <?php }

        if ($theme_link_active_colour) { ?>
          <?php echo $css_class; ?> a:hover, <?php echo $css_class; ?> a:focus {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_link_active_colour, '#cccccc'); ?>
          }
        <?php }

        if ($theme_link_visited_colour) { ?>
          <?php echo $css_class; ?> a:visited {
          color: <?php echo ezpz_get_contrast_color($bg_color, $theme_link_visited_colour, '#cccccc'); ?>
          }
    <?php }
      }
    }

    echo '</style>';

    ?>
<?php
  }
endif;
