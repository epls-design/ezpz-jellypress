<?php

/**
 * Template part for displaying a timeline item
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$main_title =  $args['main_title'];
$time_title =  $args['time_title'];
$time_text =  $args['time_text'];
$count =  $args['count'];
$date_unixtime =   strtotime($args['date']);
$date_now = time();
$magic = $args['magic_columns']; // TODO: TEST WHAT THIS DOES IN MAGIC COLS

$date_formatted = date_i18n(get_option('date_format'), $date_unixtime); // Format for WP

//Creating the week diffrence


$one_week = 604800;

$two_week_date = $date_now - ($one_week * 2);
$four_week_date = $date_now - ($one_week * 4);
$six_week_date = $date_now - ($one_week * 6);
$eight_week_date = $date_now - ($one_week * 8);
$ten_week_date = $date_now - ($one_week * 10);
$twelve_week_date = $date_now - ($one_week * 12);


if ($twelve_week_date >= $date_unixtime)
  $opacity = '0-3';

elseif ($ten_week_date >= $date_unixtime)
  $opacity = 'o-4';

elseif ($eight_week_date >= $date_unixtime)
  $opacity = 'o-5';

elseif ($six_week_date >= $date_unixtime)
  $opacity = 'o-6';

elseif ($four_week_date >= $date_unixtime)
  $opacity = 'o-7';

elseif ($two_week_date >= $date_unixtime)
  $opacity = 'o-8';

elseif ($date_now >= $date_unixtime)
  $opacity = 'o-9';


if ($magic) :
  echo '<div class="timeline-container right">';
elseif ($count % 2 == 0) :
  echo '<div class="timeline-container left">';
else :
  echo '<div class="timeline-container right">';
endif;

?>

<?php
// TODO: Add ACF swatches to set the bg color of the component.
// The circle marker border color should also match
// TODO: Display date on here as option
?>
<div class="timeline-item bg-neutral-900<?php if ($opacity) echo ' ' . $opacity; ?>">
  <?php if ($main_title) echo '<h3>' . $main_title . '</h3>'; ?>
  <p class="bold mb-0"><?php echo $time_title; ?></p>
  <?php if ($time_text) echo jellypress_content($time_text); ?>
</div>
</div>
