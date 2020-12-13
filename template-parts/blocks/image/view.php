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
//var_dump($block);

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

<!-- TODO: Add a js file into one of these, and edit the gruntfile to concat all of these into project.js - eg. try it with countup.js? -->
