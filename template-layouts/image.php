<?php
/**
 * Flexible layout: Image block
 * Renders an image section
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
  $section_id = get_query_var('section_id');
  $image = get_sub_field( 'image' );
  $size = 'full';
  $width = get_sub_field( 'full_width' );
?>

<div class="row">
  <div class="col">
    <?php if ( $width == 1 ){ echo '<div class="vw-100">'; }?>
      <?php echo wp_get_attachment_image( $image, $size ); ?>
    <?php if ( $width == 1 ){ echo '</div>'; }?>
  </div>
</div>
