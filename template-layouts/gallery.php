<?php
/**
 * Flexible layout: Gallery block
 * Renders a gallery block using a Wordpress gallery shortcode
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
//var_dump($block);

$gallery_images = $block['images'];
$image_size = 'medium';
$columns = 3;

if ($gallery_images ) :
// Generate string of ids ("123,456,789").
$images_string = implode(',', $gallery_images);
// Generate shortcode
$gallery_shortcode = sprintf('[gallery ids="%1$s" size="%2$s" columns="%3$s" link="none"]', $images_string, $image_size, $columns);
?>
  <div class="row">
      <?php echo do_shortcode($gallery_shortcode); ?> <?php // TODO: Can this be styled better by default ?>
  </div>
<?php endif; ?>
