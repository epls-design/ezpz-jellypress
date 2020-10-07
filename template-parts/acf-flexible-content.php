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
$meta_data = get_all_custom_field_meta( $id, $field_group_array );

// If there are items in the flexible content field...
if ($meta_data['sections']) :
  $i = 1;

  // Loop through the flexible content field
  foreach ($meta_data['sections'] as $section ){
    //var_dump($section);

    $classes = 'section section-'.$i; // Reset class

    // Get common fields and save as variables
    $layout = $section['acf_fc_layout'];
    $is_disabled = $section['disable'];
    $display_options = $section['display_options'];
    $section_id = $section['section_id'];
    $background_color = $section['background_color'];

    $classes.= ' section__'.$layout; // Add layout to classes

    // Block scheduling options
    $show_from = $section['show_from'];
    $show_until = $section['show_until'];
    $current_wp_time = current_time('Y-m-d H:i:s');
    if (($show_from == NULL OR $show_from <= $current_wp_time) AND ($show_until == NULL OR $show_until>= $current_wp_time)) {
      $scheduled = true;
    }
    else {
      $scheduled = false;
    }

    // Background colour
    if ($background_color) {
      $classes.= ' bg-'.strtolower($background_color);
    }

    // Block display options for smaller devices
    if($display_options == 'only_show') {
      $classes.= ' hide-above-md';
    }
    elseif($display_options == 'hide') {
      $classes.= ' hide-below-md';
    }

    // Check for full-width setting
    if ($layout === 'image' || $layout === 'video' || $layout === 'map' || $layout === 'iframe' ) {
      if( $section['full_width'] == 1) {
        $classes.= ' section__full-width';
      };
    }

    if ( $is_disabled != 1 AND $scheduled == true) : // Display the section, if it is not disabled, and if the scheduling checks pass true ?>
      <section <?php if($section_id) echo 'id="'.strtolower($section_id).'"'; ?> class="<?php echo $classes;?>">
        <div class="container">
          <?php
          set_query_var('section', $section ); // Pass current array to the layout
          set_query_var('section_id', $i); // Pass section ID - useful for generating unique IDs eg. for a carousel
          get_template_part( 'template-layouts/' . $layout  );
          ?>
        </div>
      </section><!-- /.section-<?php echo $i;?> .section__<?php echo $layout;?> -->
      <?php $i++;
    endif;

  } // foreach
  unset($i); // Unset counter
endif; // if ($meta_data['sections'])
?>
