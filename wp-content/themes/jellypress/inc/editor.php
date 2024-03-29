<?php

/**
 * Functions which add features to the WYSIWIG editor
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Add custom styles to the WYSIWIG
 * @link https://www.wpbeginner.com/wp-tutorials/how-to-add-custom-styles-to-wordpress-visual-editor/
 * @link https://shellcreeper.com/complete-guide-to-style-format-drop-down-in-wp-editor/
 */

// Callback function to insert 'styleselect' into the $buttons array
add_filter('mce_buttons', 'jellypress_additional_styles');
function jellypress_additional_styles($buttons) {
  array_unshift($buttons, 'styleselect');
  return $buttons;
}

/**
 *  Remove the h1 tag from the WordPress editor.
 *
 *  @param   array  $settings  The array of editor settings
 *  @return  array             The modified edit settings
 */
add_filter('tiny_mce_before_init', 'jellypress_remove_h1_tinymce');
function jellypress_remove_h1_tinymce($settings) {
  $settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;Code=code';
  return $settings;
}

// Callback function to filter the MCE settings
add_filter('tiny_mce_before_init', 'jellypress_mce_before_init_insert_formats');
function jellypress_mce_before_init_insert_formats($init_array) {
  // Define the style_formats array
  $style_formats = array(

    /**
     * Each array child is a format with it's own settings
     * Notice that each array has title, block, classes, and wrapper arguments
     * Title is the label which will be visible in Formats menu
     * Block defines whether it is a span, div, selector, or inline style
     * Classes allows you to define CSS classes
     * Wrapper whether or not to add a new block-level element around any selected elements
     * inline – Name of the inline element to produce for example “span”. The current text selection will be wrapped in this inline element.
     * block – Name of the block element to produce for example “h1″. Existing block elements within the selection gets replaced with the new block element.
     * selector – CSS 3 selector pattern to find elements within the selection by. This can be used to apply classes to specific elements or complex things like odd rows in a table. Note that if you combine both selector and block then you can get more nuanced behavior where the button changes the class of the selected tag by default, but adds the block tag around the cursor if the selected tag isn't found.
     * classes – Space separated list of classes to apply to the selected elements or the new inline/block element.
     * styles – Name/value object with CSS style items to apply such as color etc.
     * attributes – Name/value object with attributes to apply to the selected elements or the new inline/block element.
     * exact – Disables the merge similar styles feature when used. This is needed for some CSS inheritance issues such as text-decoration for underline/strikethrough.
     * wrapper – State that tells that the current format is a container format for block elements. For example a div wrapper or blockquote.
     */
    array(
      'title' => 'Standfirst',
      'classes' => 'standfirst',
      'block' => 'p',
    ),
    array(
      'title' => 'Small',
      'classes' => 'small',
      'block' => 'p',
    ),
    array(
      'title' => 'TO-DO Note',
      'classes' => 'to-do',
      'inline' => 'span',
    )
  );
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init_array['style_formats'] = json_encode($style_formats);
  return $init_array;
}

/**
 * Hooks into TinyMCE to remove unwanted HTML tags and formatting from pasted text
 * @link https://jonathannicol.com/blog/2015/02/19/clean-pasted-text-in-wordpress/
 * @link see also https://sundari-webdesign.com/wordpress-removing-classes-styles-and-tag-attributes-from-pasted-content/
 */
add_filter('tiny_mce_before_init', 'jellypress_tinymce_cleanup');
function jellypress_tinymce_cleanup($in) {
  $in['paste_preprocess'] = "function(plugin, args){
      // Strip all HTML tags except those we have whitelisted
      var whitelist = 'p,a,span,b,strong,i,em,br,h2,h3,h4,h5,h6,ul,li,ol,table,tr,td,th,tbody,thead,img,iframe,embed,code,blockquote,cite';
      var stripped = jQuery('<div>' + args.content + '</div>');
      var els = stripped.find('*').not(whitelist);
      for (var i = els.length - 1; i >= 0; i--) {
        var e = els[i];
        jQuery(e).replaceWith(e.innerHTML);
      }
      // Strip all class and id attributes
      stripped.find('*').removeAttr('id').removeAttr('class');
      // Return the clean HTML
      args.content = stripped.html();
    }";
  return $in;
}

/**
 * Applies a filter to the_content and the_excerpt to automatically add rel="external" to outbound links
 * @link https://crunchify.com/how-to-add-relsponsored-or-relnofollow-to-all-external-links-in-wordpress/
 *
 * @param string $content
 * @return string Filtered Text
 */
add_filter('the_content', 'jellypress_filter_external_links_rel');
add_filter('the_excerpt', 'jellypress_filter_external_links_rel');
function jellypress_filter_external_links_rel($content) {
  return preg_replace_callback('/<a[^>]+/', 'jellypress_add_rel_external_to_outbound_links', $content);
}
function jellypress_add_rel_external_to_outbound_links($matches) {
  $link = $matches[0];
  $site_link = get_bloginfo('url');
  if (strpos($link, 'rel') === false) {
    // If the link doesn't have a rel, and it is not an internal link, add rel="external"
    $link = preg_replace("%(href=\S(?!$site_link))%i", 'rel="external" $1', $link);
  }
  // Commented out - respect any manual rel that has been added by the author
  //elseif (preg_match("%href=\S(?!$site_link)%i", $link)) {
  //    $link = preg_replace('/rel=\S(?!external)\S*/i', 'rel="external"', $link);
  //}
  return $link;
}

add_action('enqueue_block_editor_assets', 'jellypress_block_editor_scripts');
function jellypress_block_editor_scripts() {
  wp_enqueue_script(
    'jellypress-gutenberg-filters',
    get_template_directory_uri() . '/dist/editor-block-filters.js',
    array('react', 'react-dom', 'wp-data', 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-hooks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'lodash'),
    filemtime(get_template_directory() . '/dist/editor-block-filters.js'),
  );
}
