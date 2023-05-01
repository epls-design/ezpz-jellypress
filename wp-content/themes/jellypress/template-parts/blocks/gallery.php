<?php

/**
 * Flexible layout: Gallery block
 * Renders a simple image gallery block with the option to enable
 * a MagnificPopup lightbox gallery
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$gallery_images = $block['images'];
$image_size = 'medium';

$add_lightbox = $block['add_lightbox'];
$lightbox_display_titles = $block['lightbox_display_titles'];

if ($gallery_images) : ?>

  <section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?php echo $block_classes; ?>">
    <div class="container">

      <div class="gallery" id="gallery-<?php echo $block_id; ?>">
        <div class="row align-middle justify-center">
          <?php foreach ($gallery_images as $gallery_image) {
            echo '<div class="col xs-6 sm-4 lg-3">';

            if ($add_lightbox && $lightbox_display_titles) echo '<a href="' . wp_get_attachment_image_url($gallery_image, 'large') . '" title="' . get_the_title($gallery_image) . '">';
            elseif ($add_lightbox & !$lightbox_display_titles) echo '<a href="' . wp_get_attachment_image_url($gallery_image, 'large') . '">';

            echo wp_get_attachment_image($gallery_image, $image_size, null, array("class" => "gallery-image"));

            if ($add_lightbox) echo '</a>';

            echo '</div>';
          } ?>
        </div>
      </div>

    </div>
  </section>

<?php endif; ?>

<?php if ($add_lightbox) :
  // Initialize modal
  $func = jellypress_modal_init('#gallery-' . $block_id, 'a');
  //$func = jellypress_modal_init('.gallery-group-'.$block_id);
  add_action('wp_footer', $func, 30); // 30 priority ensures it is placed below the enqueued scripts (priority 20)
endif; ?>