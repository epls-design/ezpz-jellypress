<?php
/**
 * Flexible layout: Image block
 * Renders an image section
 *
 * @package jellypress
 */
?>

<?php
  $image = get_sub_field( 'image' );
  $size = 'full';
  $width = get_sub_field( 'full_width' );
?>

<div class="row <?php if ( $width == 1 ){ echo 'vw-100'; }?>">
  <div class="col">
    <?php echo wp_get_attachment_image( $image, $size ); ?>
  </div>
</div>
