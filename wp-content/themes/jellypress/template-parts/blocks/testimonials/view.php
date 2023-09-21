<?php

/**
 * Testimonials Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML
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
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

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

    <?php if ($content || $is_preview) : ?>
      <header class="row justify-center">
        <div class="col md-10 lg-8">
          <InnerBlocks className="<?php echo $text_align; ?>" allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
        </div>
      </header>
    <?php endif; ?>

    <?php
    jellypress_acf_placeholder(
      $$fields['testimonials'][0]['testimonial_text'],
      __('Please add testimonials to this block - click here to get started.', 'jellypress'),
      $is_preview
    );

    if ($testimonials = $fields['testimonials']) :
      // Note: Apply class .has-pagination to the splide element to make the dots numbered instead of dots
      // Note: Apply class .has-inner-arrows to make the arrows stay inside the container

      $slider_id = 'slider-' . $block_id;
      $number_of_slides = count($testimonials);

      add_action(
        'wp_footer',
        jellypress_splide_init('#' . $slider_id, 1, 1, 1, 1, $number_of_slides, $display_arrows, $display_pagination, $slider_speed),
        30
      );
    ?>

      <div class="row justify-center">
        <div class="col md-10 lg-8">
          <div class="splide slider slider-testimonials" id="<?php echo $slider_id; ?>" aria-label="<?php _e('Testimonials', 'jellypress'); ?>">
            <div class="splide__track">
              <div class="splide__list">
                <?php
                foreach ($testimonials as $testimonial) :
                  $testimonial_params = array(
                    'testimonial' => $testimonial,
                    'testimonial_class' => 'splide__slide slide',
                    'col_class' => $testimonial_col_class,
                  );
                  get_template_part('template-parts/blocks/testimonials/slide-template', null, $testimonial_params);
                endforeach;
                ?>
              </div>
            </div>
            <?php if ($show_progress_bar) : ?>
              <div class="splide__progress">
                <div class="splide__progress__bar">
                </div>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        </div>
      </div>
</section>