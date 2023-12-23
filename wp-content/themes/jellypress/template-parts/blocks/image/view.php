<?php

/**
 * Image Block Template.
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


$block_attributes = jellypress_get_block_attributes($block);

$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = array(
  array(
    'core/image', array()
  )
);
$block_template =  jellypress_get_block_template($block_template);

$fields = get_fields();
$container_class = 'container';

$block_width = isset($block_attributes['align']) ? $block_attributes['align'] : '';
if ($block_width == 'wide') $container_class .= ' is-wide';
elseif ($block_width == 'full') $block_attributes['class'] .= ' is-full-width';
elseif ($block_width === 'center') $justify = 'center';
?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>

  <div class="<?php echo $container_class; ?>">
    <?php
    if ($block_width === 'full') echo '<div class="vw-100">';
    elseif ($block_width === 'center') echo '<div class="row justify-' . $justify . '"><div class="col md-10 lg-8">';

    if ($block_width === 'full') $size = 'full';
    else $size = 'large';
    ?>
    <InnerBlocks templateLock="all" allowedBlocks=" <?php echo $allowed_blocks; ?>" template="<?php echo $block_template; ?>" />
    <?php
    if ($block_width === 'full') echo '</div>';
    elseif ($block_width === 'center') echo '</div></div>'; ?>
  </div>

</section>