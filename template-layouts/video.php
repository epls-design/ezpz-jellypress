<?php
/**
 * Flexible layout: Video block
 * Renders a video section
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$section_id = get_query_var('section_id');
$section = get_query_var('section');
$title = $section['title'];
$video = $section['video'];
$width = $section['full_width'];
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
        <?php echo wp_oembed_get($video); ?>
      </div><!-- /.embed-container -->
    <?php if ( $width == 1 ){ echo '</div>'; }?>
  </div>
</div>
