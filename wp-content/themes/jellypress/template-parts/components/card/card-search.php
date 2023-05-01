<?php

/**
 * Template part for displaying a search result card
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$card_class = 'card bg-white';
$loaded = isset($args['loaded']) ? true : false;
if ($loaded == true) {
  $card_class .= ' loaded';
};

?>

<article <?php post_class($card_class); ?>>

  <header class="card-section entry-header">
    <?php the_title('<h2 class="entry-title h4"><a href="' . esc_url(get_permalink()) . '" tabindex="-1">', '</a></h2>'); ?>
  </header>

  <?php if (jellypress_generate_excerpt()) echo '<div class="card-section entry-content">' . jellypress_generate_excerpt(200, true) . '</div>'; ?>

    <div class="card-section entry-meta">
      <span class="posted-on">
      <?php _e('Last updated ','jellypress'); ?>
        <time class="updated" datetime="<?php echo get_the_modified_date(DATE_W3C);?>">
          <?php echo get_the_modified_date(); ?>
        </time>
      </span>
    </div>

  <footer class="card-section card-footer">
    <a class="button small" href="<?php the_permalink(); ?>" rel="bookmark"><?php _e('Continue Reading <span class="screen-reader-text">' . get_the_title() . '</span>', 'jellypress'); ?></a>
  </footer>

</article>
