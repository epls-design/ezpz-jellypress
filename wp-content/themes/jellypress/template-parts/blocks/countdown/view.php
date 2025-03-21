<?php

/**
 * Countdown Block Template.
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

// Displays the block preview in the Gutenberg editor. Requires example to be set in block.json and a preview.png image file.
if (jellypress_get_block_preview_image($block) == true) return;

$block_attributes = jellypress_get_block_attributes($block, $context);
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

$fields = get_fields();

$block_id = str_replace('block_', '', $block_attributes['block_id']);
$text_align = $block_attributes['text_align'];
$complete_text = $fields['complete_text'];

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($content || $is_preview) : ?>
      <header class="row justify-center">
        <div class="col md-10 lg-8 <?php echo $text_align; ?>">
          <InnerBlocks allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
        </div>
      </header>
    <?php endif; ?>

    <?php if ($countdown = $fields['countdown_to']) :
      $countdown_tz = $fields['time_zone'];
      $countdown = $countdown . ' ' . $countdown_tz;
      // eg. 'November 4 2020 18:00:00 GMT+0000'
    ?>
      <div class="row justify-center <?php echo $text_align; ?>">
        <div class="col md-10 lg-8">
          <div class="countdown<?php if ($complete_text) echo ' has-complete-text'; ?>" data-countdown-to="<?php echo $countdown; ?>">
            <div class="partial">
              <div class="value days">00</div>
              <div class="small"><?php _e('Days', 'jellypress'); ?></div>
            </div>
            <div class="partial">
              <div class="value hours">00</div>
              <div class="small"><?php _e('Hours', 'jellypress'); ?></div>
            </div>
            <div class="partial">
              <div class="value minutes">00</div>
              <div class="small"><?php _e('Minutes', 'jellypress'); ?></div>
            </div>
            <div class="partial">
              <div class="value seconds">00</div>
              <div class="small"><?php _e('Seconds', 'jellypress'); ?></div>
            </div>
            <?php if ($complete_text) echo '<div class="partial complete-text">' . $complete_text . '</div>'; ?>
          </div>
        </div>
      </div>
    <?php elseif ($is_preview) : ?>
      <div class="acf-placeholder">
        <div class="acf-placeholder-label"><?php _e('You need to add some data to this block. Please click here to edit the fields in the block sidebar, alternatively change the block view mode to "edit".', 'jellypress'); ?></div>
      </div>
    <?php endif; ?>

  </div>
</section>