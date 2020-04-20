<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package jellypress
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>
<aside id="secondary" class="widget-area col xs-12 md-3">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- /#secondary -->
