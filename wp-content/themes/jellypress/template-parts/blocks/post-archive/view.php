<?php

/**
 * Post Archive Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 *        This is either the post ID currently being displayed inside a query loop,
 *        or the post ID of the post hosting this block.
 * @param array $context The context provided to the block by the post or it's parent block.
 * @param array $block_attributes Processed block attributes to be used in template.
 * @param array $fields Array of ACF fields used in this block.
 *
 * Block registered with ACF using block.json
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$block_attributes = jellypress_get_block_attributes($block);
$fields = get_fields();
$text_align = $block_attributes['text_align'];

if ($text_align == 'text-center') $justify = 'justify-center';
elseif ($text_align == 'text-right') $justify = 'justify-end';
else $justify = 'justify-start';

$query_post_type = $fields['query_post_type'];
$loading_type = $fields['loading_type'];

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($fields['title']) { ?>
    <header class="row <?php echo $justify; ?> block-title">
      <div class="col md-10 lg-8">
        <h2 class="<?php echo $text_align; ?>">
          <?php echo wp_strip_all_tags($fields['title']); ?>
        </h2>
      </div>
    </header>
    <?php } ?>

    <?php if ($fields['preamble']) { ?>
    <div class="row <?php echo $justify; ?> block-preamble">
      <div class="col md-10 lg-8 <?php echo $text_align; ?>">
        <?php echo $fields['preamble']; ?>
      </div>
    </div>
    <?php } ?>

    <?php
    // Set up a new WP_Query for the specified post_type
    $args_posts_query = array(
      'post_type' => $query_post_type,
      //'order' => 'ASC',
      'orderby' => 'date',
      'post__not_in' => get_option('sticky_posts'), // Exclude sticky
      'has_password' => FALSE // Exclude password protected
    );


    if ($loading_type == 'paginated') {
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
      $offset = $paged != 1 ? get_option('posts_per_page') * ($paged - 1) : 0;
      $args_posts_query['offset'] = $offset;
    }

    if ($fields['post_categories']) $args_posts_query['tax_query'] = array(array('taxonomy' => 'category', 'field' => 'term_id', 'terms' => $fields['post_categories']));

    $archive_query = new WP_Query($args_posts_query);

    echo '<div class="row equal-height archive-feed feed-' . $query_post_type . ' ' . $justify . '" id="feed-' . $query_post_type . '">';
    if ($archive_query->have_posts()) {
      while ($archive_query->have_posts()) {
        $archive_query->the_post();
        echo '<article class="col xs-12 sm-6 md-4 xl-3">';
        get_template_part('template-parts/partials/card', get_post_type());
        echo '</article>';
      }
      if ($loading_type == 'scroll' && $preview != 1) echo '</div><div class="row"><div class="col xs-12"><div id="archive-loading"></div></div>';
      if ($archive_query->max_num_pages > 1 && $loading_type == 'button' && $preview != 1) {
        echo '</div><div class="row ' . $justify . '"><div class="col md-10 lg-8 ' . $text_align . '"><button class="button outline button-loadmore">' . __('Load More...', 'jellypress') . '</button></div>';
      };
    } else {
      echo '<div class="col md-10 lg-8 offset-md-1 offset-lg-2"><div class="callout error" role="alert">' . __('No posts matched your criteria.', 'jellypress') . '</div></div>';
    }
    echo '</div>';
    wp_reset_postdata();
    ?>

    <?php if (!empty($fields['buttons'])) : ?>
    <div class="row <?php echo $justify; ?>">
      <div class="col md-10 lg-8 text-center">
        <?php
          if ($text_align == 'text-center') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-center');
          elseif ($text_align == 'text-right') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-end');
          else jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color']);
          ?>
      </div>
    </div>
    <?php endif; ?>

    <?php
    if ($loading_type == 'paginated' && $preview != 1) jellypress_numeric_pagination('false', 4, $archive_query, '#feed-' . $query_post_type);
    elseif ($preview != 1) jellypress_initialize_ajax_posts($archive_query, $loading_type); ?>

  </div>
</section>