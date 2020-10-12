<?php
/**
 * This template part loops through all ACF flexible content rows attached to the post,
 * using a dynamic while loop to fetch the correct layout partial from /template-layouts
 * Partials in that folder should be named with the same convention as in ACF.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

 // ID of the current page
$id = get_the_ID();

/**
 * Get all ACF field meta into a single array rather than querying the database for each field individually
 * Massively improve performance
 * @link https://github.com/timothyjensen/acf-field-group-values
 */
$field_group_json = 'group_5d4037f4c3a41.json'; // Replace with the name of your field group JSON.
$field_group_array = json_decode( file_get_contents( get_stylesheet_directory() . "/assets/acf-json/{$field_group_json}" ), true );
$block_data = get_all_custom_field_meta( $id, $field_group_array );

// If there are items in the flexible content field...
if ($block_data['sections']) :
  $i = 1;

  // Loop through the flexible content field
  foreach ($block_data['sections'] as $jellypress_block ){
    //var_dump($jellypress_block);

    $block_classes = 'block'; // Reset class

    // Get common fields and save as variables
    $block_layout = $jellypress_block['acf_fc_layout'];
    $block_disabled = $jellypress_block['disable'];
    $block_display = $jellypress_block['display_options'];
    $block_id = $jellypress_block['section_id'];
    $block_bg_color = $jellypress_block['background_color'];

    $block_classes.= ' block__'.$block_layout; // Add layout to classes

    // Block scheduling options
    $block_show_from = $jellypress_block['show_from'];
    $block_show_until = $jellypress_block['show_until'];
    $current_wp_time = current_time('Y-m-d H:i:s');
    if (($block_show_from == NULL OR $block_show_from <= $current_wp_time) AND ($block_show_until == NULL OR $block_show_until>= $current_wp_time)) {
      $block_datetime_show = true;
    }
    else {
      $block_datetime_show = false;
    }

    // Block display options for smaller devices
    if($block_display == 'only_show') {
      $block_classes.= ' hide-above-md';
    }
    elseif($block_display == 'hide') {
      $block_classes.= ' hide-below-md';
    }

    // Check for full-width setting
    if ($block_layout === 'image' || $block_layout === 'video' || $block_layout === 'map' || $block_layout === 'iframe' ) {
      if( $jellypress_block['full_width'] == 1) {
        $block_classes.= ' block__full-width';
      };
    }

    // Background colour
    if ($block_bg_color) {
      $block_classes.= ' bg-'.strtolower($block_bg_color);
    }

    if ( $block_disabled != 1 AND $block_datetime_show == true) : // Display the block, if it is not disabled, and if the scheduling checks pass true ?>
      <section <?php if($block_id) echo 'id="'.strtolower($block_id).'"'; ?> class="<?php echo $block_classes;?>">
        <div class="container">
          <?php
          set_query_var('jellypress_block', $jellypress_block ); // Pass current array to the layout
          set_query_var('block_id', $i); // Pass block ID - useful for generating unique IDs eg. for a carousel
          get_template_part( 'template-layouts/' . $block_layout  );
          ?>
        </div>
      </section>
      <?php $i++;
    endif;

  } // foreach
  unset($i); // Unset counter
endif; // if ($block_data['sections'])
?>
