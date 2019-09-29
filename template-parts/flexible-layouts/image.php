<?php
/**
 * Flexible layout: Image block
 *
 * A template partial that is called from acf-flexible-content.php,
 * when the content editor uses ACF flexible content fields to create their page layout.
 * This partial renders an image block.
 *
 * @package jellypress
 */
?>

<?php
  $image = get_sub_field( 'image' );
  $size = 'full';
  $width = get_sub_field( 'full_width' );
?>

<div class="container">
  <div class="row <?php if ( $width == 1 ){ echo 'vw-100'; }?>">
    <div class="col md-12">
      <?php echo wp_get_attachment_image( $image, $size ); ?>
    </div>
  </div>
</div>
