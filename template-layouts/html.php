<?php
/**
 * Flexible layout: Unfiltered HTML
 * Renders a block that allows the user to paste in unfiltered HTML.
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$block_id = get_query_var('block_id');
$jellypress_block = get_query_var('jellypress_block');
$block_title = $jellypress_block['title'];
$block_preamble = $jellypress_block['preamble'];
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
    <?php echo $jellypress_block['unfiltered_html']; ?>
  </div>
</div>
