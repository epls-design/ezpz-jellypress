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

// Get Params from get_template_part:
$sidebar_id = $args['sidebar_id'];

if ( ! is_active_sidebar( $sidebar_id ) ) {
	return;
}
?>
<aside id="secondary" class="col xs-12 md-3 block sidebar sidebar-<?php echo $sidebar_id;?> widget-area">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside>
