<?php
/**
 * Flexible layout: Text and Media block
 * Renders a block to contain Text and a Media element
 * (image, video, iframe, post, map) with the option to reorder columns
 * and change the ratio split of columns
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
//var_dump($block);

$block_title = $block['title'];
$image_size = 'medium';

$block_valign = $block['vertical_align'];
if ($block_valign == NULL) {
  $block_valign = 'top';
}
$row_class = 'align-'.$block_valign;

$block_column_split = $block['column_split'];
if ($block_column_split == 'large-text') {
  $text_class = 'md-8 lg-7';
  $media_class = 'md-4 lg-5';
}
elseif ($block_column_split == 'large-media') {
  $text_class = 'md-4 lg-5';
  $media_class = 'md-8 lg-7';
}
elseif ($block_column_split == 'equal' OR $block_column_split == NULL) {
  $text_class = 'md-6';
  $media_class = 'md-6';
}

// These fields are in a field group so let's put that in a variable...
$media_item = $block['media_item'];
$media_type = $media_item['media_type'];
$media_position = $media_item['media_position'];
$media_post = $media_item['media_post'];
$image_id = $media_item['image'];
$video_url = $media_item['video'];
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
  $text_class .= ' align-self-'.$block_valign;
}

?>

<?php if ($block_title) : ?>
  <header class="row block-title">
    <div class="col">
      <h2><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<div class="row <?php echo $row_class;?>">

  <div class="col sm-12 <?php echo $text_class; ?> flex-column">
    <?php jellypress_content($block['text']); ?>
    <?php jellypress_display_cta_buttons($block['buttons']); ?>
  </div>

  <div class="col sm-12 <?php echo $media_class; ?>">
    <?php if ($media_type == 'image'){
        echo wp_get_attachment_image( $image_id, $image_size );
      }
      elseif ($media_type == 'post'){
        global $post; // Call global $post variable
        $post = $media_post[0]; // Set $post global variable to the current post object
        setup_postdata( $post ); // Set up "environment" for template tags
          get_template_part( 'template-parts/components/card/card' ); // Display the post information
        wp_reset_postdata();
      }
      elseif ($media_type == 'video'){
        jellypress_embed_video($video_url);
      }
      elseif ($media_type == 'map'){
        if (get_field('google_maps_api_key', 'option') && $map_locations = $media_item['location']) :
          jellypress_display_map_markers($map_locations);
        elseif(current_user_can( 'publish_posts' )):
          // Show a warning for the admin to add an API key
          echo '<div class="callout error">' .
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
