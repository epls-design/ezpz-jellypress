<?php

/**
 * Flexible layout: Testimonials
 * Allows the editor to add a number of testimonials to the page which include text
 * and optional citation, image and link.
 *
 * TODO: Add option for more than one in a row.
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$block_title = $block['title'];
$title_align = $block_title ? $block['title_align'] : 'left';
$block_preamble = $block['preamble'];

if ($display_arrows = $block['display_arrows']) {
  $display_arrows = 'slider';
  $testimonial_col_class = 'col xs-8 sm-12';
} else {
  $display_arrows = 'false';
  $testimonial_col_class = 'col sm-12';
}

if ($display_pagination = $block['display_pagination']) $display_pagination = 'true';
else $display_pagination = 'false';

$slider_speed = $block['slider_duration'] * 1000;

$show_progress_bar = false; // Progress Bar is an option in php rather than the front end because it will usually not be useful.

?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?= $block_classes; ?>">
  <div class="container">

    <?php if ($block_title) : ?>
      <header class="row justify-center block-title">
        <div class="col md-10 lg-8">
          <h2 class="text-<?= $title_align; ?>"><?= jellypress_bracket_tag_replace($block_title); ?></h2>
        </div>
      </header>
    <?php endif; ?>

    <?php if ($block_preamble) : ?>
      <div class="row justify-center block-preamble">
        <div class="col md-10 lg-8">
          <?= jellypress_content($block_preamble); ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($testimonials = $block['testimonials']) :

      echo '<div class="row justify-center"><div class="col md-10 lg-8">';

      $slider_id = 'slider-' . $block_id;

      $number_of_testimonials = count($testimonials);

      if ($number_of_testimonials > 1) {
        // Note: Apply class .has-pagination to the splide element to make the dots numbered instead of dots
        // Note: Apply class .has-inner-arrows to make the arrows stay inside the container
        echo '<div class="splide slider slider-testimonials" id="' . $slider_id . '">
          <div class="splide__slider">
          <div class="splide__track">
          <div class="splide__list">';
        $testimonial_class = 'splide__slide slide';
      } else {
        echo '<div class="slider slider-testimonials">';
        $testimonial_class = 'slide single';
      }

      $i = 0;

      foreach ($testimonials as $testimonial) :

        $testimonial_params = array(
          'testimonial' => $testimonial,
          'block_id' => $block_id,
          'testimonial_id' => $i,
          'testimonial_class' => $testimonial_class,
          'col_class' => $testimonial_col_class,
        );
        get_template_part('template-parts/components/slider/slide', 'testimonial', $testimonial_params);

        $i++;
      endforeach;

      if ($number_of_testimonials > 1) echo '</div></div></div>';

      if ($number_of_testimonials > 1 && $show_progress_bar) : ?>
        <div class="splide__progress">
          <div class="splide__progress__bar">
          </div>
        </div>
    <?php endif;

      echo '</div></div></div>';

    endif; ?>

  </div>
</section>

<?php
if ($number_of_testimonials > 1) {
  add_action(
    'wp_footer',
    jellypress_splide_init('#' . $slider_id, 1, 1, 1, 1, $display_arrows, $display_pagination, $slider_speed),
    30
  );
}
?>
