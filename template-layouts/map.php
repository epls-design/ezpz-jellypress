<?php
/**
 * Flexible layout: Map
 * Renders a section using Google Maps.
 * If no location is set with the ACF field,
 * use the value from the theme's options page.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<?php
  $section_id = get_query_var('section_id');

  // TODO: Eventually could look to replace this with a repeater allowing multiple location pins and titles
  $location = get_sub_field( 'location' );
  if ( !$location ) {
    $location = get_field( 'address', 'option' );
  }
  $title = get_sub_field( 'title' );
  $width = get_sub_field( 'full_width' );
  $preamble = get_sub_field('preamble');

?>

<?php if ($title) : ?>
  <header class="row">
    <div class="col">
      <h2 class="section-header"><?php echo jellypress_bracket_tag_replace($title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<?php if ($preamble) : ?>
  <div class="row">
    <div class="col">
      <?php echo $preamble; ?>
    </div>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php if ( $width == 1 ){ echo '<div class="vw-100">'; }
        if (get_field('google_maps_api_key', 'option')) : ?>
          <div class="load-map">
            <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
          </div>
        <?php elseif(current_user_can( 'publish_posts' )):
        // Show a warning for the admin to add an API key
          _e('<div class="callout callout__error">You need to <a href="'.get_admin_url(null, 'admin.php?page=organisation-information').'" class="callout-link">add a Google Maps API key</a> in order to display a map on your website.</div>','jellypress');
        endif; // google_maps_api_key
    if ( $width == 1 ){ echo '</div>'; }?>
  </div>
</div>
