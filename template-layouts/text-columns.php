<?php
/**
 * Flexible layout: Text Columns
 * Renders a section containing between two and four columns of WYSIWIG text
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

<?php if ( $columns = $section['columns'] ) : ?>
  <div class="row">
    <?php foreach ($columns as $column): ?>
      <div class="col xs-12 md-0">
        <?php jellypress_content($column['editor']); ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if ( $section['buttons'] ) : ?>
  <div class="row">
    <div class="col text-center">
      <?php jellypress_show_cta_buttons($section['buttons']); ?>
    </div>
  </div>
<?php endif; ?>
