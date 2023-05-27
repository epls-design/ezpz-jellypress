<?php

/**
 * Image Block Template.
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

$container_class = 'container';

$block_width = isset($block_attributes['align']) ? $block_attributes['align'] : '';
if ($block_width == 'wide') $container_class .= ' is-wide';
elseif ($block_width == 'full') $block_attributes['class'] .= ' is-full-width';
elseif ($block_width === 'center') $justify = 'center';
?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>

  <?php if ($fields['title']) {
    if ($block_width === 'center') $justify = 'center';
    elseif ($text_align == 'text-left') $justify = 'start';
    else $justify = 'center';
  ?>
    <div class="container">
      <header class="row justify-<?php echo $justify; ?> block-title">
        <div class="col md-10 lg-8">
          <h2 class="<?php echo $text_align; ?>">
            <?php echo wp_strip_all_tags($fields['title']); ?>
          </h2>
        </div>
      </header>
    </div>
  <?php } ?>

  <figure>
    <div class="<?php echo $container_class; ?>">
      <?php
      if ($block_width === 'full') echo '<div class="vw-100">';
      elseif ($block_width === 'center') echo '<div class="row justify-' . $justify . '"><div class="col md-10 lg-8">';

      if ($block_width === 'full') $size = 'full';
      else $size = 'large';
      echo wp_get_attachment_image($fields['image'], $size);

      if ($block_width === 'full') echo '</div>';
      elseif ($block_width === 'center') echo '</div></div>'; ?>
    </div>

    <?php if ($fields['caption']) :
      if (!$block_title && !$justify) {
        $justify = 'start';
      }
    ?>
      <figcaption class="caption container">
        <div class="row justify-<?php echo $justify; ?>">
          <div class="col md-10 lg-8 <?php echo $text_align; ?>">
            <?php echo $fields['caption']; ?>
          </div>
        </div>
      </figcaption>
    <?php endif; ?>
  </figure>

</section>