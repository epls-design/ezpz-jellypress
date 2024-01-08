<?php

/**
 * Page Hero Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 *        This is either the post ID currently being displayed inside a query loop,
 *        or the post ID of the post hosting this block.
 * @param array $context The context provided to the block by the post or it's parent block.
 * @param array $block_attributes Processed block attributes to be used in template.
 * @param array $fields Array of ACF fields used in this block.
 *
 * Block registered with ACF using block.json
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$block_attributes = jellypress_get_block_attributes($block, $context);
$fields = get_fields();

$allowed_blocks = jellypress_get_allowed_blocks(['ezpz/post-title']);
$block_template = array(
  array(
    'ezpz/post-title', array(), array()
  )
);
$block_template = jellypress_get_block_template($block_template);

$block_attributes['class'] .= ' page-header';
?>
<header class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">
    <div class="row">
      <div class="col md-10 lg-8">
        <InnerBlocks templateLock="all" allowedBlocks="<?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
      </div>
    </div>
  </div>
</header>
