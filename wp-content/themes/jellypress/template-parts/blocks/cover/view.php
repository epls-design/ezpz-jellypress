<?php

/**
 * Cover Block Template.
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

$block_attributes = jellypress_get_block_attributes($block);
$fields = get_fields();
$text_align = $block_attributes['text_align'];

/**
 * Set up all the block attributes, note that we are using an inner container as a faux block
 */
$block_attributes['class'] .= ' has-bg-img';
if ($fields['parallax_background']) $block_attributes['class'] .= ' bg-is-fixed';
$background_image = $fields['background_image'];

$overlay_color = $block_attributes['bg_color'];
$block_attributes['class']  .= ' has-overlay overlay-' . $overlay_color;
$block_attributes['class']  .= ' overlay-opacity-' . $fields['overlay_opacity'];
$block_attributes['class'] .= ' flex';

// Remove 'block' and 'block-cover' in  $block_attributes['class'] because we are using a container inside the block
$block_attributes['class'] = str_replace(array('block-cover', 'block'), '', $block_attributes['class']);
$block_attributes['class'] = trim($block_attributes['class']); // Remove any whitespace
$block_attributes['class'] = 'cover-container ' . $block_attributes['class'];

// Set up inline styles
$block_styles = array(
  'background-image' => 'url(' . wp_get_attachment_image_url($background_image, 'medium') . ')',
);
if (isset($fields['min_height'])) $block_styles['min-height'] = $fields['min_height'] . 'vh';
// Convert Array into CSS
$block_styles = 'style="' . implode(';', array_map(
  function ($v, $k) {
    return $k . ':' . $v;
  },
  $block_styles,
  array_keys($block_styles)
)) . '"';

/**
 * Build up the flex align based on the matrix align attribute
 */
$row_class = 'row cover-content';
$container_class = 'container';

if (strpos($block_attributes['align_content'], 'top') !== false) {
  $block_attributes['class'] .= ' align-top';
} elseif (strpos($block_attributes['align_content'], 'bottom') !== false) {
  $block_attributes['class'] .= ' align-bottom';
} elseif (strpos($block_attributes['align_content'], 'center-') !== false) {
  $block_attributes['class'] .= ' align-middle';
}

if (strpos($block_attributes['align_content'], 'right') !== false) {
  $row_class .= ' justify-end';
} elseif (strpos($block_attributes['align_content'], '-center') !== false) {
  $row_class .= ' justify-center';
}

// Work out the block width
$block_width = isset($block_attributes['align']) ? $block_attributes['align'] : '';

if ($block_width == 'wide') {
  $container_class .= ' is-wide';
} elseif ($block_width == 'full') {
  $block_attributes['class'] .= ' is-width-full';
} elseif ($block_width == 'center') {
  $block_attributes['class'] .= ' is-width-small';
} else {
  $block_attributes['class'] .= ' is-width-normal';
}
?>

<section class="block block-cover" <?php echo $block_attributes['anchor']; ?>>

  <div class="<?php echo $container_class; ?>">
    <?php if ($block_width === 'full') echo '<div class="vw-100">'; ?>
    <div class="<?php echo $block_attributes['class']; ?>" data-bg-img="<?php echo wp_get_attachment_image_url($background_image, 'large'); ?>" <?php echo $block_styles; ?>>

      <div class="<?php echo $row_class; ?>">
        <div class="col md-7 lg-6">
          <?php if ($fields['title']) { ?>
            <header class="block-title">
              <h2 class="<?php echo $text_align; ?>">
                <?php echo wp_strip_all_tags($fields['title']); ?>
              </h2>
            </header>
          <?php } ?>
          <div class="cover-text <?php echo $text_align; ?>">
            <?php echo $fields['text']; ?>
          </div>
          <?php
          if (isset($fields['buttons'])) :
            if ($text_align == 'text-center') jellypress_display_cta_buttons($fields['buttons'], $overlay_color, 'justify-center');
            elseif ($text_align == 'text-right') jellypress_display_cta_buttons($fields['buttons'], $overlay_color, 'justify-end');
            else jellypress_display_cta_buttons($fields['buttons'], $overlay_color);
          endif;
          ?>
        </div>
      </div>
    </div>
  </div>

  <?php if ($block_width === 'full') echo '</div>'; ?>

  </div>
</section>