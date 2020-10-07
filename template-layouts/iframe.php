<?php
/**
 * Flexible layout: iFrame
 * Renders an iFrame section
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$section_id = get_query_var('section_id');
$section = get_query_var('section');
$title = $section['title'];
$website_url = $section['website_url'];
$width = $section['full_width'];
$preamble = $section['preamble'];
?>

<?php if ($title) : ?>
  <header class="row">
    <div class="col">
      <h2 class="section-header"><?php echo jellypress_bracket_tag_replace($title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<?php if ($preamble) : ?>
  <div class="row preamble">
    <div class="col">
      <?php jellypress_content($preamble); ?>
    </div>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php if ( $width == 1 ){ echo '<div class="vw-100">'; }?>
      <div class="embed-container">
        <iframe class="embedded-iframe" src="<?php echo $website_url; ?>"></iframe>
      </div><!-- /.embed-container -->
    <?php if ( $width == 1 ){ echo '</div>'; }?>
  </div>
</div>
