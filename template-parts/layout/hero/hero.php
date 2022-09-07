<?php

/**
 * Template part for displaying hero content when no other more specific
 * partial exists eg on single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get all ACF field meta into a single array
$field_group_array = json_decode(file_get_contents(get_stylesheet_directory() . "/assets/acf-json/group_5ff5b93d03614.json"), true);
$hero_data = get_all_custom_field_meta($id, $field_group_array);
//var_dump($hero_data);

$hero_background = $hero_data['background_type'];
$hero_subhead = $hero_data['subhead'];

$hero_class = '';
$hero_main_class = 'hero-main';

if ($hero_height = $hero_data['hero_height']) $hero_class .= ' hero-' . $hero_height;
else $hero_class .= ' hero-small';

if ($hero_background == 'image' || $hero_background == 'video') $hero_main_class .= ' bg-' . $hero_background;

if ($hero_background == 'image') {
  $background_image = $hero_data['hero_image'];
} elseif ($hero_background == 'color') {
  $bg_color = $hero_data['background_color'];
  $hero_main_class .= ' bg-' . $bg_color;
} elseif (!$hero_background) {
  $hero_main_class .= ' bg-neutral-900';
}
$title_image = $hero_data['title_image'];

?>
<header class="block hero hero-<?php echo get_post_type() . $hero_class; ?>">
  <?php
  echo '<div class="' . $hero_main_class . '" style="background-image:url(' . wp_get_attachment_image_url($background_image, 'hero') . ')">';
  ?>

  <?php if ($hero_background == 'video') :
    $video_array = $hero_data['background_video'];
    $poster = $video_array['video_poster'];
  ?>
    <video playsinline autoplay muted loop poster="<?php echo wp_get_attachment_image_url($poster, 'large'); ?>" class="hero-video">
      <?php
      foreach ($video_array['video_sources'] as $video_source) :
        $video_file_id = $video_source['video_source'];
        $video_file_url = wp_get_attachment_url($video_file_id);
        $video_ext = pathinfo($video_file_url, PATHINFO_EXTENSION);
        echo '<source src="' . $video_file_url . '" type="video/' . $video_ext . '">';
      endforeach;
      ?>
    </video>
  <?php endif; ?>

  <div class="container">

    <?php
    $header_col_class = 'col md-10';
    $header_row_class = 'row justify-center';

    // To make the page title either white or black
    $page_title = 'page-title';
    $title_colour = $hero_data['hero_title_colour'];

    if ($title_colour == '1') {
      $page_title = $page_title . ' page-title-light';
    }

    ?>

    <div class="<?php echo $header_row_class; ?>">

      <div class="<?php echo $header_col_class; ?>">
        <?php
        echo '<h1 class="' . $page_title . '">';
        if ($title_image) :
          echo wp_get_attachment_image($title_image, 'large');
          the_title('<span class="text-hide">', '</span>');
        else :
          the_title();
        endif;
        echo '</h1>';
        if ($hero_subhead) :
          echo '<p class="hero-subhead text-center">' . jellypress_bracket_tag_replace($hero_subhead) . '</p>';
        endif;
        if (get_post_type() === 'post') {
          echo '<div class="text-center">';
          jellypress_posted_on();
          echo '</div>';
        }

        if ($external_video_link = $video_array['external_video_link']) { ?>
          <div class="text-center">
            <a class="button play-button white outline" id="hero-modal-open" href="#modal-video-hero"><?php echo __('Play Video', 'jellypress'); ?></a>
            <div id="modal-video-hero" class="modal modal-video is-transparent p-0 mfp-hide">
              <?php jellypress_embed_video($external_video_link, $video_array['aspect_ratio']); ?>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="hero-button-container">
      <?php jellypress_display_cta_buttons($hero_data['buttons'], 'justify-center'); ?>
    </div>
  </div>
  </div>
</header>


<?php
if ($external_video_link) :
  // Initialize modals
  $func = jellypress_modal_init('#hero-modal-open', null, false, 'inline', 'false', 'video');
  add_action('wp_footer', $func, 30); // 30 priority ensures it is placed below the enqueued scripts (priority 20)
endif;
?>
