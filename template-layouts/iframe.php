<?php
/**
 * Flexible layout: iFrame
 * Renders an iFrame section
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
  $section_id = get_query_var('section_id');
  $title = get_sub_field( 'title' );
  $website_url = get_sub_field( 'website_url' );
  $width = get_sub_field( 'full_width' );
  $preamble = get_sub_field('preamble');
?>

<?php if ($title) : ?>
  <header class="row">
    <div class="col">
      <h2 class="section-header"><?php echo jellypress_bracket_tag_replace($title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<?php if ($preamble) : ?>
  <div class="row">
    <div class="col">
      <?php echo $preamble; ?>
    </div>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php if ( $width == 1 ){ echo '<div class="vw-100">'; }?>
      <div class="embed-container">
        <iframe src="<?php echo $website_url; ?>"></iframe>
      </div><!-- /.embed-container -->
    <?php if ( $width == 1 ){ echo '</div>'; }?>
  </div>
</div>
