<?php
/**
 * Flexible layout: Map
 * Renders a section using Google Maps.
 * The editor can add multiple markers and customise
 * the tooltip and marker icon
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$section_id = get_query_var('section_id');
$section = get_query_var('section');
$title = $section['title'];
$width = $section['full_width'];
$preamble = $section['preamble'];
//var_dump($section);
?>

<?php if ($title) : ?>
  <header class="row">
    <div class="col">
      <h2 class="section-header"><?php echo jellypress_bracket_tag_replace($title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<?php if ($preamble) : ?>
  <div class="row preamble">
    <div class="col">
      <?php jellypress_content($preamble); ?>
    </div>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php if ( $width == 1 ){ echo '<div class="vw-100">'; }
    // TODO: Replace all calls to get option with some more efficient way - cache or set constant
        if (get_field('google_maps_api_key', 'option') && ($locations = $section['locations'])) :
          jellypress_display_map_markers($locations);
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
    if ( $width == 1 ){ echo '</div>'; }?>
  </div>
</div>
