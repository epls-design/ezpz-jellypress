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

$row_class = isset($block_attributes['align_content']) ? 'align-' . $block_attributes['align_content'] : 'align-top';
$row_class = str_replace('center', 'middle', $row_class);
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

    <div class="row <?php echo $row_class; ?>">
      <?php foreach ($fields['columns'] as $column) :
        //var_dump($column);
        $col_class = 'col';
        $col_class .= ' ' . $column['width_xs'] . ' ' . $column['width_sm'] . ' ' . $column['width_md'] . ' ' . $column['width_lg'];
        $column_type = $column['column_type'];
        $col_class .= ' column-' . $column_type;
      ?>
      <div class="<?php echo $col_class; ?>">
        <?php
          if ($column_type == 'text') {
            echo $column['text'];
            if (isset($column['buttons'])) jellypress_display_cta_buttons($column['buttons'], $block_attributes['bg_color']);
          } elseif ($column_type == 'image') {
            echo '<figure>';
            if ($image_link = $column['image_link']) echo '<a href="' . $image_link['url'] . '" title="' . $image_link['title'] . '" target="' . $image_link['target'] . '">';
            echo wp_get_attachment_image($column['image'], 'large');
            if ($image_link) echo '</a>';
            if ($column_caption = $column['column_caption']) echo '<figcaption class="image-caption">' . $column_caption . '</figcaption>';
            echo '</figure>';
          } elseif ($column_type == 'video') {
            $autoplay = $fields['autoplay'] ? true : false;
            jellypress_embed_video($column['video'], $column['aspect_ratio'], $autoplay);
            if ($column_caption = $column['column_caption']) {
              echo '<div class="video-caption">' . $column_caption . '</div>';
            }
          }
          ?>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>