<?php

/**
 * Flexible layout: Video block
 * Renders a video block
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

$block_width = $block['content_width'];
if ($block_width == 'wide') $container_class .= ' is-wide';
elseif ($block_width == 'full') $block_classes .= ' is-full-width';
?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?php echo $block_classes; ?>">

  <?php if ($block_title) : $title_align = $block['title_align'];
    if ($title_align == 'left') $justify = 'start';
    elseif ($title_align == 'right') $justify = 'end';
    else $justify = 'center';
  ?>
    <div class="container">
      <header class="row justify-<?= $justify; ?> block-title">
        <div class="col md-10">
          <h2 class="text-<?php echo $title_align; ?>"><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
        </div>
      </header>
    </div>
  <?php endif; ?>

  <figure>
    <div class="<?php echo $container_class; ?>">
      <?php
      if ($block_width === 'full') echo '<div class="vw-100">';
      jellypress_embed_video($block['video'], $block['aspect_ratio']);
      if ($block_width === 'full') echo '</div>'; ?>
    </div>

    <?php if ($block['caption']) :
      if (!$block_title) {
        $justify = 'start';
        $title_align = 'left';
      }
    ?>
      <figcaption class="caption container">
        <div class="row justify-<?= $justify; ?>">
          <div class="col md-10 lg-8 text-<?= $title_align; ?>">
            <?= jellypress_content($block['caption']); ?>
          </div>
        </div>
      </figcaption>
    <?php endif; ?>
  </figure>

</section>
