<?php

/**
 * Text Columns Block Template.
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

?>
<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($fields['title']) { ?>
      <header class="block-title">
        <h2 class="<?php echo $text_align; ?>">
          <?php echo wp_strip_all_tags($fields['title']); ?>
        </h2>
      </header>
    <?php } ?>

    <?php if ($text_columns = $fields['columns']) { ?>
      <div class="row">
        <?php foreach ($text_columns as $text_column) : ?>
          <div class="col xs-12 md-0 <?php echo $text_align; ?>">
            <?php echo $text_column['editor']; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php } ?>

    <?php if (!empty($fields['buttons'])) : ?>
      <div class="row">
        <div class="col text-center">
          <?php
          if ($text_align == 'text-center') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-center');
          elseif ($text_align == 'text-right') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-end');
          else jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color']);
          ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>