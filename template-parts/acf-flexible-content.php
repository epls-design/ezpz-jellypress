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

// TODO: Change to do https://wordpress.stackexchange.com/questions/219773/conditional-to-test-if-post-has-password-protection-enabled
if( !post_password_required()):

  /**
  * Get all ACF field meta into a single array rather than querying the database for each field individually
  * Massively improve performance
  * @link https://github.com/timothyjensen/acf-field-group-values
  */
  $field_group_json = 'group_5d4037f4c3a41.json'; // Replace with the name of your field group JSON.
  $field_group_array = json_decode( file_get_contents( get_stylesheet_directory() . "/assets/acf-json/{$field_group_json}" ), true );
  $block_data = get_all_custom_field_meta( $id, $field_group_array );

  // If there are items in the flexible content field...
  if ($blocks = $block_data['sections']) :

    $i = 0;
    $total_blocks = count($blocks);

    // Loop through the flexible content field
    foreach ($blocks as $block ){
      //var_dump($block);

      // Get next block info
      if ($i < $total_blocks) {
        $next_block = $blocks[$i+1];
        //var_dump($next_block);
      }

      $block_classes = 'block'; // Reset class

      if($i == 0) {
        $block_classes .=' first'; // This is not foolproof, if any blocks are disabled this will not be accurate - so it shouldn't be used for anything important
      }
      if($i == $total_blocks - 1) {
        $block_classes .=' last'; // This is not foolproof, if any blocks are disabled this will not be accurate - so it shouldn't be used for anything important
      }

      $block_id = $block['section_id'];

      $block_layout = $block['acf_fc_layout'];
      $block_classes.= ' block__'.$block_layout; // Add layout to classes

      // Block scheduling options
      $block_show_from = $block['show_from'];
      $block_show_until = $block['show_until'];
      $current_wp_time = current_time('Y-m-d H:i:s');
      if (($block_show_from == NULL OR $block_show_from <= $current_wp_time) AND ($block_show_until == NULL OR $block_show_until>= $current_wp_time)) {
        $block_datetime_show = true;
      }
      else {
        $block_datetime_show = false;
      }

      // Block display options for smaller devices
      $block_display = $block['display_options'];
      if($block_display == 'only_show') {
        $block_classes.= ' hide-above-md';
      }
      elseif($block_display == 'hide') {
        $block_classes.= ' hide-below-md';
      }

      // Check for full-width setting
      if ($block_layout === 'image' || $block_layout === 'video' || $block_layout === 'map' || $block_layout === 'iframe' ) {
        if( $block['full_width'] == 1) {
          $block_classes.= ' block__full-width';
        };
      }

      // Background colour

      $prev_block_bg = $block_bg_color;
      if($prev_block_bg == '') $prev_block_bg = 'white'; // Reset
      $block_bg_color = $block['background_color'];
      $next_bg_color = $next_block['background_color'];

      if ($block_bg_color) {
        $block_classes.= ' bg-'.strtolower($block_bg_color);
      }

      if ( $block['disable'] != 1 AND $block_datetime_show == true) : // Display the block, if it is not disabled, and if the scheduling checks pass true ?>
        <section <?php if($block_id) echo 'id="'.strtolower($block_id).'"'; ?> class="<?php echo $block_classes;?>">
          <div class="container">
            <?php
            // @since Wordpress 5.5 --> Pass data as params to get_template_part
            $block_params = array(
              'block' => $block,
              'block_id' => $i,
              'block_bg' => $block_bg_color,
              'prev_block_bg' => $prev_block_bg
            );
            get_template_part( 'template-layouts/' . $block_layout, null, $block_params );
            ?>
          </div>
        </section>
        <?php $i++;
      endif;

    } // foreach
    unset($i); // Unset counter
  endif; // if ($blocks)

endif;
?>
