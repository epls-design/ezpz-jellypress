<?php

/**
 * Template part to display a statistic card using data from ACF.
 * This card is called by blocks/number-counter
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$statistic = $args['statistic'];
$data_attribs = 'data-count="' . $statistic['statistic_value'] . '"';

$statistic_font_size = 'size-' . $args['font_size'];

$initial_value = '';

if ($prefix = $statistic['statistic_prefix']) {
  $data_attribs .= ' data-prefix="' . $prefix . '"';
  $initial_value .= '<span class="small">' . $prefix . '</span>';
}

$initial_value .= $statistic['statistic_value'];

if ($suffix = $statistic['statistic_suffix']) {
  $data_attribs .= ' data-suffix="' . $suffix . '"';
  $initial_value .= '<span class="small">' . $suffix . '</span>';
}

if ($duration = $statistic['count_duration']) $data_attribs .= ' data-duration="' . ($duration * 1000) . '"';

$statistic_title = $statistic['statistic_title'];
$statistic_description = $statistic['statistic_description'];

$block_bg_color = isset($args['block_bg_color']) ? $args['block_bg_color'] : null;

// Determine what button color to use
switch ($block_bg_color) {
    //case 'white':
    //  $button_color = ' primary';
    //  break;
  default:
    $button_color = '';
}
?>
<div class="card card-statistic no-border">
  <div class="card-section">
    <?php echo '<div class="count-to number ' . $statistic_font_size . '" ' . $data_attribs . '>' . $initial_value . '</div>'; ?>
    <h3><?php echo jellypress_content($statistic_title); ?></h3>
    <?php if ($statistic_description) {
      echo '<div class="hide-below-md">' . jellypress_content($statistic_description) . '</div>';
    } ?>
  </div>
  <?php if ($button = $statistic['statistic_button']) :
    echo '<div class="card-section card-footer">
        <a class="button' . $button_color . '" href="' . $button['url'] . '" title="' . $button['title'] . '" target="' . $button['target'] . '">' . $button['title'] . '</a>
        </div>';
  endif; ?>
</div>