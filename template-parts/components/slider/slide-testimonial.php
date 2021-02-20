<?php
/**
 * Template part for displaying a testimonial slide
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$testimonial = $args['testimonial'];
$block_id = $args['block_id'];
$testimonial_id = $args['testimonial_id'];
$testimonial_class = $args['testimonial_class'];

$testimonial_link = $testimonial['testimonial_link'];
$testimonial_image = $testimonial['testimonial_image'];
$testimonial_citation = $testimonial['testimonial_citation'];

//var_dump($testimonial);
?>

<div class="<?php echo $testimonial_class;?>" id="slide-<?php echo $block_id.'-'.$testimonial_id;?>">
<div class="row justify-center">
  <div class="<?php echo $args['col_class'];?>">
    <?php if($testimonial_link) echo '<a class="testimonial-link" href="'.$testimonial_link['url'].'" title="'.$testimonial_link['title'].'" target="'.$testimonial_link['target'].'">'; ?>
      <blockquote class="testimonial">
        <?php
        if($testimonial_image) echo wp_get_attachment_image( $testimonial_image, 'thumbnail', '',  array( "class" => "testimonial-image alignright" ) );
        echo jellypress_content($testimonial['testimonial_text']);
        if($testimonial_citation) echo '<cite>'.$testimonial_citation.'</cite>';
        ?>
      </blockquote>
      <?php if($testimonial_link) echo '</a>'; ?>
      </div>
  </div>
</div>
