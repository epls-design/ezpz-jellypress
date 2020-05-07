<?php
/**
 * Flexible layout: Text block
 * Renders a section containing a column of WYSIWIG text
 *
 * @package jellypress
 */
?>

<?php
  $section_id = get_query_var('section_id');
  $title = get_sub_field( 'title' );
?>

<?php if ($title) : ?>
<header class="row">
  <div class="col">
    <h2 class="section-header"><?php echo $title; ?></h2>
  </div>
</header>
<?php endif; ?>
<div class="row">
  <div class="col">
    <?php the_sub_field( 'text' ); ?>
  </div>
</div>
