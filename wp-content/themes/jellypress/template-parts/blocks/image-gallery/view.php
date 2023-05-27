<?php

/**
 * Image Gallery Block Template.
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

$block_id = str_replace('block_', '', $block['id']);

$add_lightbox = $fields['add_lightbox'];
$lightbox_display_titles = $fields['lightbox_display_titles'];
?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>

  <?php if ($fields['title']) {
    if ($text_align == 'text-left') $justify = 'start';
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

  <figure class="gallery" id="gallery-<?php echo $block_id; ?>">
    <div class="<?php echo $container_class; ?>">
      <?php if ($block_width === 'full') echo '<div class="vw-100">'; ?>
      <div class="row align-middle justify-center">
        <?php foreach ($fields['images'] as $gallery_image) {
          echo '<div class="col xs-6 sm-4 lg-3">';

          if ($add_lightbox && $lightbox_display_titles) echo '<a href="' . wp_get_attachment_image_url($gallery_image, 'large') . '" title="' . get_the_title($gallery_image) . '">';
          elseif ($add_lightbox & !$lightbox_display_titles) echo '<a href="' . wp_get_attachment_image_url($gallery_image, 'large') . '">';

          echo wp_get_attachment_image($gallery_image, 'medium', null, array("class" => "gallery-image"));

          if ($add_lightbox) echo '</a>';

          echo '</div>';
        } ?>
      </div>
      <?php if ($block_width === 'full') echo '</div>'; ?>
    </div>
  </figure>

</section>

<?php if ($add_lightbox) :
  // Initialize modal
  $func = jellypress_modal_init('#gallery-' . $block_id, 'a');
  //$func = jellypress_modal_init('.gallery-group-'.$block_id);
  add_action('wp_footer', $func, 30); // 30 priority ensures it is placed below the enqueued scripts (priority 20)
endif; ?>