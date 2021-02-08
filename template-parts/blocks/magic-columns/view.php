<?php
/**
 * Flexible layout: Magic Columns
 * A super flexible layout component that alows the user to add columns,
 * which can contain text or media items. The user can also define the column
 * width at different breakpoints. This is a fairly advanced layout block for front-end
 * content creation.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$block_title = $block['title'];

$row_class = 'align-'.$block['row_vertical_align'];

?>

<section <?php if($block_id_opt = $block['section_id']) echo 'id="'.strtolower($block_id_opt).'"'; ?> class="<?php echo $block_classes;?>">
  <div class="container">

    <?php if ($block_title) : $title_align = $block['title_align'];
      $header_row_class = 'row block-title';
      if($title_align == 'center') $header_row_class .= ' justify-center';
      elseif($title_align == 'right') $header_row_class .= ' justify-end';
    ?>
      <header class="<?php echo $header_row_class;?>">
        <div class="col md-10 lg-8">
          <h2 class="text-<?php echo $title_align;?>"><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
        </div>
      </header>
    <?php endif; ?>

    <div class="row <?php echo $row_class;?>">
      <?php foreach($block['columns'] as $column):
        //var_dump($column);
        $col_class = 'col';
        $col_class .= ' '.$column['width_xs'].' '.$column['width_sm'].' '.$column['width_md'].' '.$column['width_lg'];
        $column_type = $column['column_type'];
        $col_class .= ' column-'.$column_type;
        ?>
        <div class="<?php echo $col_class;?>">
          <?php
          if ($column_type == 'text'){
            jellypress_content($column['text']);
            if($column['buttons']) jellypress_display_cta_buttons($column['buttons']);
          }
          elseif ($column_type == 'image'){
            echo '<figure>';
            if($image_link = $column['image_link']) echo '<a href="'.$image_link['url'].'" title="'.$image_link['title'].'" target="'.$image_link['target'].'">';
            echo wp_get_attachment_image( $column['image'], 'medium' );
            if($image_link) echo '</a>';
            if($column_caption = $column['column_caption']) {
              echo '<figcaption class="image-caption">';
                jellypress_content($column_caption);
              echo '</figcaption>';
            }
            echo '</figure>';
          }
          elseif ($column_type == 'post'){
            global $post; // Call global $post variable
            $post = $column['post'][0]; // Set $post global variable to the current post object
            setup_postdata( $post ); // Set up "environment" for template tags
              get_template_part( 'template-parts/components/card/card' ); // Display the post information
            wp_reset_postdata();
          }
          elseif ($column_type == 'video'){
            jellypress_embed_video($column['video'], $column['aspect_ratio']);
            if($column_caption = $column['column_caption']) {
              echo '<div class="video-caption">';
                jellypress_content($column_caption);
              echo '</div>';
            }
          }
          elseif ($column_type == 'iframe'){
            echo '<iframe class="embedded-iframe" src="'.$column['website_url'].'"></iframe>';
          }
          elseif ($column_type == 'html'){
            echo $column['unfiltered_html'];
          }
          elseif ($column_type == 'map'){
            if (get_global_option('google_maps_api_key') && $map_locations = $column['location']) :
              jellypress_display_map_markers($map_locations);
            elseif(current_user_can( 'publish_posts' )):
              // Show a warning for the admin to add an API key
              echo '<div class="callout error">' .
              sprintf(
                /* translators: %s link to theme options page. */
                __( 'You need to <a href="%s" class="callout-link">add a Google Maps API key</a> in order to display a map on your website.', 'jellypress' ),
                esc_url( get_admin_url(null, 'admin.php?page=organisation-information' ) )
              )
              . '</div>';
            endif; // google_maps_api_key
          }
          ?>
        </div>
      <?php endforeach;?>
    </div>

  </div>
</section>
