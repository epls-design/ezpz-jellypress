<?php
/**
 * Flexible layout: Post Archive
 * Allows the editor to select a post type and loading method
 * (scroll/button) and uses the jellypress_initialize_ajax_posts()
 * and related functions to provide a lazyloaded archive of posts
 * sorted by date.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
  $section_id = get_query_var('section_id');
  $title = get_sub_field( 'title' );
  $preamble = get_sub_field('preamble');
  $post_type = get_sub_field( 'query_post_type' );
  $loading_type = get_sub_field( 'loading_type' );
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
      <?php echo $preamble; ?>
    </div>
  </div>
<?php endif; ?>

<?php
  // Set up a new WP_Query for the specified post_type
  $args_posts_query = array(
    'post_type' => $post_type,
    //'order' => 'ASC',
    'orderby' => 'date',
  );
  $archive_query = new WP_Query( $args_posts_query );

  echo '<div class="row equal-height archive-feed feed-'.$post_type.'" id="feed-'.$post_type.'">';
    if ( $archive_query->have_posts() ) {
      while ( $archive_query->have_posts() ) {
        $archive_query->the_post();
        echo '<article class="col xs-12 sm-6 md-4 xl-3">';
          get_template_part( 'template-components/card', get_post_type() );
        echo '</article>';
      }

      if ( $archive_query->max_num_pages > 1 && $loading_type == 'button' ) {
        echo '<div class="col xs-12"><button class="button-loadmore">' . __( 'Load More...', 'jellypress' ) . '</button></div>';
      };

    } else {
      echo'<div class="col">' . __('No posts matched your criteria.', 'jellypress') . '</div>';
    }
  echo '</div>';
  wp_reset_postdata();
  ?>

<?php if ( have_rows( 'buttons' ) ) : ?>
  <div class="row">
    <div class="col text-center">
      <?php jellypress_show_cta_buttons(); ?>
    </div>
  </div>
<?php endif; ?>
<?php
jellypress_initialize_ajax_posts($archive_query, $loading_type);
 ?>
