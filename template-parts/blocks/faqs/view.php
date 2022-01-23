<?php

/**
 * Flexible layout: FAQs
 * Renders a section containing FAQs.
 * The editor can add an optional title and preamble
 * and then use a repeater field to add as many FAQs as they want.
 * FAQs get added to website schema as well for super-friendly SEO.
 * Uses a11y_accordions for ARIA accessible accordions https://github.com/scottaohara/a11y_accordions
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

// Get Params from get_template_part:
$block = $args['block'];
$block_id = $args['block_id'];
$block_classes = $args['block_classes'];
//var_dump($block);

$allow_multiple =  $block['allow_multiple'];

if ($block['default_panel']) $default_panel = 'data-default=' . $block['default_panel'];
else $default_panel = 'data-default';

$generate_schema = $block['generate_schema'];

$block_title = $block['title'];
$block_preamble = $block['preamble'];
?>

<section <?php if ($block_id_opt = $block['section_id']) echo 'id="' . strtolower($block_id_opt) . '"'; ?> class="<?= $block_classes; ?>">
  <div class="container">

    <?php if ($block_title) : $title_align = $block['title_align']; ?>
      <header class="row justify-center block-title">
        <div class="col md-10 lg-8">
          <h2 class="text-<?= $title_align; ?>"><?= jellypress_bracket_tag_replace($block_title); ?></h2>
        </div>
      </header>
    <?php endif; ?>

    <?php if ($block_preamble) : ?>
      <div class="row justify-center block-preamble">
        <div class="col md-10 lg-8">
          <?= jellypress_content($block_preamble); ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($faqs = $block['faq_rows']) :

      wp_enqueue_script('aria-accordion');

      global $faq_schema;
      if (empty($faq_schema) && $generate_schema) {
        // Initialise if empty.
        $faq_schema = array(
          '@context'   => "https://schema.org",
          '@type'      => "FAQPage",
          'mainEntity' => array()
        );
      };
    ?>

      <div class="row faqs justify-center">
        <div class="col md-10 lg-8">
          <section data-aria-accordion data-transition <?php if ($allow_multiple == 1) {
                                                          echo 'data-multi ';
                                                        }
                                                        echo $default_panel; ?> id="faqs-<?= $block_id; ?>">
            <?php foreach ($faqs as $faq) :

              if ($generate_schema) {
                // Push data to schema array
                $questions = array(
                  '@type'          => 'Question',
                  'name'           => wp_strip_all_tags($faq['faq_question']),
                  'acceptedAnswer' => array(
                    '@type' => "Answer",
                    'text' => wp_strip_all_tags($faq['faq_answer'])
                  )
                );
                array_push($faq_schema['mainEntity'], $questions);
              }

              echo '<h4 class="faq-question" data-aria-accordion-heading>' . $faq['faq_question'] . '</h4>';
              echo '<div class="faq-answer" data-aria-accordion-panel>' . jellypress_content($faq['faq_answer']) . '</div>';
            endforeach; ?>
          </section>
        </div>
      </div>

    <?php endif; ?>

    <?php if (!empty($block['buttons'])) : ?>
      <div class="row justify-center">
        <div class="col md-10 lg-8 text-center">
          <?php
          if ($title_align == 'center') jellypress_display_cta_buttons($block['buttons'], 'justify-center');
          elseif ($title_align == 'right') jellypress_display_cta_buttons($block['buttons'], 'justify-end');
          else jellypress_display_cta_buttons($block['buttons']);
          ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>
