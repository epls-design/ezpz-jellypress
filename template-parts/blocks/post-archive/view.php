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

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$block_title = $block['title'];
$block_preamble = $block['preamble'];
$query_post_type = $block['query_post_type'];
$loading_type = $block['loading_type'];
?>

<section <?php if($block_id_opt = $block['section_id']) echo 'id="'.strtolower($block_id_opt).'"'; ?> class="<?php echo $block_classes;?>">
  <div class="container">

  <?php if ($block_title) : $title_align = $block['title_align']; ?>
    <header class="row justify-center block-title">
      <div class="col md-10 lg-8">
        <h2 class="text-<?php echo $title_align;?>"><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
      </div>
    </header>
  <?php endif; ?>

  <?php if ($block_preamble) : ?>
    <div class="row justify-center block-preamble">
      <div class="col md-10 lg-8">
        <?php echo jellypress_content($block_preamble); ?>
      </div>
    </div>
  <?php endif; ?>

  <?php
    // Set up a new WP_Query for the specified post_type
    $args_posts_query = array(
      'post_type' => $query_post_type,
      //'order' => 'ASC',
      'orderby' => 'date',
    );
    $archive_query = new WP_Query( $args_posts_query );
    // TODO: Add a query for taxonomy?

    echo '<div class="row equal-height archive-feed feed-'.$query_post_type.'" id="feed-'.$query_post_type.'">';
      if ( $archive_query->have_posts() ) {
        while ( $archive_query->have_posts() ) {
          $archive_query->the_post();
          echo '<article class="col xs-12 sm-6 md-4 xl-3">';
            get_template_part( 'template-parts/components/card/card', get_post_type() );
          echo '</article>';
        }
        if ( $loading_type == 'scroll' ) echo '</div><div class="row"><div class="col xs-12"><div id="archive-loading"></div></div>';
        if ( $archive_query->max_num_pages > 1 && $loading_type == 'button' ) {
          echo '</div><div class="row"><div class="col xs-12"><button class="button outline button-loadmore">' . __( 'Load More...', 'jellypress' ) . '</button></div>';
        };

      } else {
        echo '<div class="col md-10 lg-8 offset-md-1 offset-lg-2"><div class="callout error" role="alert">'.__('No posts matched your criteria.', 'jellypress').'</div></div>';
      }
    echo '</div>';
    wp_reset_postdata();
    ?>

  <?php if ( $block['buttons'] ) : ?>
    <div class="row">
      <div class="col text-center">
        <?php jellypress_display_cta_buttons($block['buttons']); ?>
      </div>
    </div>
  <?php endif; ?>

  <?php jellypress_initialize_ajax_posts($archive_query, $loading_type); ?>

</div>
</section>
