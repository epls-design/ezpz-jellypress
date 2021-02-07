<?php
/**
 * Flexible layout: Image block
 * Renders an image block
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// TODO: Change width to allow columns, breaking out of container and full width

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$image_id = $block['image'];
$image_size = 'full';
$block_is_fullwidth = $block['full_width'];
?>

<section <?php if($block_id_opt = $block['section_id']) echo 'id="'.strtolower($block_id_opt).'"'; ?> class="<?php echo $block_classes;?>">
  <div class="container">

    <div class="row">
      <div class="col">
        <?php if ( $block_is_fullwidth == 1 ){ echo '<div class="vw-100">'; }?>
          <?php echo wp_get_attachment_image( $image_id, $image_size ); ?>
        <?php if ( $block_is_fullwidth == 1 ){ echo '</div>'; }?>
      </div>
    </div>

  </div>
</section>
