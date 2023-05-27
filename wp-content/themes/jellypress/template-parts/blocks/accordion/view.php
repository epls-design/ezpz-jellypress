<?php

/**
 * Accordion Block Template.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML (empty).
 * @param bool $is_preview True during backend preview render.
 * @param int $post_id The post ID the block is rendering content against.
 *        This is either the post ID currently being displayed inside a query loop,
 *        or the post ID of the post hosting this block.
 * @param array $context The context provided to the block by the post or it's parent block.
 * @param array $block_attributes Processed block attributes to be used in template.
 * @param array $fields Array of ACF fields used in this block.
 *
 * Block registered with ACF using block.json
 * @link https://www.advancedcustomfields.com/resources/blocks/
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$block_attributes = jellypress_get_block_attributes($block);
$fields = get_fields();
$text_align = $block_attributes['text_align'];

$allow_multiple =  $fields['allow_multiple'];
$generate_schema = $fields['generate_schema'];
$allow_multiple =  $fields['allow_multiple'] == 1 ? 'data-multi' : '';

?>

<section class="<?php echo $block_attributes['class']; ?>" <?php echo $block_attributes['anchor']; ?>>
  <div class="container">

    <?php if ($fields['title']) { ?>
      <header class="row justify-center block-title">
        <div class="col md-10 lg-8">
          <h2 class="<?php echo $text_align; ?>">
            <?php echo wp_strip_all_tags($fields['title']); ?>
          </h2>
        </div>
      </header>
    <?php } ?>

    <?php if ($fields['preamble']) { ?>
      <div class="row justify-center block-preamble">
        <div class="col md-10 lg-8 <?php echo $text_align; ?>">
          <?php echo $fields['preamble']; ?>
        </div>
      </div>
    <?php } ?>

    <?php
    if ($accordion_items = $fields['accordion_items']) :

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

      <div class="row accordion justify-center">
        <div class="col md-10 lg-8">
          <section data-aria-accordion data-transition data-default <?php echo $allow_multiple; ?>>
            <?php foreach ($accordion_items as $item) :

              if ($generate_schema) {
                // Push data to schema array
                $questions = array(
                  '@type'          => 'Question',
                  'name'           => wp_strip_all_tags($item['question']),
                  'acceptedAnswer' => array(
                    '@type' => "Answer",
                    'text' => wp_strip_all_tags($item['answer'])
                  )
                );
                array_push($faq_schema['mainEntity'], $questions);
              }

              echo '<h4 class="question" data-aria-accordion-heading>' . $item['question'] . '</h4>';
              echo '<div class="answer" data-aria-accordion-panel>' . $item['answer'] . '</div>';
            endforeach; ?>
          </section>
        </div>
      </div>

    <?php endif; ?>

    <?php if (!empty($fields['buttons'])) : ?>
      <div class="row justify-center">
        <div class="col md-10 lg-8 text-center">
          <?php
          if ($text_align == 'text-center') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-center');
          elseif ($text_align == 'text-right') jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color'], 'justify-end');
          else jellypress_display_cta_buttons($fields['buttons'], $block_attributes['bg_color']);
          ?>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>