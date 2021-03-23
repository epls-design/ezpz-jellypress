<?php
/**
 * Flexible layout: Chart
 * Renders a chart using Charts.js
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$block_title = $block['title'];
$block_preamble = $block['preamble'];

// Chart Variables
$chart_type = $block['chart_type'];
$chart_id = 'chart-'.$chart_type.'-'.$block_id;
$chart_data = jellypress_build_chart_data($block['chart_data'], $chart_type);
$chart_options = jellypress_build_chart_options($chart_type, $block['chart_title']);

if($chart_title = $block['chart_title']) $chart_aria_title = $chart_title;
elseif($block_title) $chart_aria_title = $block_title;
else $chart_aria_title = __('A chart outling information', 'jellypress');

// doughnut-half and pie-half are actually standard versions, we use jellypress_build_chart_options() to change the clip to semi circle
if($chart_type === 'doughnut-half') $chart_type = 'doughnut';
elseif($chart_type === 'pie-half') $chart_type = 'pie';

?>

<section <?php if($block_id_opt = $block['section_id']) echo 'id="'.strtolower($block_id_opt).'"'; ?> class="<?php echo $block_classes;?>">
  <div class="container">

  <?php if ($block_title) : $title_align = $block['title_align']; ?>
    <header class="row justify-center block-title">
      <div class="col md-10 lg-8">
        <h2 class="text-<?php echo $title_align;?>"><?php echo jellypress_bracket_tag_replace($block_title); ?></h2>
      </div>
    </header>
  <?php endif; ?>

  <?php if ($block_preamble) : ?>
    <div class="row justify-center block-preamble">
      <div class="col md-10 lg-8">
        <?php echo jellypress_content($block_preamble); ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="col">
      <div class="chart-container">
        <canvas id="<?php echo $chart_id;?>" aria-label="<?php echo $chart_aria_title;?>" role="img">
          <h3 class="screen-reader-text"><?php echo $chart_aria_title;?></h3>
          <table class="screen-reader-text">
            <tbody>
              <?php foreach($block['chart_data'] as $chart_row) {
                echo '<tr>
                      <td>'.$chart_row['data_label'].'</td>
                      <td>'.$chart_row['data_value'].'</td>
                      </tr>';
              } ?>
            </tbody>
          </table>
        </canvas>
      </div>
    </div>
  </div>

  <?php if ( $block['buttons'] ) : ?>
    <div class="row justify-center">
      <div class="col md-10 lg-8 text-center">
      <?php
        if($title_align == 'center') jellypress_display_cta_buttons($block['buttons'], 'justify-center');
        elseif($title_align == 'right') jellypress_display_cta_buttons($block['buttons'], 'justify-end');
        else jellypress_display_cta_buttons($block['buttons']);
        ?>
      </div>
    </div>
  <?php endif; ?>

</section>

<?php
  add_action('wp_footer',
  jellypress_chart_init($chart_id, $chart_type, $chart_data, $chart_options),
  30); // 30 priority ensures it is placed below the enqueued scripts (priority 20)
?>
