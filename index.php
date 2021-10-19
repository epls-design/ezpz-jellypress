<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php or page.php file exists.
 *
 * Note: For the majority of projects, more specific templates will be in use,
 * so it is unlikely this template will be called or need to be edited
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( (is_front_page() && is_home()) || is_archive() ) {
  get_template_part( 'archive');
}
elseif ( is_front_page() ){
  get_template_part( 'page');
}
elseif ( is_singular() ){
  get_template_part( 'single');
}
elseif ( is_search() ) {
  get_template_part( 'search');
}
else {
  get_template_part( '404');
}
