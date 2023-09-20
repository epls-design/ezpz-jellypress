<?php

/**
 * Map Block Template.
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
 * TODO: Migrate to Leaflet.js
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$block_attributes = jellypress_get_block_attributes($block);
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

$fields = get_fields();
$container_class = 'container';
$text_align = $block_attributes['text_align'];

$block_width = isset($block_attributes['align']) ? $block_attributes['align'] : '';
if ($block_width == 'wide') $container_class .= ' is-wide';
elseif ($block_width == 'full') $block_attributes['class'] .= ' is-full-width';
elseif ($block_width === 'center') $justify = 'center';
?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>

  <?php if ($content || $is_preview) : ?>
    <div class="container">
      <header class="row justify-center">
        <div class="col md-10 lg-8">
          <InnerBlocks className="<?php echo $text_align; ?>" allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
        </div>
      </header>
    </div>
  <?php endif; ?>

  <div class="<?php echo $container_class; ?>">
    <?php
    if ($block_width === 'full') echo '<div class="vw-100">';
    elseif ($block_width === 'center') echo '<div class="row justify-center"><div class="col md-10 lg-8">';

    if (get_global_option('google_maps_api_key') && ($map_locations = $fields['locations'])) :
      jellypress_display_map_markers($map_locations);
    elseif (current_user_can('publish_posts')) :
      // Show a warning for the admin to add an API key
      echo '<div class="callout error">' .
        sprintf(
          /* translators: %s link to theme options page. */
          __('You need to <a href="%s" class="callout-link">add a Google Maps API key</a> in order to display a map on your website.', 'jellypress'),
          esc_url(get_admin_url(null, 'admin.php?page=apis'))
        )
        . '</div>';
    endif; // google_maps_api_key

    if ($block_width === 'full') echo '</div>';
    elseif ($block_width === 'center') echo '</div></div>';
    ?>
  </div>

</section>