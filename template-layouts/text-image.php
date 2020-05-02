<?php
/**
 * Flexible layout: Text and Image block
 * Renders an section to contain Text and an Image, with an option to reorder columns
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
  <div class="col sm-12 md-4 <?php if ( $image_position == 'right' ) {echo 'order-md-2';} ?>">
    <?php echo wp_get_attachment_image( $image, $size ); ?>
  </div>
  <div class="col sm-12 md-8 <?php if ( $image_position == 'right' ) {echo 'order-md-1';} ?>">
    <?php the_sub_field( 'text' ); ?>
  </div>
</div>
