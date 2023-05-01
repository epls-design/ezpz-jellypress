<?php

/**
 * Flexible layout: Unfiltered HTML
 * Renders a block that allows the user to paste in unfiltered HTML.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$block_title = $block['title'];
$title_align = $block_title ? $block['title_align'] : 'left';
$block_preamble = $block['preamble'];
?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?php echo $block_classes; ?>">
  <div class="container">

    <?php if ($block_title) : ?>
      <header class="row justify-center block-title">
        <div class="col md-10 lg-8">
          <h2 class="text-<?php echo $title_align; ?>">
            <?php echo $block_title; ?>
          </h2>
        </div>
      </header>
    <?php endif; ?>

    <?php if ($block_preamble) : ?>
      <div class="row justify-center block-preamble">
        <div class="col md-10 lg-8">
          <?php echo jellypress_content($block_preamble); ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="row justify-center">
      <div class="col md-10 lg-8">
        <?php echo $block['unfiltered_html']; ?>
      </div>
    </div>

  </div>
</section>