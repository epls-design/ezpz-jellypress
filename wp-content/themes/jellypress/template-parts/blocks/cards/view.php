<?php

/**
 * Cards Block Template.
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

if ($text_align == 'text-center') $justify = 'justify-center';
elseif ($text_align == 'text-right') $justify = 'justify-end';
else $justify = 'justify-start';

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($fields['title']) { ?>
      <header class="row <?php echo $justify; ?> block-title">
        <div class="col md-10 lg-8">
          <h2 class="<?php echo $text_align; ?>">
            <?php echo wp_strip_all_tags($fields['title']); ?>
          </h2>
        </div>
      </header>
    <?php } ?>

    <?php if ($fields['preamble']) { ?>
      <div class="row <?php echo $justify; ?> block-preamble">
        <div class="col md-10 lg-8 <?php echo $text_align; ?>">
          <?php echo $fields['preamble']; ?>
        </div>
      </div>
    <?php } ?>

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
    <?php endif; ?>

  </div>
</section>