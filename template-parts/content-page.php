<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

?>

<section class="section">
  <div class="row">
    <div class="col">
      <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
      </header><!-- /.entry-header -->

      <?php jellypress_post_thumbnail();// TODO: Incorporate into theme
?>
      <div class="entry-content">
        <?php
        the_content();
        ?>
      </div><!-- /.entry-content -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.section__intro -->
