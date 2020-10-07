<?php
/**
 * Flexible layout: Text block
 * Renders a section containing a column of WYSIWIG text
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$section_id = get_query_var('section_id');
$section = get_query_var('section');
$title = $section['title'];
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
    <?php jellypress_content($section['text']); ?>
    <?php jellypress_show_cta_buttons($section['buttons']); ?>
  </div>
</div>
