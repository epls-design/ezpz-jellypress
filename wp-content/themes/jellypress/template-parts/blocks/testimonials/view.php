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

// Displays the block preview in the Gutenberg editor. Requires example to be set in block.json and a preview.png image file.
if (jellypress_get_block_preview_image($block) == true) return;

$block_id = str_replace('block_', '', $block['id']);

$block_attributes = jellypress_get_block_attributes($block, $context);
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

$fields = get_fields();

$text_align = $block_attributes['text_align'];

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($content || $is_preview) : ?>
      <header class="row justify-center">
        <div class="col md-10 lg-8 <?php echo $text_align; ?>">
          <InnerBlocks allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
        </div>
      </header>
    <?php endif; ?>

    <?php

    if ($testimonials = $fields['testimonials']) :

      /**
       * Set up the Swiper options - these will be passed to the swiper-init.js script.
       * @see https://swiperjs.com/swiper-api for all available options
       */
      $swiper_opts = array(
        'slidesPerView' => 1,
        'slidesSM' => 1,
        'slidesMD' => 1,
        'slidesLG' => 1,
        'slidesXL' => 1,
        'delay' =>  $fields['slider_duration'] * 1000,
        'effect' => 'fade',
      );
    ?>
      <div class="swiper-container">

        <div class="swiper" data-swiper-options="<?php echo htmlspecialchars(json_encode($swiper_opts), ENT_QUOTES, 'UTF-8'); ?>">
          <div class="swiper-wrapper">
            <?php foreach ($testimonials as $testimonial) {
              get_template_part('template-parts/blocks/testimonials/slide-template', null, array('testimonial' => $testimonial));
            } ?>
          </div>
        </div>
        <?php
        if ($fields['display_arrows']) {
          echo '<div class="swiper-button-prev"></div>';
          echo '<div class="swiper-button-next"></div>';
        }
        if ($fields['display_pagination']) {
          echo '<div class="swiper-pagination"></div>';
        }
        ?>
      </div>

    <?php elseif ($is_preview) : ?>
      <div class="acf-placeholder">
        <div class="acf-placeholder-label"><?php _e('You need to add some testimonials to this block. Please click here to edit the fields in the block sidebar, alternatively change the block view mode to "edit".', 'jellypress'); ?></div>
      </div>
    <?php endif; ?>
  </div>
  </div>
</section>