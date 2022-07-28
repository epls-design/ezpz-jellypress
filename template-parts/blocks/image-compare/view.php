<?php

/**
 * Flexible layout: Image Compare
 * A block that allows the user to compare two images eg. before/after.
 * Users TwentyTwenty by Zurb @link https://www.npmjs.com/package/zurb-twentytwenty
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

$compare_id = 'image-compare-' . $block_id;

$block_width = $block['content_width'];
if ($block_width == 'wide') $container_class .= ' is-wide';
elseif ($block_width == 'full') $block_classes .= ' is-full-width';

if ($block['before_label']) $before_label = $block['before_label'];
else $before_label = __('Before', 'jellypress');

if ($block['after_label']) $after_label = $block['after_label'];
else $after_label = __('After', 'jellypress');

?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?= $block_classes; ?>">
  <div class="container">

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

  </div>
  <div class="<?= $container_class; ?>">

    <div class="row">
      <div class="col">
        <?php
          if ($block_width === 'full') echo '<div class="vw-100">';
          elseif ($block_width === 'smaller') echo '<div class="row justify-center"><div class="col md-10 lg-8">';
        ?>
        <div id="<?= $compare_id; ?>" class="twentytwenty-container<?php if ($block['handle_color']) echo ' has-dark-handle'; ?>">
          <?php
          echo wp_get_attachment_image($block['image_one'], 'large');
          echo wp_get_attachment_image($block['image_two'], 'large');
          ?>
        </div>
        <?php
          if ($block_width === 'full') echo '</div>';
          elseif ($block_width === 'smaller') echo '</div></div>';
        ?>
      </div>
    </div>

  </div>
</section>

<?php
add_action(
  'wp_footer',
  jellypress_compare_init($compare_id, $block['orientation'], $before_label, $after_label),
  30
); // 30 priority ensures it is placed below the enqueued scripts (priority 20)
?>
