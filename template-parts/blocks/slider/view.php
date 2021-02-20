<?php
/**
 * Flexible layout: Slider
 * Allows the editor to add a slider to the post with slides containing
 * an image, text, optional heading and link.
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

if($display_arrows = $block['display_arrows']) $display_arrows = 'true';
else $display_arrows = 'false';

if($display_pagination = $block['display_pagination']) $display_pagination = 'true';
else $display_pagination = 'false';

$slider_speed = $block['slider_duration']*1000;

$show_progress_bar = true; // Progress Bar is an option in php rather than the front end because it will usually not be useful.

?>

<section <?php if($block_id_opt = $block['section_id']) echo 'id="'.strtolower($block_id_opt).'"'; ?> class="<?php echo $block_classes;?>">
  <div class="container">

<?php if ( $slides = $block['slides'] ) :

  $slider_id = 'slider-'.$block_id;

  $number_of_slides = count($slides);

  if($number_of_slides > 1) {
    // Note: Apply class .has-pagination to the splide element to make the dots numbered instead of dots
    // Note: Apply class .has-inner-arrows to make the arrows stay inside the container
    echo '<div class="splide slider" id="'.$slider_id.'">
          <div class="splide__slider">
          <div class="splide__track">
          <div class="splide__list">';
    $slide_class = 'splide__slide slide';
  }
  else {
    echo '<div class="slider">';
    $slide_class = 'slide single';
  }

  $i = 0;

  foreach ($slides as $slide):

    $slide_params = array(
      'slide' => $slide,
      'block_id' => $block_id,
      'slide_id' => $i,
      'slide_class' => $slide_class,
    );
    get_template_part( 'template-parts/components/slider/slide', 'basic', $slide_params );

    $i++;
  endforeach;

  if($number_of_slides > 1) echo '</div></div></div>';

  if($number_of_slides > 1 && $show_progress_bar) : ?>
    <div class="splide__progress">
      <div class="splide__progress__bar">
      </div>
    </div>
    <?php endif;

  echo '</div>';

endif; ?>

  </div>
</section>

<?php
if($number_of_slides > 1) {
  add_action('wp_footer',
  jellypress_splide_init('#'.$slider_id, 1, 1, 1, 1, $display_arrows, $display_pagination, $slider_speed),
  30);
}
?>
