<?php
/**
 * Flexible layout: Text Columns
 * Renders a section containing between two and four columns of WYSIWIG text
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
  $section_id = get_query_var('section_id');
  $title = get_sub_field( 'title' );
?>

<?php if ($title) : ?>
<header class="row">
  <div class="col">
    <h2 class="section-header"><?php echo jellypress_bracket_tag_replace($title); ?></h2>
  </div>
</header>
<?php endif; ?>

<?php if ( have_rows( 'columns' ) ) : ?>
  <div class="row">
    <?php while ( have_rows( 'columns' ) ) : the_row(); ?>
      <div class="col xs-12 md-0">
        <?php the_sub_field( 'editor' ); ?>
      </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>

<?php if ( have_rows( 'buttons' ) ) : ?>
  <div class="row">
    <div class="col text-center">
      <?php jellypress_show_cta_buttons(); ?>
    </div>
  </div>
<?php endif; ?>
