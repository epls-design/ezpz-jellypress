<?php
/**
 * Template part for displaying a WYSIWIG section. Uses fields from Advanced Custom Fields.
 *
 * @link https://www.advancedcustomfields.com/resources/
 *
 * @package my_amazing_story
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
