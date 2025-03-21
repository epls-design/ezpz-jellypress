<?php

/**
 * Cards Block Template.
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

if (jellypress_get_block_preview_image($block) == true) return;

$block_attributes = jellypress_get_block_attributes($block, $context);
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

$fields = get_fields();
$text_align = $block_attributes['text_align'];

if ($text_align == 'text-center') $justify = 'justify-center';
elseif ($text_align == 'text-right') $justify = 'justify-end';
else $justify = 'justify-start';

// NOTE: Card may be better as a block pattern / inner block at some point in the future

// TODO: Sort out WYSIWIG in Editor as buttons are broken
?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($content || $is_preview) : ?>
      <header class="row <?php echo $justify; ?>">
        <div class="col md-10 lg-8 <?php echo $block_attributes['text_align']; ?>">
          <InnerBlocks allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
        </div>
      </header>
    <?php endif; ?>

    <?php if ($cards = $fields['cards']) : ?>
      <div class="row <?php echo $justify; ?> cards equal-height">
        <?php foreach ($cards as $card) {
          echo '<div class="col xs-6 md-4 lg-3">';
          $card_params = array(
            'card' => $card
          );
          get_template_part('template-parts/blocks/cards/card-template', null, $card_params);
          echo '</div>';
        }
        ?>
      </div>
    <?php elseif ($is_preview) : ?>
      <div class="acf-placeholder">
        <div class="acf-placeholder-label"><?php _e('You need to add some cards to this block. Please click here to edit the fields in the block sidebar, alternatively change the block view mode to "edit".', 'jellypress'); ?></div>
      </div>
    <?php endif; ?>

  </div>
</section>