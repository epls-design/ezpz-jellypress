<?php
/**
 * Flexible layout: Text and Media block
 * Renders a section to contain Text and a Media element
 * (image, video, iframe, post, map) with the option to reorder columns
 * and change the ratio split of columns
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$section_id = get_query_var('section_id');
$section = get_query_var('section');
$title = $section['title'];
$size = 'large';

$align = $section['vertical_align'];
if ($align == NULL) {
  $align = 'top';
}
$row_class = 'align-'.$align;

$column_split = $section['column_split'];
if ($column_split == 'large-text') {
  $text_class = 'md-8 lg-7';
  $media_class = 'md-4 lg-5';
}
elseif ($column_split == 'large-media') {
  $text_class = 'md-4 lg-5';
  $media_class = 'md-8 lg-7';
}
elseif ($column_split == 'equal' OR $column_split == NULL) {
  $text_class = 'md-6';
  $media_class = 'md-6';
}

// These fields are in a field group so let's put that in a variable...
$media_item = $section['media_item'];
$media_type = $media_item['media_type'];
$media_position = $media_item['media_position'];
$media_post = $media_item['media_post'];
$image = $media_item['image'];
$video = $media_item['video'];
$website_url = $media_item['website_url'];

// We only want the image to appear before the text on smaller devices, everything else needs an explanation before it is seen on screen
if ($media_type == 'image' ){
  $text_class .= ' order-xs-2';
  $media_class .= ' order-xs-1';
}

if ( $media_position == 'left' ) {
  $text_class .= ' order-md-2';
  $media_class .= ' order-md-1';
}
elseif ( $media_position == 'right' ) {
  $text_class .= ' order-md-1';
  $media_class .= ' order-md-2';
}

if ($media_type == 'iframe' || $media_type == 'map'){
  $row_class="equal-height";
  $text_class .= ' align-self-'.$align;
}

?>

<?php if ($title) : ?>
  <header class="row">
    <div class="col">
      <h2 class="section-header"><?php echo jellypress_bracket_tag_replace($title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<div class="row <?php echo $row_class;?>">

  <div class="col sm-12 <?php echo $text_class; ?> flex-column">
    <?php jellypress_content($section['text']); ?>
    <?php jellypress_show_cta_buttons($section['buttons']); ?>
  </div>

  <div class="col sm-12 <?php echo $media_class; ?>">
    <?php if ($media_type == 'image'){
        echo wp_get_attachment_image( $image, $size );
      }
      elseif ($media_type == 'post'){
        global $post; // Call global $post variable
        $post = $media_post[0]; // Set $post global variable to the current post object
        setup_postdata( $post ); // Set up "environment" for template tags
          get_template_part( 'template-components/card' ); // Display the post information
        wp_reset_postdata();
      }
      elseif ($media_type == 'video'){
        echo '<div class="embed-container">'.wp_oembed_get($video).'</div>';
      }
      elseif ($media_type == 'map'){
        if (get_field('google_maps_api_key', 'option') && $locations = $media_item['location']) :
          jellypress_display_map_markers($locations);
        elseif(current_user_can( 'publish_posts' )):
          // Show a warning for the admin to add an API key
          echo '<div class="callout callout__error">' .
          sprintf(
            /* translators: %s link to theme options page. */
            __( 'You need to <a href="%s" class="callout-link">add a Google Maps API key</a> in order to display a map on your website.', 'jellypress' ),
            esc_url( get_admin_url(null, 'admin.php?page=organisation-information' ) )
          )
          . '</div>';        endif; // google_maps_api_key
      }
      elseif ($media_type == 'iframe'){
        echo '<iframe class="embedded-iframe" src="'.$website_url.'"></iframe>';
      }
    ?>
  </div>

</div>
