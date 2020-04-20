<?php
/**
 * Flexible layout: Text block
 *
 * A template partial that is called from acf-flexible-content.php,
 * when the content editor uses ACF flexible content fields to create their page layout.
 * This partial renders a WYSIWIG editor in one column (for the display of text)
 * and an image in the adjacent column. The user can select whether the image is on the right or left.
 *
 * @package jellypress
 */
?>

<?php
  $title = get_sub_field( 'title' );
  $size = 'large';

  // These fields are in a field group we have to loop through
  if ( have_rows( 'image_info' ) ) :
    while ( have_rows( 'image_info' ) ) : the_row();
      $image = get_sub_field( 'image' );
      $image_position = get_sub_field( 'image_position' );
    endwhile;
  endif;

?>

<?php if ($title) : ?>
<header class="row">
  <div class="col">
    <h2 class="section-header"><?php echo $title; ?></h2>
  </div>
</header>
<?php endif; ?>
<div class="row align-middle">
  <div class="col md-4 <?php if ( $image_position == 'right' ) {echo 'order-md-2';} ?>">
    <?php echo wp_get_attachment_image( $image, $size ); ?>
  </div>
  <div class="col md-8 <?php if ( $image_position == 'right' ) {echo 'order-md-1';} ?>">
    <?php the_sub_field( 'text' ); ?>
  </div>
</div>
