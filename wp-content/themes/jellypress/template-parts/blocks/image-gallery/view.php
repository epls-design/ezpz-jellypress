<?php

/**
 * Image Gallery Block Template.
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

$block_attributes = jellypress_get_block_attributes($block, $context);
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

$fields = get_fields();

$container_class = 'container';

$block_width = isset($block_attributes['align']) ? $block_attributes['align'] : '';
if ($block_width == 'wide') $container_class .= ' is-wide';
elseif ($block_width == 'full') $block_attributes['class'] .= ' is-full-width';

$block_id = 'gallery-' . str_replace('block_', '', $block_attributes['block_id']);

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>

  <?php if ($content || $is_preview) : ?>
    <div class="container">
      <header class="row justify-center">
        <div class="col md-10 lg-8">
          <InnerBlocks className="<?php echo $block_attributes['text_align']; ?>" allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
        </div>
      </header>
    </div>
  <?php endif; ?>

  <div class="gallery" id="<?php echo $block_id; ?>">
    <div class="<?php echo $container_class; ?>">
      <?php if ($block_width === 'full') echo '<div class="vw-100">'; ?>
      <?php
      jellypress_acf_placeholder(
        $fields['images'],
        __('Please add some images to this block - click here to get started.', 'jellypress'),
        $is_preview
      );
      ?>
      <div class="row align-middle justify-center">
        <?php

        if ($fields['images']) {

          $wrapper_class = 'ratio-1x1 square-image ';
          if ($fields['allow_zoom'] == true) {
            // Enqueue Photoswipe
            wp_enqueue_script('photoswipe-init');
            if ($is_preview) $wrapper_class .= 'prevent-clicks';
            else $wrapper_class .= 'lightbox-image';
          }

          foreach ($fields['images'] as $gallery_image) {
            if ($fields['allow_zoom'] == true) {
              $image_full_size = wp_get_attachment_image_src($gallery_image, 'full');
            }
        ?>
            <div class="col xs-6 sm-4 lg-3">
              <figure class="<?php echo $wrapper_class; ?>">
                <?php if ($fields['allow_zoom'] == true) echo '<a href="' . $image_full_size[0] . '" data-pswp-width="' . $image_full_size[1] . '" data-pswp-height="' . $image_full_size[2] . '" target="_blank">'; ?>
                <?php echo wp_get_attachment_image($gallery_image, 'medium', null, array("class" => "gallery-image")); ?>
                <?php if ($fields['allow_zoom'] == true) echo '</a>'; ?>
              </figure>
            </div>
          <?php } ?>
        <?php
        } ?>
      </div>
      <?php if ($block_width === 'full') echo '</div>'; ?>
    </div>
  </div>

</section>

<?php if ($add_lightbox) :
  // Initialize modal
  $func = jellypress_modal_init('#gallery-' . $block_id, 'a');
  //$func = jellypress_modal_init('.gallery-group-'.$block_id);
  add_action('wp_footer', $func, 30); // 30 priority ensures it is placed below the enqueued scripts (priority 20)
endif; ?>
