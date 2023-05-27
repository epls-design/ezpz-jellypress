<?php

/**
 * Text and Media Block Template.
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

$row_class = isset($block_attributes['align_content']) ? 'align-' . $block_attributes['align_content'] : 'align-top';
$row_class = str_replace('center', 'middle', $row_class);

$media_class = 'col sm-12 md-6 column-media';
$text_class = 'col sm-12 md-5 column-text ' . $text_align;
$media_align = $fields['media_position'] ? $fields['media_position'] : 'left';
if ($media_align == 'left') {
  $text_class .= ' order-md-2';
  $media_class .= ' order-md-1';
} else {
  $text_class .= ' order-md-1';
  $media_class .= ' order-md-2';
}
?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <div class="row <?php echo $row_class; ?> justify-between">
      <div class="<?php echo $media_class; ?>">
        <?php
        if ($fields['media_type'] == 'image') {
          echo '<figure>';
          echo wp_get_attachment_image($fields['image'], 'large', false, array('class' => 'w-100'));
          echo '</figure>';
        } elseif ($fields['media_type'] == 'video') {
          $autoplay = $fields['autoplay'] ? true : false;
          jellypress_embed_video($fields['video'], $fields['aspect_ratio'], $autoplay);
        }
        ?>
      </div>
      <div class="<?php echo $text_class; ?>">
        <?php
        if ($fields['title']) { ?>
          <header class="block-title">
            <h2 class="<?php echo $text_align; ?>">
              <?php echo wp_strip_all_tags($fields['title']); ?>
            </h2>
          </header>
        <?php
        }
        echo $fields['text'];
        if ($text_align == 'text-center') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-center');
        elseif ($text_align == 'text-right') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-end');
        else jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color']); ?>
      </div>
    </div>

  </div>
</section>