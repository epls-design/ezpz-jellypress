<?php

/**
 * Flexible layout: Time Line
 * Renders a time line
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get Params from get_template_part:

$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
$time_lines = $block['time_lines'];

$block_title = $block['title'];
$block_preamble = $block['preamble'];

?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?php echo $block_classes; ?>">
  <div class="container">

    <?php if ($block_title) : $title_align = $block['title_align']; ?>
      <header class="row justify-center block-title">
        <div class="col md-10 lg-8">
          <h2 class="text-<?php echo $title_align; ?>"><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
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

    <div class="row">
      <div class="col">
        <div class="timeline">
          <?php
          $i = 0;

          foreach ($time_lines as $time_line) :

            $time_params = array(
              'block_id' => $block_id,
              'count' => $i,
              'date' => $time_line['date'],
              'main_title' => $time_line['main_title'],
              'time_title' => $time_line['time_title'],
              'time_text' => $time_line['time_text'],
            );
            get_template_part('template-parts/components/time-line/time', 'basic', $time_params);
            $i++;
          endforeach;
          ?>
        </div>
      </div>
    </div>

  </div>
</section>
