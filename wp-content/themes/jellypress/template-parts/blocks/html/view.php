<?php

/**
 * Unfiltered HTML Block Template.
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
      <header class="row justify-center block-title">
        <div class="col md-10 lg-8">
          <h2 class="<?php echo $text_align; ?>">
            <?php echo wp_strip_all_tags($fields['title']); ?>
          </h2>
        </div>
      </header>
    <?php } ?>

    <?php if ($fields['preamble']) { ?>
      <header class="row justify-center block-preamble <?php echo $text_align; ?>">
        <div class="col md-10 lg-8">
          <?php echo $fields['preamble']; ?>
        </div>
      </header>
    <?php } ?>

    <div class="row justify-center">
      <div class="col md-10 lg-8">
        <?php
        echo $fields['unfiltered_html'];
        ?>
      </div>
    </div>

  </div>
</section>