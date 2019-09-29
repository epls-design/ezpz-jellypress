<?php
/**
 * Flexible layout: Text block
 *
 * A template partial that is called from acf-flexible-content.php,
 * when the content editor uses ACF flexible content fields to create their page layout.
 * This partial renders a WYSIWIG editor block - usually used for the display of text.
 *
 * @package jellypress
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
