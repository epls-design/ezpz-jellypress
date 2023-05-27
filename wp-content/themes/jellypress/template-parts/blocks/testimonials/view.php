<?php

/**
 * Testimonials Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 *        This is either the post ID currently being displayed inside a query loop,
 *        or the post ID of the post hosting this block.
 * @param array $context The context provided to the block by the post or it's parent block.
 * @param array $block_attributes Processed block attributes to be used in template.
 * @param array $fields Array of ACF fields used in this block.
 *
 * Block registered with ACF using block.json
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$block_id = str_replace('block_', '', $block['id']);
$block_attributes = jellypress_get_block_attributes($block);
$fields = get_fields();
$text_align = $block_attributes['text_align'];

if ($display_arrows = $fields['display_arrows']) {
  $display_arrows = 'slider';
  $testimonial_col_class = 'col xs-8 sm-12';
} else {
  $display_arrows = 'false';
  $testimonial_col_class = 'col sm-12';
}

if ($display_pagination = $fields['display_pagination']) $display_pagination = 'true';
else $display_pagination = 'false';

$slider_speed = $fields['slider_duration'] * 1000;

$show_progress_bar = false; // Progress Bar is an option in php rather than the front end because it will usually not be useful.

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($fields['title']) { ?>
    <header class="row justify-center block-title">
      <div class="col md-10 lg-8">
        <h2 class="<?php echo $text_align; ?>">
          <?php echo wp_strip_all_tags($fields['title']); ?>
        </h2>
      </div>
    </header>
    <?php } ?>

    <?php if ($fields['preamble']) { ?>
    <div class="row justify-center block-preamble">
      <div class="col md-10 lg-8 <?php echo $text_align; ?>">
        <?php echo $fields['preamble']; ?>
      </div>
    </div>
    <?php } ?>

    <?php if ($testimonials = $fields['testimonials']) :

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
        get_template_part('template-parts/blocks/testimonials/slide-template', null, $testimonial_params);

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