<?php
/**
 * Flexible layout: Video block
 * Renders a video section
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
  $section_id = get_query_var('section_id');
  $title = get_sub_field( 'title' );
  $video = get_sub_field( 'video' );
  $width = get_sub_field( 'full_width' );
?>

<?php if ($title) : ?>
<header class="row">
  <div class="col">
    <h2 class="section-header"><?php echo jellypress_bracket_tag_replace($title); ?></h2>
  </div>
</header>
<?php endif; ?>
<div class="row">
  <div class="col">
    <?php if ( $width == 1 ){ echo '<div class="vw-100">'; }?>
      <div class="embed-container">
        <?php echo $video; ?>
      </div><!-- /.embed-container -->
    <?php if ( $width == 1 ){ echo '</div>'; }?>
  </div>
</div>
