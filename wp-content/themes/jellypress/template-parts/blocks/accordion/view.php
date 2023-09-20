<?php

/**
 * Accordion Block Template. Can only be used inside ezpz/content.
 *
 * @param array $block The block settings and attributes.
 * @param string $content The block inner HTML
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
$allowed_blocks = jellypress_get_allowed_blocks();
$block_template = jellypress_get_block_template();

$fields = get_fields();

$generate_schema = $fields['generate_schema'];
$allow_multiple =  $fields['allow_multiple'] == 1 ? 'data-multi' : '';

jellypress_acf_placeholder(
  $fields['accordion_items'][0]['question'],
  __('Please add accordion items to this block - click here to get started.', 'jellypress'),
  $is_preview
);

if ($accordion_items = $fields['accordion_items']) {

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
  <div data-aria-accordion data-transition data-default <?php echo $allow_multiple; ?>>
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
  </div>
<?php } ?>