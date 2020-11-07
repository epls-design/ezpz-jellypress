<?php
/**
 * Flexible layout: Image block
 * Renders an image block
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$block_id = get_query_var('block_id');
$block = get_query_var('block');
$image_id = $block['image'];
$image_size = 'full';
$block_is_fullwidth = $block['full_width'];
?>

<div class="row">
  <div class="col">
    <?php if ( $block_is_fullwidth == 1 ){ echo '<div class="vw-100">'; }?>
      <?php echo wp_get_attachment_image( $image_id, $image_size ); ?>
    <?php if ( $block_is_fullwidth == 1 ){ echo '</div>'; }?>
  </div>
</div>
