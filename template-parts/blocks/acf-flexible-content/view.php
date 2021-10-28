<?php

/**
 * This template part loops through all ACF flexible content rows attached to the post,
 * using a dynamic while loop to fetch the correct layout partial from /template-layouts
 * Partials in that folder should be named with the same convention as in ACF.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// ID of the current page
$id = get_the_ID();

// Get Params from get_template_part:
$is_stack = $args['is_stack'];
if ($is_stack) $stack_id = $args['stack_id'];

if (!post_password_required()) :

  /**
   * Get all ACF field meta into a single array rather than querying the database for each field individually
   * Massively improve performance
   * @link https://github.com/timothyjensen/acf-field-group-values
   */
  $field_group_json = 'group_5d4037f4c3a41.json'; // Replace with the name of your field group JSON.
  $field_group_array = json_decode(file_get_contents(get_stylesheet_directory() . "/assets/acf-json/{$field_group_json}"), true);
  $block_data = get_all_custom_field_meta($id, $field_group_array);

  // If there are items in the flexible content field...
  if (!empty($block_data['sections'])) :
    $blocks = $block_data['sections'];

    $i = 0;
    $total_blocks = count($blocks);

    // Loop through the flexible content field
    foreach ($blocks as $block) {
      //var_dump($block);

      // Get next block info
      if ($i < $total_blocks) {
        $next_block = $blocks[$i + 1];
        //var_dump($next_block);
      }

      $block_classes = 'block'; // Reset class

      if ($is_stack) $block_classes .= ' is_stack';

      //if ($i == 0) {
      //  $block_classes .= ' first'; // This is not foolproof, if any blocks are disabled this will not be accurate - so it shouldn't be used for anything important
      //}
      //if ($i == $total_blocks - 1) {
      //  $block_classes .= ' last'; // This is not foolproof, if any blocks are disabled this will not be accurate - so it shouldn't be used for anything important
      //}

      $block_layout = $block['acf_fc_layout'];
      $block_classes .= ' block__' . $block_layout; // Add layout to classes

      // Block scheduling options
      $block_show_from = $block['show_from'];
      $block_show_until = $block['show_until'];
      $current_wp_time = current_time('Y-m-d H:i:s');
      if (($block_show_from == NULL or $block_show_from <= $current_wp_time) and ($block_show_until == NULL or $block_show_until >= $current_wp_time)) {
        $block_datetime_show = true;
      } else {
        $block_datetime_show = false;
      }

      // Block display options for smaller devices
      $block_display = $block['display_options'];
      if ($block_display == 'only_show') {
        $block_classes .= ' hide-above-md';
      } elseif ($block_display == 'hide') {
        $block_classes .= ' hide-below-md';
      }

      // Background colour

      $prev_block_bg = isset($block_bg_color) ? $block_bg_color : null;

      $block_bg_color = $block['background_color'];
      $next_bg_color = $next_block['background_color'];

      if ($block_bg_color) {
        $block_classes .= ' bg-' . strtolower($block_bg_color);
      }

      if ($block['disable'] != 1 and $block_datetime_show == true) : // Display the block, if it is not disabled, and if the scheduling checks pass true

        // Append Stack ID to block; this will allow JS libs to work still
        if ($is_stack) $block_id = $stack_id . '-' . $i;
        else $block_id = $i;

        // @since Wordpress 5.5 --> Pass data as params to get_template_part
        $block_params = array(
          'block' => $block,
          'block_id' => $block_id,
          'block_classes' => $block_classes,
          'block_bg_color' => $block_bg_color,
          //              'prev_block_bg' => $prev_block_bg
        );
        get_template_part('template-parts/blocks/' . $block_layout . '/view', null, $block_params);
        $i++;
      endif;
    } // foreach
    unset($i); // Unset counter
  endif; // if ($blocks)

endif;
