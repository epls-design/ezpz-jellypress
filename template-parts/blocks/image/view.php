<?php
/**
 * Flexible layout: Image block
 * Renders an image block
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
$container_class = 'container';
//var_dump($block);

$image_id = $block['image'];
$image_size = 'full';

$block_width = $block['content_width'];
if($block_width == 'wide') $container_class .= ' is-wide';
elseif($block_width == 'full') $block_classes .= ' is-full-width';
?>

<section <?php if($block_id_opt = $block['section_id']) echo 'id="'.strtolower($block_id_opt).'"'; ?> class="<?php echo $block_classes;?>">
  <div class="<?php echo $container_class;?>">

    <div class="row">
      <div class="col">
        <?php
        if ( $block_width === 'full' ) echo '<div class="vw-100">';
          echo wp_get_attachment_image( $image_id, $image_size );
        if ( $block_width === 'full' ) echo '</div>'; ?>
      </div>
    </div>

  </div>
</section>
