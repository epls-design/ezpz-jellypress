<?php

/**
 * Image Zoom Block Template. Can only be used inside ezpz/content.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty as).
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

$block_id = 'gallery-' . str_replace('block_', '', $block_attributes['block_id']);

jellypress_acf_placeholder(
  $fields['image'],
  __('Please add an image to this block - click here to get started.', 'jellypress'),
  $is_preview
);

if ($fields['image']) { ?>
  <div class="gallery" id="<?php echo $block_id; ?>">
    <?php
    if ($fields['allow_zoom'] == true) {
      // Enqueue Photoswipe
      wp_enqueue_script('photoswipe-init');
      if ($is_preview) $wrapper_class .= 'prevent-clicks';
      else $wrapper_class .= 'lightbox-image';
      $image_full_size = wp_get_attachment_image_src($fields['image'], 'full');
    }
    ?>
    <figure class="<?php echo $wrapper_class; ?>">
      <?php if ($fields['allow_zoom'] == true) echo '<a href="' . $image_full_size[0] . '" data-pswp-width="' . $image_full_size[1] . '" data-pswp-height="' . $image_full_size[2] . '" target="_blank">'; ?>
      <?php echo wp_get_attachment_image($fields['image'], 'medium', null, array("class" => "gallery-image w-100")); ?>
      <?php if ($fields['allow_zoom'] == true) echo '</a>'; ?>
    </figure>
  </div>
<?php }

// TODO: OVERWRITE THE CORE IMAGE BLOCK WITH THIS ONE