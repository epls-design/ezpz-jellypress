<?php
/**
 * Flexible layout: Text block
 * Renders a block containing a column of WYSIWIG text
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$block_id = get_query_var('block_id');
$jellypress_block = get_query_var('jellypress_block');
$block_title = $jellypress_block['title'];
?>

<?php if ($block_title) : ?>
  <header class="row block-title">
    <div class="col">
      <h2><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
    </div>
  </header>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php jellypress_content($jellypress_block['text']); ?>
    <?php jellypress_display_cta_buttons($jellypress_block['buttons']); ?>
  </div>
</div>
