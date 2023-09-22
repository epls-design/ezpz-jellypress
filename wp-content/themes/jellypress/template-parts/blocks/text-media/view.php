<?php

/**
 * Text and Media Block Template.
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

$block_attributes = jellypress_get_block_attributes($block);
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

$fields = get_fields();
$text_align = $block_attributes['text_align'];

$row_class = isset($block_attributes['align_content']) ? 'align-' . $block_attributes['align_content'] : 'align-top';
$row_class = str_replace('center', 'middle', $row_class);

$media_align = $fields['media_position'] ? $fields['media_position'] : 'left';

$media_class = 'col sm-12 md-6 column-media';
$text_class = 'col  column-text ' . $text_align . ' ';

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

        // Placeholder shows if neither image nor video has been added
        jellypress_acf_placeholder(
          array(
            $fields['image'],
            $fields['video'],
          ),
          __('Please click here to add a media item to this block.', 'jellypress'),
          $is_preview
        );

        if ($fields['media_type'] == 'image') {
          echo '<figure>';
          // TODO: Add zoom option
          if ($fields['link']) {
            $link = $fields['link'];
            if ($is_preview) $class = 'prevent-clicks';
            echo '<a class="' . $class . '" href="' . $link['url'] . '" target="' . $link['target'] . '" title="' . $link['title'] . '">';
          }
          echo wp_get_attachment_image($fields['image'], 'large', false, array('class' => 'full-width'));
          if ($fields['link']) {
            echo '</a>';
          }
          echo '</figure>';
        } elseif ($fields['media_type'] == 'video') {
          $autoplay = $fields['autoplay'] ? true : false;
          jellypress_embed_video($fields['video'], $fields['aspect_ratio'], $autoplay);
        }
        ?>
      </div>
      <div class="<?php echo $text_class; ?>">
        <InnerBlocks allowedBlocks="<?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
      </div>
    </div>
  </div>

</section>