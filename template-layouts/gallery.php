<?php
/**
 * Flexible layout: Gallery block
 * Renders a gallery section using a Wordpress gallery shortcode
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
  $section_id = get_query_var('section_id');
  $image_ids = get_sub_field( 'images' );
  $size = 'medium';
  $columns = 3
?>

<?php
if ( $image_ids ) :
// Generate string of ids ("123,456,789").
$images_string = implode( ',', $image_ids );
// Generate shortcode
$gallery_shortcode = sprintf( '[gallery ids="%1$s" size="%2$s" columns="%3$s" link="none"]', $images_string, $size, $columns );
?>
<div class="row">
  <?php echo do_shortcode( $gallery_shortcode ); ?>
</div>
<?php endif; ?>
