<?php
/**
 * Template part for displaying a WYSIWIG section. Uses fields from Advanced Custom Fields.
 *
 * @link https://www.advancedcustomfields.com/resources/
 *
 * @package my_amazing_story
 */
?>

<?php
  $title = get_sub_field( 'title' );
?>

<div class="container">
  <?php if ($title) : ?>
    <header class="row">
      <div class="col">
        <h2 class="section-header"><?php echo $title; ?></h2>
      </div>
    </header>
  <?php endif; ?>
  <div class="row">
    <div class="col md-12">
      <?php the_sub_field( 'text' ); ?>
    </div>
  </div>
</div>
