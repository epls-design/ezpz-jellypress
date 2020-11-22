<?php
/**
 * Flexible layout: Unfiltered HTML
 * Renders a block that allows the user to paste in unfiltered HTML.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];

$block_title = $block['title'];
$block_preamble = $block['preamble'];
?>

<?php if ($block_title) : ?>
  <header class="row block-title">
    <div class="col">
      <h2><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<?php if ($block_preamble) : ?>
  <div class="block-preamble">
      <?php jellypress_content($block_preamble); ?>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php echo $block['unfiltered_html']; ?>
  </div>
</div>
