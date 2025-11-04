<?php

/**
 * Server-side rendering of the `ezpz/section` block.
 * We do this because it makes it easier to change the markup without having to fix corrupted blocks.
 * There is support for revisions built into Gutenberg, but it's overkill for this use case and super clunky.
 */

if (!defined('ABSPATH')) exit;
$wrapper_attributes = get_block_wrapper_attributes(
	['id' => isset($attributes['anchor']) ? $attributes['anchor'] : '']
);

?>
<section <?php echo $wrapper_attributes; ?>>
  <div class="container">
    <div class="row justify-center">
      <div class="col md-10 lg-8">
        <?php
				echo $content;
				?>
      </div>
    </div>
  </div>
</section>
