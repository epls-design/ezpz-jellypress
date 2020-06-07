<?php
/**
 * The sidebar containing the main widget area
 * This partial can be removed if sidebars are not used in your theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package jellypress
 */

if ( ! is_active_sidebar( 'default-sidebar' ) ) {
	return;
}
?>
<aside id="secondary" class="section widget-area col xs-12 md-3">
	<?php dynamic_sidebar( 'default-sidebar' ); ?>
</aside><!-- /#secondary -->
