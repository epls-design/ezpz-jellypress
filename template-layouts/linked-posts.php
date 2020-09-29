<?php
/**
 * Flexible layout: Linked Posts
 * Renders a row of simple equal-height cards
 * with featured image, title, excerpt and button.
 * The user can select whether the posts display are specified,
 * random or latest. If random or latest they can select post type
 * and quantity to display.
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
  $query_type = get_sub_field('query_type');
  $posts_array = array(); // Create an empty array to store posts ready for the loop
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

<?php
/**
 * Sets up the $posts_array array with values dependent on whether
 * the user has selected specified, random or latest.
 */

if($query_type == 'rand' || $query_type == 'date') {
  $query_post_type = get_sub_field( 'query_post_type' );
  $query_quantity = get_sub_field( 'query_quantity' );
}

if($query_type == 'specified'):
  $queried_posts = get_sub_field( 'specified_posts' );
    if ( $queried_posts ):
      foreach ( $queried_posts as $post_to_display ):
        array_push($posts_array, $post_to_display->ID);
      endforeach;
    endif;
elseif($query_type == 'rand' || $query_type == 'date'):
  $query_posts_from_db = new WP_Query( array(
    'post_type' => $query_post_type, // Accepts an array
    'posts_per_page' => $query_quantity,
    'orderby' => $query_type
  ) );
  if ( $query_posts_from_db->have_posts() ) :
    while ( $query_posts_from_db->have_posts() ) :
      // Not sure if this is the most efficient way to do this ... seems like an expensive query
      $query_posts_from_db->the_post();
      array_push($posts_array, get_the_ID());
    endwhile;
    wp_reset_postdata();
  endif;
endif;

if($posts_array) :
  echo '<div class="row equal-height">';
    global $post; // Call global $post variable
    foreach($posts_array as $queried_post):
      $post = $queried_post; // Set $post global variable to the current post object
      setup_postdata( $post ); // Set up "environment" for template tags

      echo '<article class="col xs-12 sm-6 md-4 xl-3">';
        get_template_part( 'template-components/card', get_post_type() ); // Display the post information
      echo '</article><!-- /#post-'.get_the_ID().' -->';

    endforeach;
    wp_reset_postdata();
  echo '</div>';
endif;

?>

<?php if ( have_rows( 'buttons' ) ) : ?>
  <div class="row">
    <div class="col text-center">
      <?php jellypress_show_cta_buttons(); ?>
    </div>
  </div>
<?php endif; ?>
