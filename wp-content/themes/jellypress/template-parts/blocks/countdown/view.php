<?php

/**
 * Countdown Block Template.
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

$complete_text = $fields['complete_text'];
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
    <div class="row justify-center block-preamble">
      <div class="col md-10 lg-8 <?php echo $text_align; ?>">
        <?php echo $fields['preamble']; ?>
      </div>
    </div>
    <?php } ?>

    <?php if (!empty($fields['buttons'])) : ?>
    <div class="row justify-center">
      <div class="col md-10 lg-8 text-center">
        <?php
          if ($text_align == 'text-center') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-center');
          elseif ($text_align == 'text-right') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-end');
          else jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color']);            ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($countdown = $fields['countdown_to']) :
      $countdown_id = 'countdown-' . $block_id;
      $countdown_tz = $fields['time_zone'];
      $countdown = $countdown . ' ' . $countdown_tz;
      // eg. 'November 4 2020 18:00:00 GMT+0000'
    ?>
    <div class="row justify-center <?php echo $text_align; ?>">
      <div class="col md-10 lg-8">
        <div id="<?php echo $countdown_id; ?>" class="countdown<?php if ($complete_text) echo ' has-complete-text'; ?>">
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
    <?php endif; ?>

  </div>
</section>

<?php if ($countdown) {
  add_action(
    'wp_footer',
    jellypress_countdown_init($countdown_id, $countdown),
    30
  ); // 30 priority ensures it is placed below the enqueued scripts (priority 20)
} ?>