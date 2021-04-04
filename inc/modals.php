<?php
/**
 * Functions which initialize and manipulate modals.
 * Uses Magnific Popup library for the modal functionality.
 * @link https://dimsemenov.com/plugins/magnific-popup/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'jellypress_modal_init' ) ) :
  /**
   * Function used to initialize MagnificPop on a selector
   * @link https://dimsemenov.com/plugins/magnific-popup/documentation.html#content-types
   *
   * @param [type] $modal_selector -> selector eg #element_id or .element_id used to initiate MagnificPopup
   * @param [type] $delegate -> string used to determine the delegate eg. 'a'
   * @param string $modal_type -> Opts 'image', 'iframe', 'ajax', 'inline'
   * @param string $is_gallery -> Whether the modal is part of a group. The name implies images but it can be used with any content type
   * @param string $close_button_inside -> Whether the close button should be inside the modal or outside.
   * @return void
   */
  function jellypress_modal_init($modal_selector, $delegate = null, $is_gallery = true, $modal_type = 'image', $close_button_inside = 'false') {

    wp_enqueue_script('magnific-popup');

    // Code to output in the DOM
    $output =
    '<script type="text/javascript">
      (function($) {
        $("'.$modal_selector.'").magnificPopup({
          type: "'.$modal_type.'",';
    if($delegate) $output.= '
          delegate: "'.$delegate.'", ';
    if($is_gallery) $output.= '
          gallery:{
            enabled: true,
            preload: [2,2]
          },';
    $output.='
          closeBtnInside: '.$close_button_inside.',
          removalDelay: 400,
          mainClass: "modal-fade"
        });
      })( jQuery );
  </script>
    ';
    $output = str_replace(array("\r", "\n","  "), '', $output)."\n";
    $func = function() use($output) { print $output; };
    return $func;
  }
endif;
