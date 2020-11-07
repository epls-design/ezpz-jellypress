<?php
/**
 * Functions which add features to the WYSIWIG editor
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', 'jellypress_add_editor_styles' );
if ( ! function_exists( 'jellypress_add_editor_styles' ) ) :
	/**
	 * Registers an editor stylesheet for the theme.
	 */
	function jellypress_add_editor_styles() {
		add_editor_style( 'dist/css/editor-style.min.css' );
	}
endif;

 /**
  * Add custom styles to the WYSIWIG
  * @link https://www.wpbeginner.com/wp-tutorials/how-to-add-custom-styles-to-wordpress-visual-editor/
  * @link https://shellcreeper.com/complete-guide-to-style-format-drop-down-in-wp-editor/
  */

 // Callback function to insert 'styleselect' into the $buttons array
if ( !function_exists( 'jellypress_additional_styles' )) {
  function jellypress_additional_styles( $buttons ) {
      array_unshift( $buttons, 'styleselect' );
      return $buttons;
  }
  // Register our callback to the appropriate filter
  add_filter( 'mce_buttons', 'jellypress_additional_styles' );
}

/**
 *  Remove the h1 tag from the WordPress editor.
 *
 *  @param   array  $settings  The array of editor settings
 *  @return  array             The modified edit settings
 */
function jellypress_remove_h1_tinymce( $settings ) {
  $settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;Code=code';
  return $settings;
}
add_filter( 'tiny_mce_before_init', 'jellypress_remove_h1_tinymce' );

// Callback function to filter the MCE settings
if ( !function_exists( 'jellypress_mce_before_init_insert_formats' )) {

  function jellypress_mce_before_init_insert_formats( $init_array ) {
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
    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = json_encode( $style_formats );
    return $init_array;
    }
    // Attach callback to 'tiny_mce_before_init'
    add_filter( 'tiny_mce_before_init', 'jellypress_mce_before_init_insert_formats' );
}

/**
 * Loads a stylesheet to define styles for the admin area
 */
add_action('admin_head', function() {
  echo '<link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/dist/css/admin-style.min.css" />';
});

if ( ! function_exists( 'jellypress_tinymce_cleanup' ) ) {
  /**
   * Hooks into TinyMCE to remove unwanted HTML tags and formatting from pasted text
   * @link https://jonathannicol.com/blog/2015/02/19/clean-pasted-text-in-wordpress/
   * @link see also https://sundari-webdesign.com/wordpress-removing-classes-styles-and-tag-attributes-from-pasted-content/
   */
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
}
add_filter('tiny_mce_before_init', 'jellypress_tinymce_cleanup');

/**
 * Allow SVG uploads to the media library
 */
add_filter( 'upload_mimes', 'jellypress_allow_svg_upload' );
function jellypress_allow_svg_upload($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_action( 'admin_head', 'jellypress_svg_admin_style' );
function jellypress_svg_admin_style() {
	$css = '';
	$css = 'td.media-icon img[src$=".svg"] { width: 100% !important; height: auto !important; }';
	echo '<style type="text/css">'.$css.'</style>';
}
