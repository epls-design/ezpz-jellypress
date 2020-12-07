<?php
/**
 * Flexible layout: iFrame
 * Renders an iFrame block
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
//var_dump($block);

$block_title = $block['title'];
$website_url = $block['website_url'];
$block_is_fullwidth = $block['full_width'];
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
  <div class="row block-preamble">
    <div class="col">
      <?php jellypress_content($block_preamble); ?>
    </div>
  </div>
<?php endif; ?>

<?php if ($website_url) : ?>
<div class="row">
  <div class="col">
    <?php if ( $block_is_fullwidth == 1 ){ echo '<div class="vw-100">'; }?>
      <div class="embed-container">
        <iframe class="embedded-iframe" src="<?php echo $website_url; ?>"></iframe>
      </div>
    <?php if ( $block_is_fullwidth == 1 ){ echo '</div>'; }?>
  </div>
</div>
<?php endif; ?>
