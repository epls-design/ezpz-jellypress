<?php
/**
 * The sidebar containing the main widget area
 * This partial can be removed if sidebars are not used in your theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$sidebar_id = get_query_var('sidebar_id');

if ( ! is_active_sidebar( $sidebar_id ) ) {
	return;
}
?>
<aside id="secondary" class="sidebar sidebar-<?php echo $sidebar_id;?> widget-area col xs-12 md-3">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside>
