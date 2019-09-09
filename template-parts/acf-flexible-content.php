<?php
/**
 * This template part loops through all ACF flexible content rows attached to the post,
 * using a dynamic while loop to fetch the correct layout partial from template-parts/flexible-layouts
 * Partials in that folder should be named with the same convention as in ACF.
 *
 * @package jellypress
 */
?>

<?php
 // ID of the current item in the WordPress Loop
$id = get_the_ID();
// check if the flexible content field has rows of data
if ( have_rows( 'sections', $id ) ) :
    // loop through the selected ACF layouts and display the matching partial
    while ( have_rows( 'sections', $id ) ) : the_row();

      // Get common fields and save as variables
      $layout = get_row_layout();
      $is_disabled = get_sub_field( 'disable' );
      $display_options = get_sub_field( 'display_options' );
      $section_id = get_sub_field( 'section_id' );
      $background_color = get_sub_field( 'background_color' );

      // Background colour and display options are optional, let's check if they exist - and if so, create the appropriate css classes
      if ($background_color) {
        $background = ' section__'.strtolower($background_color);
      }

      if($display_options == 'only_show') {
        $display = ' hide-above-md';
      }
      elseif($display_options == 'hide') {
        $display = ' hide-below-md';
      }

      // Concatenate all css classes into one variable
      $classes = 'section__'.$layout.$background.$display;

      if ( $is_disabled != 1 ) : // Display the section, if it is not disabled ?>
        <section <?php if($section_id){echo 'id="'.strtolower($section_id).'"';} ?> class="section <?php echo $classes;?>">
          <?php get_template_part( 'template-parts/flexible-layouts/' . $layout ); ?>
        </section><!-- .section__<?php echo $layout;?> -->
      <?php endif;
    endwhile;
endif;
?>
