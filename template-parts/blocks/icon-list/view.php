<?php

/**
 * Flexible layout: Icon List
 * Renders a list of icons, styled with Font Awesome
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

$icons = $block['icon'];

/**
 * TODO:
 * - Add in Block Title and Preamble (clone)
 */

?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?php echo $block_classes; ?>">
  <div class="container">

    <div class="row">
      <div class="col">
        <?php
        $icon_size = 'icon-list-size-' . $block['icon_size'];
        echo '<ul class="fa-ul icon-list-section-ul ' . $icon_size . '">';
        foreach ($icons as $icon) {
          echo '<li><i style="color: ' . $block['colour'] . ' " class="fa-li ' . $icon['icon-selector'] . '"></i>' . $icon['text'] . '</li>';
        }
        echo '</ul>';
        ?>
      </div>
    </div>

  </div>
</section>
