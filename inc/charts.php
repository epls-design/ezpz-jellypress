<?php

/**
 * Functions that work with charts.js
 * @link https://www.chartjs.org/docs
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

/**
 * Initializes Charts.js on a given element
 *
 * @param string $chart_id HTML ID of the chart element
 * @param string $chart_type eg. 'bar', 'pie'
 * @param array $chart_data constructed by jellypress_build_chart_data()
 * @param array $chart_options constructed by jellypress_build_chart_data()
 * @return string Javascript to initialize a chart.
 */
if (!function_exists('jellypress_chart_init')) :
  function jellypress_chart_init($chart_id, $chart_type, $chart_data, $chart_options = null)
  {
    // Convert to JSON
    $chart_data_json = json_encode($chart_data);
    // Strip the quotation marks to. Uses a pattern that's highly unlikely to be input by the user.
    // This is added in jellypress_build_chart_data() in the php array as it can't hold variables
    $chart_data_json = str_replace('"#!--!# ', '', $chart_data_json);
    $chart_data_json = str_replace(' #!--!#"', '', $chart_data_json);

    $output =
      "<script type='text/javascript'>
        var ctx = document.getElementById('$chart_id');
        var chart = new Chart(ctx, {
          type: '$chart_type',
          data: $chart_data_json,
          options: {
            $chart_options
          }
        });
      </script>";
    $output = str_replace(array("\r", "\n", "  "), '', $output) . "\n";
    $func = function () use ($output) {
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
if (!function_exists('jellypress_build_chart_data')) :
  function jellypress_build_chart_data($chart_data_array, $chart_type, $allow_multiple = false)
  {

    $chart_datasets = [];
    $chart_axes = [];

    // Start by making a nested array for each data label
    $chart_datasets[]['label'] = $chart_data_array['dataset_label'];

    if ($allow_multiple) {
      foreach ($chart_data_array['additional_dataset_labels'] as $additional_label) {
        // Only if the label is not empty, otherwise we would end up with multiple empty datapoints
        if ($additional_label['dataset_label']) $chart_datasets[]['label'] = $additional_label['dataset_label'];
      }
    }

    $i = 0;
    $j = 1; // Start at 1 because 0 is the mandatory array_key
    foreach ($chart_data_array['chart_data'] as $chart_data_row) {
      $chart_axes[] = $chart_data_row['data_label']; // Add to the chart axes labels
      $chart_datasets[0]['data'][] = $chart_data_row['data_value']; // Add the data_value to the first dataset

      if ($allow_multiple) {
        foreach ($chart_data_row['additional_values'] as $additional_value) {
          // Loop through the additional values and add to the
          // Only if the nested array exists (i.e. if a label was set)
          if ($chart_datasets[$j]) $chart_datasets[$j]['data'][] = $additional_value['data_value'];
          $j++;
        }
      }

      $i++;
      $j = 1;
    }

    // Add styling if more than one dataset
    $i = 0;
    $pie_charts = array('pie', 'pie-half', 'doughnut', 'doughnut-half', 'polarArea');
    // Pie Charts don't display legends correctly when styles are overridden like this.
    if (count($chart_datasets) > 1 && (!in_array($chart_type, $pie_charts))) {
      foreach ($chart_datasets as $dataset) {
        if ($chart_type === 'radar' || $chart_type === 'line') {
          $chart_datasets[$i]['pointBorderColor'] = '#!--!# colorBorders[' . $i . '] #!--!#';
          $chart_datasets[$i]['pointBackgroundColor'] = '#!--!# colorBorders[' . $i . '] #!--!#';
          $chart_datasets[$i]['pointHoverBackgroundColor'] = 'rgb(255,255,255)';
        }
        $chart_datasets[$i]['backgroundColor'] = '#!--!# colorFills[' . $i . '] #!--!#';
        $chart_datasets[$i]['hoverBackgroundColor'] = '#!--!# colorFillsHover[' . $i . '] #!--!#';
        $chart_datasets[$i]['borderColor'] = '#!--!# colorBorders[' . $i . '] #!--!#';
        $i++;
      }
    }

    // Reset styling for single dataset in type line
    if (count($chart_datasets) == 1 && ($chart_type === 'radar' || $chart_type === 'line')) {
      $chart_datasets[0]['pointBorderColor'] = '#!--!# colorBorders[0] #!--!#';
      $chart_datasets[0]['pointBackgroundColor'] = '#!--!# colorBorders[0] #!--!#';
    }

    $chart_data_array = array(
      "labels" => $chart_axes,
      "datasets" => $chart_datasets,
    );

    return $chart_data_array;
  }
endif;

/**
 * Builds Chart Options
 *
 * @param string $chart_type eg. 'bar', 'pie'
 * @param string $chart_title Optional Chart Title
 * @return void
 */
if (!function_exists('jellypress_build_chart_options')) :
  function jellypress_build_chart_options($chart_type, $chart_title = null)
  {

    $chart_options = null; // So we can return null if nothing gets added

    if ($chart_title) {
      $chart_options .= "
        title: {
          display: true,
          text: '" . $chart_title . "',
        },
      ";
    }

    if ($chart_type === 'bar') {
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
      ";
    }

    if ($chart_type === 'stackedBar') {
      $chart_options .= "
      scales: {
        xAxes: [{
          stacked: true,
          ticks: {
            display: true,
            minRotation: 0,
            maxRotation: 90,
            fontSize: 14,
          }
        }],
        yAxes: [{
          stacked: true,
          ticks: {
            beginAtZero: true,
          }
        }],
      },
      ";
    }

    if ($chart_type === 'stackedHorizontalBar') {
      $chart_options .= "
      scales: {
        xAxes: [{
          stacked: true,
          ticks: {
            beginAtZero: true,
          }
        }],
        yAxes: [{
          stacked: true,
          ticks: {
            display: true,
            minRotation: 0,
            maxRotation: 90,
            fontSize: 14,
          }
        }],
      },
      ";
    }

    if ($chart_type === 'horizontalBar') {
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
      ";
    }

    if ($chart_type === 'radar') {
      $chart_options .= "
      legend: {
        display: true,
        position: 'top'
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem.datasetIndex],
            index = tooltipItem.index;
            return dataset.label+ ': '+ dataset.data[index];
          },
          title: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem[0].datasetIndex],
            index = tooltipItem[0].index;
            return data.labels[index];
          },
        }
      },
      ";
    }

    $pie_charts = array('pie', 'pie-half', 'doughnut', 'doughnut-half', 'polarArea');
    if (in_array($chart_type, $pie_charts)) {
      $chart_options .= "
      legend: {
        display: true,
        position: 'top'
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem.datasetIndex],
            index = tooltipItem.index;
            return data.labels[index]+ ': '+ dataset.data[index];
          },
          title: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem[0].datasetIndex],
            index = tooltipItem[0].index;
            return dataset.label;
          },
        }
      },
      ";
    }

    if ($chart_type === 'doughnut-half' || $chart_type === 'pie-half') {
      $chart_options .= "
      circumference: Math.PI,
      rotation: -Math.PI,
      ";
    }

    return $chart_options;
  }
endif;
