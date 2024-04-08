<?php

/**
 * Template part for displaying a testimonial slide
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$testimonial = $args['testimonial'];
?>

<div class="swiper-slide">
  <?php
  if ($testimonial_link = $testimonial['testimonial_link']) echo '<a class="testimonial-link" href="' . $testimonial_link['url'] . '" title="' . $testimonial_link['title'] . '" target="' . $testimonial_link['target'] . '">';
  echo '<blockquote class="testimonial">';
  if ($testimonial_image = $testimonial['testimonial_image']) echo wp_get_attachment_image($testimonial_image, 'thumbnail', '',  array("class" => "testimonial-image alignright"));
  echo strip_tags($testimonial['testimonial_text'], array('p', 'br'));
  if ($testimonial_citation = $testimonial['testimonial_citation']) echo '<cite>' . $testimonial_citation . '</cite>';
  echo '</blockquote>';
  if ($testimonial_link) echo '</a>';
  ?>
</div>