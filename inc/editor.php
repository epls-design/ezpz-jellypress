<?php
/**
 * Functions which add features to the WYSIWIG editor
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

add_action( 'admin_init', 'jellypress_add_editor_styles' );
if ( ! function_exists( 'jellypress_add_editor_styles' ) ) {
	/**
	 * Registers an editor stylesheet for the theme.
	 */
	function jellypress_add_editor_styles() {
		add_editor_style( 'assets/css/editor-style.min.css' );
	}
}

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
          'title'   => 'Buttons',
          'items' => array(
            array(
              'title' => 'Button',
              'classes' => 'button',
              'block' => 'a',
            ),
            array(
              'title' => 'Secondary Button',
              'classes' => 'button button__secondary',
              'block' => 'a',
            ),
            array(
              'title' => 'Outline Button',
              'classes' => 'button button__outline',
              'block' => 'a',
            ),
          ),
      ),
      array(
        'title'   => 'Text Size',
        'items' => array(
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
          ),
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
  echo '<link rel="stylesheet" href="'.get_stylesheet_directory_uri().'/assets/css/admin-style.min.css" />';
});
