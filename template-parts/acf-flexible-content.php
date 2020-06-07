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

?>

<?php
 // ID of the current item in the WordPress Loop
$id = get_the_ID();
// check if the flexible content field has rows of data
if ( have_rows( 'sections', $id ) ) :
  $i = 0;
  // loop through the selected ACF layouts and display the matching partial
  while ( have_rows( 'sections', $id ) ) : the_row();

    $classes = 'section id-'.$i; // Reset class

    // Get common fields and save as variables
    $layout = get_row_layout();
    $is_disabled = get_sub_field( 'disable' );
    $display_options = get_sub_field( 'display_options' );
    $section_id = get_sub_field( 'section_id' );
    $background_color = get_sub_field( 'background_color' );

    $classes.= ' section__'.$layout; // Add layout to classes

    // Scheduling
    $show_from = get_sub_field( 'show_from' );
    $show_until = get_sub_field( 'show_until' );
    $current_wp_time = current_time('Y-m-d H:i:s');
    if (($show_from == NULL OR $show_from <= $current_wp_time) AND ($show_until == NULL OR $show_until>= $current_wp_time)) {
      $scheduled = true;
    }
    else {
      $scheduled = false;
    }

    // Background colour and display options are optional, let's check if they exist - and if so, create the appropriate css classes
    if ($background_color) {
      $classes.= ' bg-'.strtolower($background_color);
    }
    if($display_options == 'only_show') {
      $classes.= ' hide-above-md';
    }
    elseif($display_options == 'hide') {
      $classes.= ' hide-below-md';
    }

    if ( $is_disabled != 1 AND $scheduled == true) : // Display the section, if it is not disabled, and if the scheduling checks pass true ?>
    <section <?php if($section_id){echo 'id="'.strtolower($section_id).'"';} ?> class="<?php echo $classes;?>">
      <div class="container">
        <?php
        set_query_var('section_id', $i); // Pass current ID to template part. Useful to give unique IDs to elements contained in the section
        get_template_part( 'template-layouts/' . $layout ); ?>
      </div>
    </section><!-- /.section__<?php echo $layout;?> -->
    <?php $i++;
    endif;
  endwhile;
endif;
?>
