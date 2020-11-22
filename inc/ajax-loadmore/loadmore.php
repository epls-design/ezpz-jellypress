<?php
/**
 * Uses Wordpress AJAX to load more posts to an archive page.
 * Modified from @link https://rudrastyh.com/wordpress/load-more-posts-ajax.html
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if (! function_exists('jellypress_loadmore_script') ) :
  function jellypress_loadmore_script() {
      $theme_version = wp_get_theme()->get('Version'); // Get current version of theme
      $js_version = $theme_version . '.' . filemtime(get_template_directory() . '/dist/js/site.min.js'); // Appends time stamp to help with cache busting
      // register our main scripts but do not enqueue them yet. These scripts will get enqueued by the jellypress_initialize_ajax_posts() function.
      wp_register_script( 'jellypress_loadmore_scroll', get_stylesheet_directory_uri() . '/inc/ajax-loadmore/loadmore-scroll.js', array('jquery'), $js_version );
      wp_register_script( 'jellypress_loadmore_button', get_stylesheet_directory_uri() . '/inc/ajax-loadmore/loadmore-button.js', array('jquery'), $js_version );
    }
    add_action( 'wp_enqueue_scripts', 'jellypress_loadmore_script' );
endif;

if (! function_exists('jellypress_loadmore_ajax_handler') ):
  function jellypress_loadmore_ajax_handler(){
    // prepare our arguments for the query
    $args = json_decode( stripslashes( $_POST['query'] ), true );
    $args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
    $args['post_status'] = 'publish';
    // it is always better to use WP_Query but not here
    query_posts( $args );
      if( have_posts() ) :
        // run the loop
        while( have_posts() ): the_post();
          // Display the template part for the post
          //set_query_var( 'loaded', true );
          echo '<article class="col xs-12 sm-6 md-4 xl-3 loaded" id="post-'.get_the_ID().'">';
            get_template_part( 'template-components/card', get_post_type(), array('loaded' => true) );
          echo '</article>';
        endwhile;
      endif;
    die; // here we exit the script and even no wp_reset_query() required!
  }

  add_action('wp_ajax_loadmore', 'jellypress_loadmore_ajax_handler'); // wp_ajax_{action}
  add_action('wp_ajax_nopriv_loadmore', 'jellypress_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}
endif;

if (! function_exists('jellypress_initialize_ajax_posts') ) :
  /**
   * The function to localize the query parameters and enqueue the correct javascript file
   *
   * @param string $query - The array for the WP query
   * @param string $load_type - Either button or scroll
   * @return void
   */
  function jellypress_initialize_ajax_posts($query = null, $load_type = 'scroll') {

      $script_name = 'jellypress_loadmore_'.$load_type;

      if($query == null) {
        // No array passed, so use the global $wp_query
        global $wp_query;
        $query = $wp_query;
      }
      // now the most interesting part
      // we have to pass parameters to the loadmore.js scripts but we can get the parameters values only in PHP
      // you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
      wp_localize_script( $script_name, 'jellypress_loadmore_params', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ), // WordPress AJAX
        'posts' => json_encode( $query->query_vars ), // everything about your loop is here
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'max_page' => $query->max_num_pages
      ) );
      wp_enqueue_script( $script_name );
  }
endif;
