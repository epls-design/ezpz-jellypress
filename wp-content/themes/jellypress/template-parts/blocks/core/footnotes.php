<?php

/**
 * Rewrites the output of core/footnotes on the front end
 * This is called from jellypress_filter_block_core_footnotes()
 *
 * @param array $args['block'] The block settings and attributes.
 * @param string $args['block_content'] The block content.
 * @param string $args['provider'] The video platform
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;
?>
<section class="block block-footnotes bg-white" id="<?php echo get_post_type() . '-footnotes'; ?>">
  <div class="container">
    <?php echo $args['block_content']; ?>
  </div>
</section>