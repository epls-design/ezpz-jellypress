<?php

/**
 * Flexible layout: Text Columns
 * Renders a block containing between two and four columns of WYSIWIG text
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
?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?= $block_classes; ?>">
  <div class="container">

    <?php if ($block_title) : $title_align = $block['title_align']; ?>
      <header class="row block-title">
        <div class="col">
          <h2 class="text-<?= $title_align; ?>"><?= jellypress_bracket_tag_replace($block_title); ?></h2>
        </div>
      </header>
    <?php endif; ?>

    <?php if ($text_columns = $block['columns']) : ?>
      <div class="row">
        <?php foreach ($text_columns as $text_column) : ?>
          <div class="col xs-12 md-0">
            <?= jellypress_content($text_column['editor']); ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($block['buttons'])) : ?>
      <div class="row">
        <div class="col text-center">
          <?php
          if ($title_align == 'center') jellypress_display_cta_buttons($block['buttons'], 'justify-center');
          elseif ($title_align == 'right') jellypress_display_cta_buttons($block['buttons'], 'justify-end');
          else jellypress_display_cta_buttons($block['buttons']);
          ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
