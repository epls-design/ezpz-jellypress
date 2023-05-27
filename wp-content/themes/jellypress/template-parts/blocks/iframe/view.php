<?php

/**
 * iFrame Block Template.
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
?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>

  <?php
  if ($fields['title'] || $fields['preamble']) echo '<div class="container">';

  if ($fields['title']) { ?>
    <header class="row justify-center block-title">
      <div class="col md-10 lg-8">
        <h2 class="<?php echo $text_align; ?>">
          <?php echo wp_strip_all_tags($fields['title']); ?>
        </h2>
      </div>
    </header>
  <?php } ?>

  <?php if ($fields['preamble']) { ?>
    <div class="row justify-center block-preamble">
      <div class="col md-10 lg-8 <?php echo $text_align; ?>">
        <?php echo $fields['preamble']; ?>
      </div>
    </div>
  <?php } ?>

  <?php if ($fields['title'] || $fields['preamble']) echo '</div>'; ?>

  <div class="<?php echo $container_class; ?>">
    <div class="row">
      <div class="col">
        <?php
        if ($block_width === 'full') echo '<div class="vw-100">';
        elseif ($block_width === 'center') echo '<div class="row justify-center"><div class="col md-10 lg-8">';
        ?>
        <div class="embed-container">
          <iframe allowfullscreen class="embedded-iframe" src="<?php echo $fields['website_url']; ?>"></iframe>
        </div>
        <?php
        if ($block_width === 'full') echo '</div>';
        elseif ($block_width === 'center') echo '</div></div>';
        ?>
      </div>
    </div>
  </div>

</section>