<?php
/**
 * Flexible layout: Text and Media block
 * Renders a section to contain Text and a Media element
 * (image or video) with the option to reorder columns
 * and change the ratio split of columns
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
$section_id = get_query_var('section_id');
$title = get_sub_field( 'title' );
$size = 'large';

$align = get_sub_field( 'vertical_align' );
if ($align == NULL) {
  $align = 'top';
}

$column_split = get_sub_field( 'column_split' );
if ($column_split == 'large-text') {
  $text_class = 'md-8 lg-7';
  $media_class = 'md-4 lg-5';
}
elseif ($column_split == 'large-media') {
  $text_class = 'md-4 lg-5';
  $media_class = 'md-8 lg-7';
}
elseif ($column_split == 'equal' OR $column_split == NULL) {
  $text_class = 'md-6';
  $media_class = 'md-6';
}

// These fields are in a field group we have to loop through
if ( have_rows( 'media_item' ) ) :
  while ( have_rows( 'media_item' ) ) : the_row();
    $type = get_sub_field( 'type' );
    $media_position = get_sub_field( 'media_position' );
    $image = get_sub_field( 'image' );
    $video = get_sub_field( 'video' );
  endwhile;
endif;

if ( $media_position == 'right' ) {
  $text_class .= ' order-md-1';
  $media_class .= ' order-md-2';
}

?>

<?php if ($title) : ?>
  <header class="row">
    <div class="col">
      <h2 class="section-header"><?php echo $title; ?></h2>
    </div>
  </header>
<?php endif; ?>
<div class="row align-<?php echo $align;?>">
  <div class="col sm-12 <?php echo $media_class; ?>">
    <?php if ($type == 'image'){
        echo wp_get_attachment_image( $image, $size );
      }
      elseif ($type == 'video'){
        echo '<div class="embed-container">'.$video.'</div>';
      }
    ?>
  </div>
  <div class="col sm-12 <?php echo $text_class; ?>">
    <?php the_sub_field( 'text' ); ?>
  </div>
</div>
