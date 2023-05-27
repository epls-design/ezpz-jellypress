<?php

/**
 * Linked Posts Block Template.
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

$query_type = $fields['query_type'];
$posts_array = array(); // Create an empty array to store posts ready for the loop
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
    /**
     * Sets up the $posts_array array with values dependent on whether
     * the user has selected specified, random or latest.
     */

    if ($query_type == 'rand' || $query_type == 'date') {
      $query_post_type = $fields['query_post_type'];
      $query_quantity = $fields['query_quantity'];
    }

    if ($query_type == 'specified') :
      $queried_posts = $fields['specified_posts'];
      if ($queried_posts) :
        foreach ($queried_posts as $post_to_display) :
          array_push($posts_array, $post_to_display);
        endforeach;
      endif;
    elseif ($query_type == 'rand' || $query_type == 'date') :
      $query_posts_from_db = new WP_Query(array(
        'post_type' => $query_post_type, // Accepts an array
        'posts_per_page' => $query_quantity,
        'orderby' => $query_type,
        'post__not_in' => get_option('sticky_posts'), // Exclude sticky
        'has_password' => FALSE // Exclude password protected
      ));

      if ($query_posts_from_db->have_posts()) :
        while ($query_posts_from_db->have_posts()) :
          // Not sure if this is the most efficient way to do this ... seems like an expensive query
          $query_posts_from_db->the_post();
          array_push($posts_array, get_the_ID());
        endwhile;
        wp_reset_postdata();
      endif;
    endif;

    echo '<div class="row equal-height ' . $justify . '">';
    if ($posts_array) :
      global $post; // Call global $post variable
      foreach ($posts_array as $queried_post) :
        $post = $queried_post; // Set $post global variable to the current post object
        setup_postdata($post); // Set up "environment" for template tags

        echo '<article class="col xs-12 sm-6 md-4 xl-3">';
        get_template_part('template-parts/partials/card', get_post_type()); // Display the post information
        echo '</article>';

      endforeach;
      wp_reset_postdata();
    else :
      echo '<div class="col md-10 lg-8"><div class="callout error" role="alert">' . __('No posts matched your criteria.', 'jellypress') . '</div></div>';
    endif;
    echo '</div>';

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

  </div>
</section>