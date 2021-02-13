<?php
/**
 * Flexible layout: Number Counter
 * Allows the editor to add a number of values (statistics)
 * with optional prefix/suffix and duration.
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
        <?php jellypress_content($block_preamble); ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ( $statistics = $block['statistics'] ) :

    $stats_have_large_numbers = false;
    $stats_have_xlarge_numbers = false;

    foreach($statistics as $statistic) {
      // If any statistics are above 5 characters, we need to set a smaller font-size
      if(strlen($statistic['statistic_value']) >= 7) {
        $stats_have_xlarge_numbers = true;
      }
      elseif(strlen($statistic['statistic_value']) >= 5) {
        $stats_have_large_numbers = true;
      }
    }

    if($stats_have_xlarge_numbers == true) $statistic_font_size = 'xsmall';
    elseif($stats_have_large_numbers == true) $statistic_font_size = 'small';
    else $statistic_font_size = 'regular';

    echo '<div class="row equal-height statistics">';
      foreach($statistics as $statistic):
        echo '<div class="col xs-6 md-4">';
          $card_params = array(
            'statistic' => $statistic,
            'font_size' => $statistic_font_size,
          );
          get_template_part( 'template-parts/components/card/card', 'statistic', $card_params );
        echo '</div>';
      endforeach;
    echo '</div>';
  endif; ?>

  </div>
</section>
