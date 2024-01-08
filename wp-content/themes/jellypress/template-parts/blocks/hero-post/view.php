<?php

/**
 * Post Hero Block Template.
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

$block_attributes['class'] .= ' page-header';
?>
<header class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">
    <div class="row">
      <div class="col md-10 lg-8">
        <?php
        // TODO: Add inner block for core/post-title, will need to strip out all the styling
        the_title('<h1 class="page-title">', '</h1>');
        ?>
        <div class="entry-meta">
          <?php
          jellypress_posted_on();
          jellypress_posted_by();
          ?>
        </div>
      </div>
    </div>
  </div>
</header>
