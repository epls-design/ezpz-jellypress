<?php

/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

?>
<form role="search" method="get" class="search-form" id="searchform" action="<?= esc_url(home_url('/')); ?>">
  <label class="search-label" for="s">
    <span class="screen-reader-text"><?php _e('Search for:', 'jellypress'); ?></span>
    <input type="search" class="search-field" name="s" id="s" placeholder="<?php esc_attr_e('I\'m looking for&hellip;', 'jellypress'); ?>" value="<?= get_search_query(); ?>" />
  </label>
  <button type="submit" class="search-submit" name="submit"><span class="screen-reader-text"><?php _e('Search', 'jellypress'); ?></span><?= jellypress_icon('search'); ?></button>
</form>
