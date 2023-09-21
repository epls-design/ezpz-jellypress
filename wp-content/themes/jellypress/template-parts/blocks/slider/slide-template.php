<?php

/**
 * Template part for displaying a simple slide
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$slide = $args['slide'];
$slide_class = $args['slide_class'];
$block_bg_color = isset($args['block_bg_color']) ? $args['block_bg_color'] : null;

// Determine what button color to use
switch ($block_bg_color) {
    //case 'white':
    //  $button_color = ' primary';
    //  break;
  default:
    $button_color = '';
}

$slide_link = $slide['slide_link'];

?>

<div class="<?php echo $slide_class; ?>">
  <div class="row <?php echo $args['row_align']; ?> justify-between">
    <div class="col sm-6 md-4 lg-6 slide-media">
      <?php
      if ($slide_link) echo '<a href="' . $slide_link['url'] . '" title="' . $slide_link['title'] . '" target="' . $slide_link['target'] . '">';
      echo wp_get_attachment_image($slide['slide_image'], 'medium', '',  array("class" => "slide-image"));
      if ($slide_link) echo '</a>';
      ?>
    </div>
    <div class="col sm-12 md-8 lg-5 slide-text <?php echo $args['text_align']; ?>">
      <?php
      if ($slide_title = $slide['slide_title']) echo '<h3>' . $slide_title . '</h3>';
      echo $slide['slide_text'];
      if ($slide_link) echo '<a class="button' . $button_color . '" href="' . $slide_link['url'] . '" title="' . $slide_link['title'] . '" target="' . $slide_link['target'] . '">' . $slide_link['title'] . '</a>';
      ?>
    </div>
  </div>
</div>