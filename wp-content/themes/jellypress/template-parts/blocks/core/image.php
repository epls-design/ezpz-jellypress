<?php

// TODO: ADD IN OPTION TO MAKE IT w-100, this to be enabled by default
// TODO: If it's a zoom, maybe it should be a fixed aspect ratio?

/**
 * Rewrites the output of core/image on the front end. This is because we are using a custom
 * lightbox plugin, and want to override Wordpress' default lightbox functionality.
 *
 * @param array $args['block'] The block settings and attributes.
 * @param string $args['block_content'] The block content.
 * @param string $args['provider'] The video platform
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Extract block attributes from the arguments.
$atts = $args['block']['attrs'];

// Check if lightbox is enabled and link destination is set to 'none'. If so, enable zoom.
if (isset($atts['lightbox']['enabled']) && $atts['lightbox']['enabled'] && $atts['linkDestination'] == 'none') {
  $zoom = true;
  // Get the full-size image URL.
  $image_size_full = wp_get_attachment_image_src($atts['id'], 'full');
  // Enqueue the Photoswipe JavaScript library.
  wp_enqueue_script('photoswipe-init');
} else {
  $zoom = false;
}

// Get the original block content.
$output = $args['block_content'];

/**
 * Create a WP_HTML_Tag_Processor instance for manipulating HTML tags.
 * @see https://wpdevelopment.courses/articles/wp-html-tag-processor/
 * @see https://developer.wordpress.org/reference/classes/wp_html_tag_processor/
 */
$markup = new WP_HTML_Tag_Processor($output);

// Modify the <figure> tag by removing certain data attributes that WP adds (we are using our own Lightbox script)
$markup->next_tag(array('tag_name' => 'figure'));
$markup->remove_attribute('data-wp-context');
$markup->remove_attribute('data-wp-interactive');

// If zoom is enabled, add class to figure, and remove unnecessary class.
if ($zoom) {
  $markup->add_class('lightbox-image');
  $markup->remove_class("wp-lightbox-container");
}

// Remove data attributes from <img> tags that Wordpress has added.
$markup->next_tag(array('tag_name' => 'img'));
$markup->remove_attribute('data-wp-effect--setstylesonresize');
$markup->remove_attribute('data-wp-effect');
$markup->remove_attribute('data-wp-init');
$markup->remove_attribute('data-wp-on--click');
$markup->remove_attribute('data-wp-on--load');

// If zoom is enabled, add class 'gallery-image' and 'w-100'.
if ($zoom)
  $markup->add_class('gallery-image w-100');

// Get the updated HTML content.
$body_content = $markup->get_updated_html();

// Convert $body_content to an HTML DOM so we can manipulate it.
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($body_content);
$xpath = new DOMXPath($dom);

// Remove all <button> elements from the DOM -> this was added by WP for the lightbox.
$buttons = $xpath->query('//button');
foreach ($buttons as $button) {
  $button->parentNode->removeChild($button);
}

// Remove all <div> elements from the DOM -> these were added by WP for the lightbox.
$divs = $xpath->query('//div');
foreach ($divs as $div) {
  $div->parentNode->removeChild($div);
}

// If zoom is enabled, modify <figure> and <img> tags for lightbox functionality.
if ($zoom) {
  $figures = $xpath->query('//figure');
  foreach ($figures as $figure) {
    // Create a new <div> with class .gallery.
    $galleryDiv = $dom->createElement('div');
    $galleryDiv->setAttribute('class', 'gallery');
    $galleryDiv->setAttribute('id', 'gallery-' . $atts['id']);

    // Clone the existing <figure> element.
    $clonedFigure = $figure->cloneNode(true);

    // Append the cloned <figure> to the new <div>.
    $galleryDiv->appendChild($clonedFigure);

    // Replace the <figure> with the new <div> in the DOM structure.
    $figure->parentNode->replaceChild($galleryDiv, $figure);
  }

  $images = $xpath->query('//img');
  foreach ($images as $image) {
    // Create a new <a> tag.
    $anchor = $dom->createElement('a');
    $anchor->setAttribute('href', $image_size_full[0]);
    $anchor->setAttribute('data-pswp-height', $image_size_full[2]);
    $anchor->setAttribute('data-pswp-width', $image_size_full[1]);
    $anchor->setAttribute('target', '_blank');

    // Clone the <img> tag.
    $clonedImage = $image->cloneNode(true);

    // Append the cloned <img> to the <a> tag.
    $anchor->appendChild($clonedImage);

    // Replace the <img> tag with the <a> tag in the DOM structure.
    $image->parentNode->replaceChild($anchor, $image);
  }
}

// Get the cleaned HTML body content.
$body_content = '';
foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $node) {
  $body_content .= $dom->saveHTML($node);
}

// Output the modified HTML content to the front end.
echo $body_content;
