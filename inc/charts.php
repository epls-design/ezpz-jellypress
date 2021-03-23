<?php
/**
 * Functions that work with charts.js
 * @link https://www.chartjs.org/docs
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Initializes Charts.js on a given element
 *
 * @param string $chart_id HTML ID of the chart element
 * @param string $chart_type eg. 'bar', 'pie'
 * @param array $chart_data constructed by jellypress_build_chart_data()
 * @param array $chart_options constructed by jellypress_build_chart_data()
 * @return string Javascript to initialize a chart.
 */
if (!function_exists('jellypress_chart_init')):
  function jellypress_chart_init($chart_id, $chart_type, $chart_data, $chart_options = null ) {
    $output =
      "<script type='text/javascript'>
        var ctx = document.getElementById('$chart_id');
        var chart = new Chart(ctx, {
          type: '$chart_type',
          data: {
              $chart_data
          },
          options: {
            $chart_options
          }
        });
      </script>";
    $output = str_replace(array("\r", "\n","  "), '', $output); // TODO: Add this to all enqueue
    $func = function () use($output) {
      print $output;
    };
    wp_enqueue_script('charts');
    wp_enqueue_script('charts-opts');
    return $func;
  }
endif;

/**
 * Builds chart data
 *
 * @param array $chart_data_array An Array of data; this function expects it to be structured in a certain way from ACF
 * @param string $chart_type eg. 'bar', 'pie'
 * @return json encoded data for passing to jellypress_chart_init()
 */
if (!function_exists('jellypress_build_chart_data')):
  function jellypress_build_chart_data($chart_data_array) {
    $chart_data_labels = [];
    $chart_data_values = [];

    foreach($chart_data_array as $chart_data_row) {
      if ($chart_data_row['data_label']) {
        array_push($chart_data_labels, $chart_data_row['data_label']);
      }
      else {
        array_push($chart_data_labels, '');
      }
      array_push($chart_data_values, $chart_data_row['data_value']);
    }

    // TODO: Add another repeated for graphs like Bar graph where there might be multiple datasets
    $chart_data_json = "
      labels: ".json_encode($chart_data_labels).",
      datasets: [{
        data: ".json_encode($chart_data_values).",
        label: 'People'
      }],
      ";
    return $chart_data_json;
  }
endif;

/**
 * Builds Chart Options
 *
 * @param string $chart_type eg. 'bar', 'pie'
 * @param string $chart_title Optional Chart Title
 * @return void
 */
if (!function_exists('jellypress_build_chart_options')):
  function jellypress_build_chart_options($chart_type, $chart_title = null) {

    $chart_options = null; // So we can return null if nothing gets added

    if ($chart_title) {
      $chart_options .= "
        title: {
          display: true,
          text: '". $chart_title ."',
        },
      ";
    }

    if($chart_type === 'bar') {
      $chart_options .= "
      scales: {
        xAxes: [{
          ticks: {
            display: true,
            minRotation: 0,
            maxRotation: 90,
            fontSize: 14,
          }
        }],
        yAxes: [{
          ticks: {
            beginAtZero: true,
          }
        }],
      },
      "; // TODO: MOVE fontSize TO CHARTS-OPTS
    }

    if($chart_type === 'horizontalBar') {
      $chart_options .= "
      scales: {
        xAxes: [{
          ticks: {
            beginAtZero: true,
          }
        }],
        yAxes: [{
          ticks: {
            display: true,
            minRotation: 0,
            maxRotation: 90,
            fontSize: 14,
          }
        }],
      },
      "; // TODO: MOVE fontSize TO CHARTS-OPTS
    }

    // TODO: Add Line Option

    if($chart_type === 'pie' || $chart_type === 'pie-half' || $chart_type === 'doughnut' || $chart_type === 'doughnut-half') {
      $chart_options .= "
      legend: {
        display: true,
        position: 'top'
      },
      ";
    }

    if($chart_type === 'doughnut-half' || $chart_type === 'pie-half') {
      $chart_options .= "
      circumference: Math.PI,
      rotation: -Math.PI,
      ";
    }

    if($chart_type === 'doughnut-half' || $chart_type === 'doughnut') {
      $chart_options .= "
      cutoutPercentage: 50,
      ";
    }
    return $chart_options;
  }
endif;
