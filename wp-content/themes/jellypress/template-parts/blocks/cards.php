<?php

/**
 * Flexible layout: Cards
 * Allows the editor to add a number of promotional cards to a page.
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

$allow_multiple = isset($block['allow_multiple']) ? $block['allow_multiple'] : null;

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

    <?php if ($cards = $block['cards']) : ?>
      <div class="row justify-center cards equal-height">
        <?php foreach ($cards as $card) {
          echo '<div class="col xs-6 md-4 lg-3">';
          $card_params = array(
            'card' => $card
          );
          get_template_part('template-parts/partials/card', 'basic', $card_params);
          echo '</div>';
        }
        ?>
      </div>
    <?php endif; ?>

  </div>
</section>