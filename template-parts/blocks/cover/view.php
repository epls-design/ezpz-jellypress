<?php

/**
 * Flexible layout: Cover block
 * Renders a block containing a background image and overlaid text
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$block_classes .= ' has-bg-img';

$block_title = $block['title'];

$background_image = $block['background_image'];
if ($block['parallax_background']) $block_classes .= ' bg-is-fixed';

$content_align = $block['content_align'];

if ($content_align == 'center') {
  $row_justify = 'center';
  $col_class = 'md-10 lg-8 text-center';
} elseif ($content_align == 'left') {
  $row_justify = 'start';
  $col_class = 'md-8 xl-6';
} else {
  // Right
  $row_justify = 'end';
  $col_class = 'md-8 xl-6';
}

if ($min_height = $block['min_height']) $block_classes .= ' min-h-' . $min_height;
if (!$text_color = $block['text_color']) $block_classes .= ' text-white';

$overlay_color = $block['overlay_color'];
if ($overlay_color) {
  $block_classes .= ' has-overlay overlay-' . $overlay_color;
  $block_classes .= ' overlay-opacity-' . $block['overlay_opacity'];
}

$block_classes .= ' flex align-middle';
?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?= $block_classes; ?>" data-bg-img="<?= wp_get_attachment_image_url($background_image, 'large'); ?>" style="background-image:url(<?= wp_get_attachment_image_url($background_image, 'medium'); ?>)">
  <div class="container">

    <?php if ($block_title) : $title_align = $block['title_align']; ?>
      <header class="row justify-<?= $row_justify; ?> block-title">
        <div class="col <?= $col_class; ?>">
          <h2 class="text-<?= $title_align; ?>"><?= jellypress_bracket_tag_replace($block_title); ?></h2>
        </div>
      </header>
    <?php endif; ?>

    <div class="row justify-<?= $row_justify; ?> cover-text">
      <div class="col <?= $col_class; ?>">
        <?= jellypress_content($block['text']); ?>
        <?php
        if (isset($block['buttons'])) :
          if ($content_align == 'center') jellypress_display_cta_buttons($block['buttons'], 'justify-center');
          else jellypress_display_cta_buttons($block['buttons']);
        endif;
        ?>
      </div>
    </div>

  </div>
</section>
