<?php
/**
 * Flexible layout: Map
 * Renders a block using Google Maps.
 * The editor can add multiple markers and customise
 * the tooltip and marker icon
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$block_id = get_query_var('block_id');
$jellypress_block = get_query_var('jellypress_block');
$block_title = $jellypress_block['title'];
$block_is_fullwidth = $jellypress_block['full_width'];
$block_preamble = $jellypress_block['preamble'];
?>

<?php if ($block_title) : ?>
  <header class="row block-title">
    <div class="col">
      <h2><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<?php if ($block_preamble) : ?>
  <div class="row block-preamble">
    <div class="col">
      <?php jellypress_content($block_preamble); ?>
    </div>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php if ( $block_is_fullwidth == 1 ){ echo '<div class="vw-100">'; }
    // TODO: Replace all calls to get option with some more efficient way - cache or set constant
        if (get_field('google_maps_api_key', 'option') && ($map_locations = $jellypress_block['locations'])) :
          jellypress_display_map_markers($map_locations);
        elseif(current_user_can( 'publish_posts' )):
          // Show a warning for the admin to add an API key
          echo '<div class="callout callout__error">' .
          sprintf(
            /* translators: %s link to theme options page. */
            __( 'You need to <a href="%s" class="callout-link">add a Google Maps API key</a> in order to display a map on your website.', 'jellypress' ),
            esc_url( get_admin_url(null, 'admin.php?page=organisation-information' ) )
          )
          . '</div>';
        endif; // google_maps_api_key
    if ( $block_is_fullwidth == 1 ){ echo '</div>'; }?>
  </div>
</div>
