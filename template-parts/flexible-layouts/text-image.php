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
  $title = get_sub_field( 'title' );
  $image = get_sub_field( 'image' );
  $size = 'large';
  $image_position = get_sub_field( 'image_position' );
?>

<div class="container">
  <?php if ($title) : ?>
    <header class="row">
      <div class="col">
        <h2 class="section-header"><?php echo $title; ?></h2>
      </div>
    </header>
  <?php endif; ?>
  <div class="row align-middle">
    <div class="col md-4 <?php if ( $image_position == 'right' ) {echo 'push-md-8';} ?>">
      <?php echo wp_get_attachment_image( $image, $size ); ?>
    </div>
    <div class="col md-8 <?php if ( $image_position == 'right' ) {echo 'pull-md-4';} ?>">
      <?php the_sub_field( 'text' ); ?>
    </div>
  </div>
</div>
