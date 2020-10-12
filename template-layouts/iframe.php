<?php
/**
 * Flexible layout: iFrame
 * Renders an iFrame block
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$block_id = get_query_var('block_id');
$jellypress_block = get_query_var('jellypress_block');
$block_title = $jellypress_block['title'];
$website_url = $jellypress_block['website_url'];
$block_is_fullwidth = $jellypress_block['full_width'];
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
  <div class="row block-preamble">
    <div class="col">
      <?php jellypress_content($block_preamble); ?>
    </div>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <?php if ( $block_is_fullwidth == 1 ){ echo '<div class="vw-100">'; }?>
      <div class="embed-container">
        <iframe class="embedded-iframe" src="<?php echo $website_url; ?>"></iframe>
      </div>
    <?php if ( $block_is_fullwidth == 1 ){ echo '</div>'; }?>
  </div>
</div>
