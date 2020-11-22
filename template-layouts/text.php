<?php
/**
 * Flexible layout: Text block
 * Renders a block containing a column of WYSIWIG text
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];

$block_title = $block['title'];
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
    <?php jellypress_content($block['text']); ?>
    <?php jellypress_display_cta_buttons($block['buttons']); ?>
  </div>
</div>
