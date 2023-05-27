<?php

/**
 * Slider Block Template.
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

if ($display_arrows = $fields['display_arrows']) $display_arrows = 'slider';
else $display_arrows = 'false';

if ($display_pagination = $fields['display_pagination']) $display_pagination = 'true';
else $display_pagination = 'false';

$slider_speed = $fields['slider_duration'] * 1000;

$show_progress_bar = false; // Progress Bar is an option in php rather than the front end because it will usually not be useful.

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($slides = $fields['slides']) :

      $slider_id = 'slider-' . $block_id;

      $number_of_slides = count($slides);

      if ($number_of_slides > 1) {
        // Note: Apply class .has-pagination to the splide element to make the dots numbered instead of dots
        // Note: Apply class .has-inner-arrows to make the arrows stay inside the container
        echo '<div class="splide slider" id="' . $slider_id . '">
          <div class="splide__slider">
          <div class="splide__track">
          <div class="splide__list">';
        $slide_class = 'splide__slide slide';
      } else {
        echo '<div class="slider">';
        $slide_class = 'slide single';
      }

      $i = 0;

      foreach ($slides as $slide) :

        $slide_params = array(
          'slide' => $slide,
          'block_id' => $block_id,
          'slide_id' => $i,
          'slide_class' => $slide_class,
        );
        get_template_part('template-parts/blocks/slider/slide-template', null, $slide_params);

        $i++;
      endforeach;

      if ($number_of_slides > 1) echo '</div></div></div>';

      if ($number_of_slides > 1 && $show_progress_bar) : ?>
        <div class="splide__progress">
          <div class="splide__progress__bar">
          </div>
        </div>
    <?php endif;

      echo '</div>';

    endif; ?>

  </div>
</section>

<?php
if ($number_of_slides > 1) {
  add_action(
    'wp_footer',
    jellypress_splide_init('#' . $slider_id, 1, 1, 1, 1, $display_arrows, $display_pagination, $slider_speed),
    30
  );
}
?>