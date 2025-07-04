<?php

/**
 * Widget > Socials Block Template.
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

$menu_exists = false;

$block_attributes = jellypress_get_widget_attributes($block, $is_preview);

$fields = get_fields();

// Bail if no items
if (empty($fields['social_links'])) {
  if ($is_preview) {
    echo '<p class="text-error weight-bold">' . __('No social links added. Widget will not display on front-end.', 'jellypress') . '</p>';
  }
  return;
}

?>

<div class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <?php
  if ($fields['widget_title']) {
    echo '<h3 class="widget-title h6">' . esc_html($fields['widget_title']) . '</h3>';
  }
  ?>
  <nav>
    <ul class="social-channels">
      <?php
      foreach ($fields['social_links'] as $link) {
        $channel = jellypress_get_social_channel($link['channel']);
      ?>
        <li>
          <a href="<?php echo esc_url($channel['url']); ?>" target="_blank" rel="noopener noreferrer">
            <span class="screen-reader-text">
              <?php
              echo sprintf(
                esc_html__('Follow us on %s', 'jellypress'),
                esc_html($channel['name'])
              );
              ?>
            </span>
            <?php echo jellypress_icon($channel['icon'], 'social-icon'); ?>
          </a>
        </li>
      <?php
      }
      ?>
    </ul>
  </nav>
</div>