<?php
/**
 * Template part for displaying a basic post card
 * Called from Blocks/Cards
 *
 * @package jellypress
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$card = $args['card'];
//var_dump($card);

$card_link = $card['card_link'];

?>

<div class="card">

  <?php if($card_image = $card['card_image']) : ?>
    <figure class="card-image">
      <?php
      if($card_link) echo '<a href="'.$card_link['url'].'" title="'.$card_link['title'].'" target="'.$card_link['target'].'" aria-hidden="true" tabindex="-1">';
      echo wp_get_attachment_image( $card_image, 'medium_landscape' );
      if($card_link) echo '</a>';
      ?>
    </figure>
  <?php endif; ?>

  <div class="card-section">
    <?php echo jellypress_content($card['card_text']); ?>
  </div>

  <?php if($card_link) : ?>
  <footer class="card-section card-footer">
    <?php echo '<a class="button small" href="'.$card_link['url'].'" title="'.$card_link['title'].'" target="'.$card_link['target'].'">'.$card_link['title'].'</a>'; ?>
  </footer>
  <?php endif; ?>

</div>
