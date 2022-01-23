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
defined('ABSPATH') || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
$container_class = 'container';
//var_dump($block);

$block_title = $block['title'];
$block_preamble = $block['preamble'];

$block_width = $block['content_width'];
if ($block_width == 'wide') $container_class .= ' is-wide';
elseif ($block_width == 'full') $block_classes .= ' is-full-width';
?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?= $block_classes; ?>">
  <?php if ($block_title || $block_preamble) echo '<div class="container">'; ?>

  <?php if ($block_title) : $title_align = $block['title_align']; ?>
    <header class="row justify-center block-title">
      <div class="col md-10 lg-8">
        <h2 class="text-<?= $title_align; ?>"><?= jellypress_bracket_tag_replace($block_title); ?></h2>
      </div>
    </header>
  <?php endif; ?>

  <?php if ($block_preamble) : ?>
    <div class="row justify-center block-preamble">
      <div class="col md-10 lg-8">
        <?= jellypress_content($block_preamble); ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($block_title || $block_preamble) echo '</div>'; ?>

  <div class="<?= $container_class; ?>">
    <div class="row">
      <div class="col">
        <?php if ($block_width === 'full') {
          echo '<div class="vw-100">';
        }
        if (get_global_option('google_maps_api_key') && ($map_locations = $block['locations'])) :
          jellypress_display_map_markers($map_locations);
        elseif (current_user_can('publish_posts')) :
          // Show a warning for the admin to add an API key
          echo '<div class="callout error">' .
            sprintf(
              /* translators: %s link to theme options page. */
              __('You need to <a href="%s" class="callout-link">add a Google Maps API key</a> in order to display a map on your website.', 'jellypress'),
              esc_url(get_admin_url(null, 'admin.php?page=apis'))
            )
            . '</div>';
        endif; // google_maps_api_key
        if ($block_width === 'full') {
          echo '</div>';
        } ?>
      </div>
    </div>
  </div>

</section>
